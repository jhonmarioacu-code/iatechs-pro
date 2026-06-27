const DURATION_PATTERN = /^(\d+)(ms|s|m|h|d)$/i;

const UNIT_TO_MILLISECONDS: Record<string, number> = {
  ms: 1,
  s: 1000,
  m: 60_000,
  h: 3_600_000,
  d: 86_400_000
};

export function durationToMilliseconds(value: string | undefined, fallback: number): number {
  if (!value) {
    return fallback;
  }

  const match = value.trim().match(DURATION_PATTERN);
  if (!match) {
    return fallback;
  }

  const amount = Number(match[1]);
  const unit = match[2].toLowerCase();
  const multiplier = UNIT_TO_MILLISECONDS[unit];

  if (!Number.isFinite(amount) || amount <= 0 || !multiplier) {
    return fallback;
  }

  return amount * multiplier;
}

export function durationToSeconds(value: string | undefined, fallbackSeconds: number): number {
  const fallback = Math.max(1, Math.floor(fallbackSeconds * 1000));
  return Math.max(1, Math.floor(durationToMilliseconds(value, fallback) / 1000));
}

