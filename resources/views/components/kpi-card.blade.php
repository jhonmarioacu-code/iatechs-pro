@props([
    'label' => '',
    'value' => '',
    'trend' => '',
])

@php
    $token = strtoupper((string) str($label)->take(2));
    $trendValue = strtolower(trim((string) $trend));

    $trendClass = 'is-neutral';
    if (str_starts_with($trendValue, '+')) {
        $trendClass = 'is-up';
    } elseif (str_starts_with($trendValue, '-')) {
        $trendClass = 'is-down';
    }

    $metaLabel = $trendClass === 'is-neutral' ? 'segmento actual' : 'vs periodo anterior';
@endphp

<article class="kpi-card">
    <span class="kpi-icon">{{ $token }}</span>
    <div class="kpi-body">
        <span class="kpi-label">{{ $label }}</span>
        <strong class="kpi-value">{{ $value }}</strong>
        <div class="kpi-meta">
            <span class="kpi-trend {{ $trendClass }}">{{ $trend }}</span>
            <small>{{ $metaLabel }}</small>
        </div>
    </div>
</article>
