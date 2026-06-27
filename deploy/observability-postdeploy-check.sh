#!/usr/bin/env bash

set -Eeuo pipefail
IFS=$'\n\t'

APP_BASE_URL="${APP_BASE_URL:-http://127.0.0.1}"
APP_ENV_FILE="${APP_ENV_FILE:-.env}"
OBS_VALIDATE_PROM_STACK="${OBS_VALIDATE_PROM_STACK:-false}"

log() {
    printf '[obs-check] %s\n' "$*"
}

fail() {
    printf '[obs-check][ERROR] %s\n' "$*" >&2
    exit 1
}

trim_quotes() {
    local value="$1"
    value="${value%\"}"
    value="${value#\"}"
    value="${value%\'}"
    value="${value#\'}"
    printf '%s' "$value"
}

read_env_key() {
    local key="$1"
    local file="$2"
    if [[ ! -f "$file" ]]; then
        printf ''
        return
    fi

    local raw
    raw="$(grep -E "^${key}=" "$file" | tail -n 1 | cut -d= -f2- || true)"
    trim_quotes "$raw"
}

curl_json_ok() {
    local url="$1"
    curl -fsS "$url" >/dev/null
}

check_app_health() {
    log "Checking application health endpoint"
    curl_json_ok "${APP_BASE_URL}/health" || fail "Health endpoint failed: ${APP_BASE_URL}/health"
}

resolve_exporter_token() {
    local token
    token="$(read_env_key "OBS_EXPORTER_TOKEN" "$APP_ENV_FILE")"

    if [[ -n "$token" ]]; then
        printf '%s' "$token"
        return
    fi

    local tokenFile
    tokenFile="$(read_env_key "OBS_EXPORTER_TOKEN_FILE" "$APP_ENV_FILE")"
    if [[ -n "$tokenFile" && -f "$tokenFile" ]]; then
        head -n 1 "$tokenFile" | tr -d '\r'
        return
    fi

    printf ''
}

check_metrics_endpoint() {
    local exporterEnabled token metricsPayload
    exporterEnabled="$(read_env_key "OBS_EXPORTER_ENABLED" "$APP_ENV_FILE")"

    if [[ "${exporterEnabled,,}" != "true" ]]; then
        log "OBS_EXPORTER_ENABLED is not true; metrics endpoint check skipped"
        return
    fi

    token="$(resolve_exporter_token)"
    [[ -n "$token" ]] || fail "OBS_EXPORTER_ENABLED=true but no token resolved (OBS_EXPORTER_TOKEN / OBS_EXPORTER_TOKEN_FILE)."

    log "Checking protected Prometheus metrics endpoint"
    metricsPayload="$(curl -fsS \
        -H "Authorization: Bearer ${token}" \
        "${APP_BASE_URL}/api/metrics/prometheus")" || fail "Metrics endpoint request failed."

    grep -q "iatechs_payment_success_rate_24h_percent" <<<"$metricsPayload" || fail "Expected metric iatechs_payment_success_rate_24h_percent not found."
    grep -q "iatechs_observability_overall_status" <<<"$metricsPayload" || fail "Expected metric iatechs_observability_overall_status not found."
}

docker_container_exists() {
    local name="$1"
    command -v docker >/dev/null 2>&1 || return 1
    docker ps --format '{{.Names}}' | grep -q "^${name}$"
}

check_prometheus_stack() {
    local runChecks="${OBS_VALIDATE_PROM_STACK,,}"
    if [[ "$runChecks" != "true" ]]; then
        if ! docker_container_exists "iatechs-pro-prometheus"; then
            log "Prometheus container not detected and OBS_VALIDATE_PROM_STACK=false; stack checks skipped"
            return
        fi
    fi

    command -v docker >/dev/null 2>&1 || fail "Docker is required for Prometheus/Grafana stack checks."

    log "Checking Prometheus health"
    curl_json_ok "http://127.0.0.1:9090/-/healthy" || fail "Prometheus health endpoint failed."

    local targetsPayload queryPayload attempt maxAttempts sleepSeconds
    maxAttempts=24
    sleepSeconds=5

    for attempt in $(seq 1 "$maxAttempts"); do
        targetsPayload="$(curl -fsS "http://127.0.0.1:9090/api/v1/targets" || true)"
        if grep -q '"job":"iatechs_app"' <<<"$targetsPayload" && grep -q '"health":"up"' <<<"$targetsPayload"; then
            log "Prometheus target iatechs_app is healthy (targets API)"
            break
        fi

        queryPayload="$(curl -fsS -G --data-urlencode 'query=up{job="iatechs_app"}' "http://127.0.0.1:9090/api/v1/query" || true)"
        if grep -q '"job":"iatechs_app"' <<<"$queryPayload" && grep -Eq '"value":\[[^]]*,"1"\]' <<<"$queryPayload"; then
            log "Prometheus target iatechs_app is healthy (query API)"
            break
        fi

        if [[ "$attempt" -eq "$maxAttempts" ]]; then
            local targetsSnippet
            targetsSnippet="$(printf '%s' "$targetsPayload" | tr '\n' ' ' | cut -c1-500)"
            fail "Prometheus target iatechs_app is not healthy after ${maxAttempts} attempts. Last targets snippet: ${targetsSnippet}"
        fi

        log "Prometheus target iatechs_app not healthy yet (attempt ${attempt}/${maxAttempts}), waiting ${sleepSeconds}s"
        sleep "$sleepSeconds"
    done

    log "Checking Alertmanager health"
    curl_json_ok "http://127.0.0.1:9093/-/healthy" || fail "Alertmanager health endpoint failed."

    log "Checking Grafana health"
    curl_json_ok "http://127.0.0.1:3000/api/health" || fail "Grafana health endpoint failed."
}

main() {
    check_app_health
    check_metrics_endpoint
    check_prometheus_stack
    log "Observability postdeploy checks completed successfully"
}

main "$@"

