@props([
    'title' => 'Actividad',
    'rows' => [],
])

<section class="surface-card">
    <header class="surface-header">
        <h2>{{ $title }}</h2>
    </header>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Estado</th>
                    <th>Responsable</th>
                    <th>Actualizado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row['ref'] }}</td>
                        <td>{{ $row['status'] }}</td>
                        <td>{{ $row['owner'] }}</td>
                        <td>{{ $row['updated'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

