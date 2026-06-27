@extends('layouts.admin')

@section('portal-content')
    @php
        $incomeSeries = [4200, 7600, 8200, 17800, 13600, 16200, 10800, 16800, 18600];
        $expenseSeries = [2900, 4300, 3800, 6400, 4100, 7900, 4300, 6400, 9100];
        $chartLabels = ['1 May', '5 May', '9 May', '13 May', '17 May', '21 May', '25 May', '28 May', '31 May'];

        $chartWidth = 760;
        $chartHeight = 250;
        $seriesLength = count($incomeSeries);
        $pointStep = $seriesLength > 1 ? $chartWidth / ($seriesLength - 1) : 0;
        $chartMax = (float) max(max($incomeSeries), max($expenseSeries));

        $incomePoints = [];
        $expensePoints = [];
        $incomeDots = [];
        $expenseDots = [];

        for ($index = 0; $index < $seriesLength; $index++) {
            $x = round($pointStep * $index, 2);

            $incomeY = round($chartHeight - (($incomeSeries[$index] / $chartMax) * $chartHeight), 2);
            $expenseY = round($chartHeight - (($expenseSeries[$index] / $chartMax) * $chartHeight), 2);

            $incomePoints[] = $x.','.$incomeY;
            $expensePoints[] = $x.','.$expenseY;
            $incomeDots[] = ['x' => $x, 'y' => $incomeY];
            $expenseDots[] = ['x' => $x, 'y' => $expenseY];
        }

        $countries = [
            ['code' => 'US', 'name' => 'Estados Unidos', 'users' => 2456],
            ['code' => 'ES', 'name' => 'Espana', 'users' => 1245],
            ['code' => 'MX', 'name' => 'Mexico', 'users' => 1021],
            ['code' => 'BR', 'name' => 'Brasil', 'users' => 842],
            ['code' => 'AR', 'name' => 'Argentina', 'users' => 512],
        ];

        $projects = [
            ['icon' => 'EL', 'name' => 'Plataforma E-learning', 'meta' => 'Actualizado hace 2 horas', 'state' => 'Activo', 'stateClass' => 'is-active', 'tone' => '#6b7bff'],
            ['icon' => 'CR', 'name' => 'CRM Corporativo', 'meta' => 'Actualizado hace 5 horas', 'state' => 'Activo', 'stateClass' => 'is-active', 'tone' => '#4db1ff'],
            ['icon' => 'SF', 'name' => 'Sistema de Facturacion', 'meta' => 'Actualizado hace 1 dia', 'state' => 'En progreso', 'stateClass' => 'is-progress', 'tone' => '#ff8d72'],
            ['icon' => 'AM', 'name' => 'App Movil Clientes', 'meta' => 'Actualizado hace 2 dias', 'state' => 'En revision', 'stateClass' => 'is-review', 'tone' => '#43c98d'],
        ];

        $activities = [
            ['initials' => 'MG', 'title' => 'Maria Garcia creo un nuevo proyecto', 'context' => 'Plataforma E-learning', 'time' => 'Hace 2 horas'],
            ['initials' => 'JL', 'title' => 'Juan Lopez actualizo la configuracion', 'context' => 'CRM Corporativo', 'time' => 'Hace 5 horas'],
            ['initials' => 'AT', 'title' => 'Ana Torres subio un nuevo archivo', 'context' => 'Diseno UI/UX', 'time' => 'Hace 1 dia'],
            ['initials' => 'CR', 'title' => 'Carlos Ruiz elimino un registro', 'context' => 'Usuario inactivo', 'time' => 'Hace 2 dias'],
        ];

        $revenueMix = [
            ['label' => 'Suscripciones', 'amount' => 48563, 'color' => '#5b5dff'],
            ['label' => 'Servicios', 'amount' => 32654, 'color' => '#25d18f'],
            ['label' => 'Consultorias', 'amount' => 24125, 'color' => '#f6be3f'],
            ['label' => 'Otros', 'amount' => 19221, 'color' => '#4aa9ff'],
        ];

        $revenueTotal = array_sum(array_column($revenueMix, 'amount'));
        $ringCursor = 0.0;
        $ringSegments = [];

        foreach ($revenueMix as $row) {
            $segmentPercent = $revenueTotal > 0 ? ($row['amount'] / $revenueTotal) * 100 : 0;
            $segmentStart = $ringCursor;
            $ringCursor += $segmentPercent;
            $ringSegments[] = $row['color'].' '.round($segmentStart, 2).'% '.round($ringCursor, 2).'%';
        }

        $ringGradient = implode(', ', $ringSegments);
    @endphp

    <section class="admin-dashboard">
        <div class="admin-grid admin-grid-main">
            <article class="surface-card admin-surface admin-chart-card">
                <header class="admin-card-header">
                    <div>
                        <h2>Ingresos</h2>
                        <p>Comparativo mensual entre ingresos y gastos operativos.</p>
                    </div>
                    <span class="admin-chip">Mensual</span>
                </header>

                <div class="admin-legend">
                    <span><i class="admin-dot is-primary"></i>Ingresos</span>
                    <span><i class="admin-dot is-secondary"></i>Gastos</span>
                </div>

                <div class="admin-line-chart" role="img" aria-label="Grafico mensual de ingresos y gastos">
                    <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="none">
                        @for ($line = 1; $line <= 4; $line++)
                            <line x1="0" y1="{{ ($chartHeight / 5) * $line }}" x2="{{ $chartWidth }}" y2="{{ ($chartHeight / 5) * $line }}" class="admin-grid-line" />
                        @endfor

                        <polyline points="{{ implode(' ', $expensePoints) }}" class="admin-line admin-line-secondary" />
                        <polyline points="{{ implode(' ', $incomePoints) }}" class="admin-line admin-line-primary" />

                        @foreach ([$incomeDots[0], $incomeDots[array_key_last($incomeDots)]] as $dot)
                            <circle cx="{{ $dot['x'] }}" cy="{{ $dot['y'] }}" r="4" class="admin-line-node is-primary" />
                        @endforeach

                        @foreach ([$expenseDots[0], $expenseDots[array_key_last($expenseDots)]] as $dot)
                            <circle cx="{{ $dot['x'] }}" cy="{{ $dot['y'] }}" r="4" class="admin-line-node is-secondary" />
                        @endforeach
                    </svg>
                </div>

                <div class="admin-axis-labels">
                    @foreach ($chartLabels as $label)
                        <span>{{ $label }}</span>
                    @endforeach
                </div>
            </article>

            <article class="surface-card admin-surface admin-map-card">
                <header class="admin-card-header">
                    <div>
                        <h2>Usuarios por pais</h2>
                        <p>Distribucion geolocalizada de cuentas activas.</p>
                    </div>
                    <span class="admin-chip">Este mes</span>
                </header>

                <div class="admin-map-layout">
                    <div class="admin-world-map" aria-hidden="true">
                        <span style="--x: 19%; --y: 45%; --size: 14px;"></span>
                        <span style="--x: 46%; --y: 38%; --size: 10px;"></span>
                        <span style="--x: 58%; --y: 55%; --size: 12px;"></span>
                        <span style="--x: 67%; --y: 34%; --size: 9px;"></span>
                        <span style="--x: 32%; --y: 59%; --size: 8px;"></span>
                    </div>

                    <ul class="admin-country-list">
                        @foreach ($countries as $country)
                            <li>
                                <div class="admin-country-copy">
                                    <span class="admin-flag">{{ $country['code'] }}</span>
                                    <span>{{ $country['name'] }}</span>
                                </div>
                                <strong>{{ number_format($country['users']) }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </article>
        </div>

        <div class="admin-grid admin-grid-bottom">
            <article class="surface-card admin-surface admin-list-card">
                <header class="admin-card-header">
                    <div>
                        <h2>Proyectos recientes</h2>
                        <p>Actividad de productos y plataformas clave.</p>
                    </div>
                    <a class="btn btn-secondary" href="{{ route('portal.module', ['portal' => 'admin', 'module' => 'projects']) }}">Ver todos</a>
                </header>

                <ul class="admin-project-list">
                    @foreach ($projects as $project)
                        <li>
                            <span class="admin-project-icon" style="--tone: {{ $project['tone'] }}">{{ $project['icon'] }}</span>
                            <div class="admin-project-copy">
                                <strong>{{ $project['name'] }}</strong>
                                <p>{{ $project['meta'] }}</p>
                            </div>
                            <span class="admin-state-badge {{ $project['stateClass'] }}">{{ $project['state'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </article>

            <article class="surface-card admin-surface admin-list-card">
                <header class="admin-card-header">
                    <div>
                        <h2>Actividades recientes</h2>
                        <p>Eventos relevantes del equipo en el ultimo ciclo.</p>
                    </div>
                    <a class="btn btn-secondary" href="{{ route('portal.module', ['portal' => 'admin', 'module' => 'activity-log']) }}">Ver todas</a>
                </header>

                <ul class="admin-activity-list">
                    @foreach ($activities as $activity)
                        <li>
                            <span class="admin-avatar">{{ $activity['initials'] }}</span>
                            <div class="admin-activity-copy">
                                <strong>{{ $activity['title'] }}</strong>
                                <p>{{ $activity['context'] }}</p>
                            </div>
                            <time>{{ $activity['time'] }}</time>
                        </li>
                    @endforeach
                </ul>
            </article>

            <article class="surface-card admin-surface admin-revenue-card">
                <header class="admin-card-header">
                    <div>
                        <h2>Distribucion de ingresos</h2>
                        <p>Resumen financiero por unidad de negocio.</p>
                    </div>
                </header>

                <div class="admin-revenue-layout">
                    <div class="admin-ring" style="--segments: {{ $ringGradient }};">
                        <div class="admin-ring-center">
                            <strong>${{ number_format($revenueTotal, 0) }}</strong>
                            <span>Total</span>
                        </div>
                    </div>

                    <ul class="admin-revenue-list">
                        @foreach ($revenueMix as $segment)
                            <li>
                                <div class="admin-revenue-copy">
                                    <i style="background: {{ $segment['color'] }}"></i>
                                    <span>{{ $segment['label'] }}</span>
                                </div>
                                <div class="admin-revenue-amount">
                                    <strong>${{ number_format($segment['amount']) }}</strong>
                                    <small>{{ number_format(($segment['amount'] / $revenueTotal) * 100, 1) }}%</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </article>
        </div>
    </section>
@endsection
