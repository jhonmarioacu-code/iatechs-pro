@extends('layouts.customer')

@section('portal-content')
    <section class="surface-card">
        <header class="surface-header">
            <h2>Marketplace | Productos</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.dashboard') }}">Volver al dashboard</a>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Producto</th>
                        <th>Categoria</th>
                        <th>Precio venta</th>
                        <th>Disponibilidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->stock > 0 ? 'Disponible' : 'Sin stock' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay productos activos por el momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Marketplace | Servicios</h2>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Codigo</th>
                        <th>Estado</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->code ?? 'N/A' }}</td>
                            <td>{{ $service->status }}</td>
                            <td>{{ optional($service->starts_at)->toDateString() ?? 'N/A' }}</td>
                            <td>{{ optional($service->ends_at)->toDateString() ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay servicios publicados por el momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
