@extends('layouts.admin')

@section('portal-content')
    <section class="surface-card" x-data="{ tab: 'ops' }">
        <header class="surface-header">
            <h2>Centro de control global</h2>
            <div class="crud-actions">
                <button class="btn btn-secondary" type="button" @click="tab = 'ops'">Operacion</button>
                <button class="btn btn-secondary" type="button" @click="tab = 'risk'">Riesgos</button>
                <button class="btn btn-secondary" type="button" @click="tab = 'growth'">Crecimiento</button>
            </div>
        </header>

        <div x-show="tab === 'ops'" class="surface-list">
            <p>Estado de tenants, suscripciones y operaciones criticas en tiempo real.</p>
            <div class="tag-grid">
                <span>Tenants activos: 148</span>
                <span>Despliegues hoy: 3</span>
                <span>Workers cola: estables</span>
                <span>Errores criticos: 0</span>
            </div>
        </div>

        <div x-show="tab === 'risk'" class="surface-list">
            <p>Vista consolidada de seguridad, auditoria y salud de permisos.</p>
            <div class="tag-grid">
                <span>Intentos bloqueados: 12</span>
                <span>Politicas activas: 100%</span>
                <span>Rutas admin protegidas: 100%</span>
                <span>Eventos auditables: habilitados</span>
            </div>
        </div>

        <div x-show="tab === 'growth'" class="surface-list">
            <p>Indicadores ejecutivos para expansion comercial del SaaS.</p>
            <div class="tag-grid">
                <span>Nuevas empresas: 9</span>
                <span>Renovacion mensual: 96%</span>
                <span>ARR estimado: +14%</span>
                <span>Activacion IA: 63%</span>
            </div>
        </div>
    </section>

    <div class="surface-grid">
        <x-module-table
            title="Actividad Reciente Global"
            :rows="[
                ['ref' => 'CMP-0842', 'status' => 'Nueva empresa', 'owner' => 'Super Admin', 'updated' => 'Hace 5 min'],
                ['ref' => 'SUB-1129', 'status' => 'Renovada', 'owner' => 'Billing Bot', 'updated' => 'Hace 14 min'],
                ['ref' => 'REP-0194', 'status' => 'Generado', 'owner' => 'Analytics', 'updated' => 'Hace 21 min'],
            ]"
        />

        <section class="surface-card">
            <header class="surface-header">
                <h2>Estado Operativo</h2>
            </header>
            <ul class="surface-list">
                <li>Multi-tenant activo por company_id.</li>
                <li>Permisos centralizados con Spatie.</li>
                <li>Portal responsive para desktop, tablet y mobile.</li>
                <li>IA Assistant disponible desde topbar y widget flotante.</li>
            </ul>
        </section>
    </div>
@endsection
