@extends('layouts.base')

@section('title', 'IAtechs Pro | Company Portal')

@section('body')
    <x-portal-shell
        portal="company"
        :title="$portalTitle ?? 'Company Portal'"
        :subtitle="$portalSubtitle ?? ''"
        :kpis="$kpis ?? []"
    >
        @yield('portal-content')
    </x-portal-shell>
@endsection

