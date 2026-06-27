@extends('layouts.admin')

@section('portal-content')
    <div class="surface-grid">
        <section class="surface-card">
            <header class="surface-header">
                <h2>SLO/SLA Pagos y Suscripciones</h2>
            </header>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Metrica</th>
                            <th>Valor</th>
                            <th>Objetivo</th>
                            <th>Severidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($revenueObservability['alerts'] ?? []) as $alert)
                            <tr>
                                <td>{{ $alert['name'] }}</td>
                                <td>{{ $alert['value'] }}</td>
                                <td>{{ $alert['target'] }}</td>
                                <td>{{ $alert['severity'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="surface-card">
            <header class="surface-header">
                <h2>Revenue Snapshot</h2>
            </header>
            <ul class="surface-list">
                <li>Payments completed 24h: <strong>{{ $revenueObservability['payments']['completed_24h'] ?? 0 }}</strong></li>
                <li>Payments failed 24h: <strong>{{ $revenueObservability['payments']['failed_24h'] ?? 0 }}</strong></li>
                <li>Payments pending online stale: <strong>{{ $revenueObservability['payments']['pending_online_stale'] ?? 0 }}</strong></li>
                <li>Subscriptions active: <strong>{{ $revenueObservability['subscriptions']['active'] ?? 0 }}</strong></li>
                <li>Subscriptions past_due: <strong>{{ $revenueObservability['subscriptions']['past_due'] ?? 0 }}</strong></li>
                <li>Churn 30d: <strong>{{ $revenueObservability['subscriptions']['churn_30d'] ?? 0 }}%</strong></li>
                <li>MRR: <strong>$ {{ number_format((float) ($revenueObservability['revenue']['mrr'] ?? 0), 2, '.', ',') }}</strong></li>
                <li>ARR: <strong>$ {{ number_format((float) ($revenueObservability['revenue']['arr'] ?? 0), 2, '.', ',') }}</strong></li>
                <li>Overall status: <strong>{{ $revenueObservability['overall_status'] ?? 'OK' }}</strong></li>
                <li>Generated at: <strong>{{ $revenueObservability['generated_at'] ?? 'N/A' }}</strong></li>
            </ul>
        </section>
    </div>

    <div class="surface-grid">
        <section class="surface-card">
            <header class="surface-header">
                <h2>Estado de Servicios</h2>
            </header>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $name => $service)
                            <tr>
                                <td>{{ strtoupper($name) }}</td>
                                <td>{{ $service['ok'] ? 'OK' : 'ERROR' }}</td>
                                <td>{{ $service['message'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>HORIZON</td>
                            <td>{{ $horizon['ok'] ? 'RUNNING' : 'DOWN' }}</td>
                            <td>{{ $horizon['message'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="surface-card">
            <header class="surface-header">
                <h2>Metricas HTTP (24h cache)</h2>
            </header>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statusBuckets as $bucket)
                            <tr>
                                <td>{{ $bucket['code'] }}</td>
                                <td>{{ $bucket['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="surface-grid">
        <section class="surface-card">
            <header class="surface-header">
                <h2>Colas</h2>
            </header>
            <ul class="surface-list">
                <li>Conexion default: <strong>{{ $queueDetails['default_connection'] }}</strong></li>
                <li>Queue Redis: <strong>{{ $queueDetails['redis_queue'] }}</strong></li>
                <li>Pendientes: <strong>{{ $queueDetails['pending_jobs'] }}</strong></li>
                <li>Fallidos: <strong>{{ $queueDetails['failed_jobs'] }}</strong></li>
            </ul>
        </section>

        <section class="surface-card">
            <header class="surface-header">
                <h2>Logs y Trazabilidad</h2>
            </header>
            <ul class="surface-list">
                <li>Archivo: <code>{{ $logDetails['path'] }}</code></li>
                <li>Existe: <strong>{{ $logDetails['exists'] ? 'SI' : 'NO' }}</strong></li>
                <li>Tamano: <strong>{{ $logDetails['size_kb'] }} KB</strong></li>
                <li>Ultima modificacion: <strong>{{ $logDetails['last_modified_at'] ?? 'N/A' }}</strong></li>
                <li>Ultimo request observado: <strong>{{ $logDetails['last_seen_at'] ?? 'N/A' }}</strong></li>
            </ul>
        </section>
    </div>
@endsection
