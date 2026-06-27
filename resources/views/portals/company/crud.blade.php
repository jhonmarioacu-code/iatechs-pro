@extends('layouts.company')

@section('portal-content')
    <section
        class="surface-card"
        data-crud-root
        data-module="{{ $module }}"
        data-mode="{{ $mode }}"
        @if ($recordId !== null)
            data-record-id="{{ $recordId }}"
        @endif
        data-index-url="{{ route('portal.company.module.index', ['module' => $module]) }}"
        data-create-url="{{ route('portal.company.module.create', ['module' => $module]) }}"
    >
        <header class="surface-header">
            <h2 class="crud-title">
                {{ strtoupper($module) }} | {{ strtoupper($mode) }}
            </h2>
            <div class="crud-actions">
                <a class="btn btn-secondary" href="{{ route('portal.company.module.index', ['module' => $module]) }}">
                    Listado
                </a>
                <a class="btn btn-primary" href="{{ route('portal.company.module.create', ['module' => $module]) }}">
                    Crear
                </a>
            </div>
        </header>

        <p class="module-copy">
            Modulo conectado a endpoints API: <strong>/api/v1/{{ $module }}</strong>
        </p>

        <div class="crud-feedback" data-crud-feedback></div>

        <div data-crud-content></div>
    </section>
@endsection

