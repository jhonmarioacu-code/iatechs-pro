@props([
    'label' => '',
    'value' => '',
    'trend' => '',
])

<article class="kpi-card">
    <span class="kpi-label">{{ $label }}</span>
    <strong class="kpi-value">{{ $value }}</strong>
    <span class="kpi-trend">{{ $trend }}</span>
</article>

