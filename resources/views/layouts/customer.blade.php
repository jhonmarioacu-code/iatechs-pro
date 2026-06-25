@extends('layouts.base')

@section('title', 'IAtechs Pro | Customer Portal')

@section('body')
    <x-portal-shell
        portal="customer"
        :title="$portalTitle ?? 'Customer Portal'"
        :subtitle="$portalSubtitle ?? ''"
        :kpis="$kpis ?? []"
    >
        @yield('portal-content')
    </x-portal-shell>
@endsection

