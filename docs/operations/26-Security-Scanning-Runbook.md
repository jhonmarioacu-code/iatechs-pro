# IAtechs Pro

# Operations

## 26-Security-Scanning-Runbook

---

# Objetivo

Definir el proceso oficial de escaneo SCA/SAST y secretos para prevenir vulnerabilidades en cada entrega.

---

# Alcance

Incluye:

```text
Dependencias PHP y Node (SCA)
Vulnerabilidades de filesystem y librerias (Trivy)
Misconfiguraciones IaC (Trivy config)
Filtraciones de secretos en repositorio (Gitleaks)
```

---

# Pipeline oficial

Workflow:

```text
.github/workflows/security.yml
```

Triggers:

```text
Push y Pull Request en main/develop
Ejecucion semanal automatica (lunes 07:00 UTC)
Ejecucion manual (workflow_dispatch)
```

---

# Jobs y controles

1. `dependency-sca`

```text
composer audit --locked
npm audit --omit=dev --audit-level=high
```

2. `trivy-fs`

```text
Escaneo HIGH/CRITICAL de vulnerabilidades en filesystem
```

3. `trivy-config`

```text
Escaneo HIGH/CRITICAL de misconfiguraciones IaC
```

4. `secret-scan`

```text
Deteccion de secretos expuestos con Gitleaks
```

---

# Politica de fallo

El pipeline de seguridad es bloqueante:

```text
Si detecta vulnerabilidades HIGH/CRITICAL o secretos expuestos, falla el workflow.
```

---

# Operacion local recomendada

Antes de abrir PR:

```bash
composer audit --locked
npm audit --omit=dev --audit-level=high
```

---

# Respuesta a hallazgos

1. Corregir dependencia vulnerable o subir version segura.
2. Rotar credenciales si se detecta secreto.
3. Documentar mitigacion en el PR.
4. Re-ejecutar workflow hasta quedar en verde.

