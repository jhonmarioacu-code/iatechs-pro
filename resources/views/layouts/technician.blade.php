@extends('layouts.base')

@section('title', 'IAtechs Pro | Technician Portal')

@section('body')
    <x-portal-shell
        portal="technician"
        :title="$portalTitle ?? 'Technician Portal'"
        :subtitle="$portalSubtitle ?? ''"
        :kpis="$kpis ?? []"
    >
        @yield('portal-content')
    </x-portal-shell>
@endsection

