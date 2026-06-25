@extends('layouts.admin')

@section('portal-content')
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
                <li>Multi-tenant activo por `company_id`.</li>
                <li>Permisos centralizados con Spatie.</li>
                <li>Portal responsive para desktop/tablet/mobile.</li>
                <li>IA Assistant disponible desde topbar y widget flotante.</li>
            </ul>
        </section>
    </div>
@endsection

