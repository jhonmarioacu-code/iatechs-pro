@extends('layouts.base')

@section('title', 'IAtechs Pro | Admin Portal')

@section('body')
    <x-portal-shell
        portal="admin"
        :title="$portalTitle ?? 'Admin Portal'"
        :subtitle="$portalSubtitle ?? ''"
        :kpis="$kpis ?? []"
    >
        @yield('portal-content')
    </x-portal-shell>
@endsection

