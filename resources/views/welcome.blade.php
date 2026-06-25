<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IAtechs Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950">
    <main class="mx-auto flex min-h-screen w-full max-w-7xl flex-col gap-6 px-4 py-5 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 border-b border-zinc-300 pb-5 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase text-emerald-700">IAtechs Pro</p>
                <h1 class="mt-1 text-3xl font-bold tracking-normal text-zinc-950 sm:text-4xl">Centro de control</h1>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-sm">
                <span class="status-pill border-emerald-300 bg-emerald-50 text-emerald-800">Testing aislado</span>
                <span class="status-pill border-sky-300 bg-sky-50 text-sky-800">API v1</span>
                <span class="status-pill border-amber-300 bg-amber-50 text-amber-800">Produccion lista</span>
            </div>
        </header>

        <section class="grid gap-4 lg:grid-cols-[1.35fr_0.65fr]">
            <div class="panel">
                <div class="flex flex-col gap-3 border-b border-zinc-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="section-title">Estado operativo</h2>
                        <p class="section-subtitle">Validacion del backend y endpoints publicos.</p>
                    </div>
                    <button class="action-button" data-health-refresh type="button">Actualizar estado</button>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-3">
                    <article class="metric">
                        <span class="metric-label">Aplicacion</span>
                        <strong class="metric-value" data-health-field="app">Pendiente</strong>
                    </article>
                    <article class="metric">
                        <span class="metric-label">API</span>
                        <strong class="metric-value" data-health-field="api">Pendiente</strong>
                    </article>
                    <article class="metric">
                        <span class="metric-label">Build</span>
                        <strong class="metric-value">Vite</strong>
                    </article>
                </div>

                <div class="mt-4 overflow-hidden rounded border border-zinc-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-950 text-white">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Capa</th>
                                <th class="px-4 py-3 font-semibold">Estado</th>
                                <th class="px-4 py-3 font-semibold">Ruta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white">
                            <tr>
                                <td class="px-4 py-3 font-medium">Health web</td>
                                <td class="px-4 py-3" data-health-row="web">Sin verificar</td>
                                <td class="px-4 py-3 font-mono text-xs">/health</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Health API</td>
                                <td class="px-4 py-3" data-health-row="api">Sin verificar</td>
                                <td class="px-4 py-3 font-mono text-xs">/api/health</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Modulo API</td>
                                <td class="px-4 py-3">Protegido por Sanctum y tenant</td>
                                <td class="px-4 py-3 font-mono text-xs">/api/v1/*</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="panel">
                <h2 class="section-title">Compuerta actual</h2>
                <div class="mt-4 space-y-3">
                    <div class="check-row">
                        <span>Migraciones</span>
                        <strong>OK</strong>
                    </div>
                    <div class="check-row">
                        <span>Rutas cacheables</span>
                        <strong>OK</strong>
                    </div>
                    <div class="check-row">
                        <span>Tests arquitectura</span>
                        <strong>OK</strong>
                    </div>
                    <div class="check-row">
                        <span>Frontend build</span>
                        <strong data-build-status>Por validar</strong>
                    </div>
                </div>
            </aside>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Core SaaS', 'Empresas, planes, suscripciones, facturacion'],
                ['Servicios', 'Tickets, diagnosticos, reparaciones, ordenes'],
                ['ERP', 'Inventario, compras, pagos, contabilidad'],
                ['Enterprise', 'Documentos, cumplimiento, BI, activos'],
            ] as [$title, $description])
                <article class="module-tile">
                    <h3>{{ $title }}</h3>
                    <p>{{ $description }}</p>
                </article>
            @endforeach
        </section>
    </main>
</body>
</html>
