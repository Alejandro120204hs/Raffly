@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/ingresos.css') }}">
@endpush

@section('title', 'Ingresos')
@section('page-title', 'Ingresos')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Resumen de Ingresos</h2>
        <p class="page-header-sub">Dinero recaudado, premios pagados y ganancia neta</p>
    </div>
</div>

{{-- KPIs --}}
<div class="ingresos-kpis">

    <div class="ingreso-kpi ingreso-kpi--green">
        <div class="ingreso-kpi-icon"><i class="fas fa-check-circle"></i></div>
        <div class="ingreso-kpi-body">
            <span class="ingreso-kpi-val">${{ number_format($totalRecaudado, 0, ',', '.') }}</span>
            <span class="ingreso-kpi-label">Total recaudado</span>
            <span class="ingreso-kpi-sub">Boletas vendidas y pagadas</span>
        </div>
    </div>

    <div class="ingreso-kpi ingreso-kpi--rose">
        <div class="ingreso-kpi-icon"><i class="fas fa-trophy"></i></div>
        <div class="ingreso-kpi-body">
            <span class="ingreso-kpi-val">${{ number_format($totalPremiosPagados, 0, ',', '.') }}</span>
            <span class="ingreso-kpi-label">Premios pagados</span>
            <span class="ingreso-kpi-sub">Rifas finalizadas con ganador</span>
        </div>
    </div>

    <div class="ingreso-kpi ingreso-kpi--teal">
        <div class="ingreso-kpi-icon"><i class="fas fa-coins"></i></div>
        <div class="ingreso-kpi-body">
            <span class="ingreso-kpi-val">${{ number_format($ganancia, 0, ',', '.') }}</span>
            <span class="ingreso-kpi-label">Ganancia neta</span>
            <span class="ingreso-kpi-sub">Recaudado menos premios entregados</span>
        </div>
    </div>

    <div class="ingreso-kpi ingreso-kpi--amber">
        <div class="ingreso-kpi-icon"><i class="fas fa-clock"></i></div>
        <div class="ingreso-kpi-body">
            <span class="ingreso-kpi-val">${{ number_format($totalPendiente, 0, ',', '.') }}</span>
            <span class="ingreso-kpi-label">Pendiente por cobrar</span>
            <span class="ingreso-kpi-sub">Boletas apartadas sin pagar</span>
        </div>
    </div>

    <div class="ingreso-kpi ingreso-kpi--blue">
        <div class="ingreso-kpi-icon"><i class="fas fa-chart-line"></i></div>
        <div class="ingreso-kpi-body">
            <span class="ingreso-kpi-val">${{ number_format($totalPotencial, 0, ',', '.') }}</span>
            <span class="ingreso-kpi-label">Potencial (activas)</span>
            <span class="ingreso-kpi-sub">Si se venden todos los números</span>
        </div>
    </div>

</div>

{{-- Gráfico por mes --}}
@if($porMes->count() > 0)
<div class="panel ingresos-chart-panel">
    <div class="panel-header-row">
        <h3 class="panel-section-title"><i class="fas fa-chart-bar"></i> Ingresos por mes</h3>
        <span class="panel-section-sub">Últimos {{ $porMes->count() }} meses registrados</span>
    </div>

    @php $maxMes = $porMes->max('total') ?: 1; @endphp

    <div class="mes-chart-wrap">
        <div class="mes-chart">
            @foreach($porMes as $mes)
            @php $pct = max(2, round(($mes['total'] / $maxMes) * 100)); @endphp
            <div class="mes-col">
                <span class="mes-val">${{ number_format($mes['total'], 0, ',', '.') }}</span>
                <div class="mes-bar-wrap">
                    <div class="mes-bar" style="height: {{ $pct }}%"></div>
                </div>
                <span class="mes-label">{{ $mes['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Tabla desglose por rifa --}}
<div class="panel">
    <div class="panel-header-row">
        <h3 class="panel-section-title"><i class="fas fa-list"></i> Desglose por rifa</h3>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rifa</th>
                    <th>Estado</th>
                    <th>Precio / №</th>
                    <th>Vendidos</th>
                    <th>Recaudado</th>
                    <th>Premio pagado</th>
                    <th>Ganancia</th>
                    <th>Por cobrar</th>
                    <th>Potencial</th>
                    <th>Avance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rifas as $r)
                <tr class="table-row">
                    {{-- Rifa --}}
                    <td>
                        <a href="{{ route('admin.rifas.show', $r['id']) }}" class="rifa-link">
                            <div class="rifa-avatar"><i class="fas fa-ticket-alt"></i></div>
                            <span class="rifa-name">{{ $r['nombre'] }}</span>
                        </a>
                    </td>

                    {{-- Estado --}}
                    <td>
                        @if($r['estado'] === 'activa')
                            <span class="status-badge status-activa"><span class="status-dot"></span> Activa</span>
                        @else
                            <span class="status-badge status-finalizada"><span class="status-dot"></span> Finalizada</span>
                        @endif
                    </td>

                    {{-- Precio --}}
                    <td><span class="td-precio">${{ number_format($r['precio'], 0, ',', '.') }}</span></td>

                    {{-- Vendidos --}}
                    <td>
                        <span class="td-vendidos">{{ $r['vendidos'] }}</span>
                        <span class="td-total-small"> / {{ number_format($r['total'], 0, ',', '.') }}</span>
                    </td>

                    {{-- Recaudado --}}
                    <td><span class="td-recaudado">${{ number_format($r['recaudado'], 0, ',', '.') }}</span></td>

                    {{-- Premio pagado --}}
                    <td>
                        @if($r['premio_pago'] > 0)
                            <span class="td-premio-pago">${{ number_format($r['premio_pago'], 0, ',', '.') }}</span>
                        @else
                            <span class="td-empty">—</span>
                        @endif
                    </td>

                    {{-- Ganancia --}}
                    <td>
                        <span class="{{ $r['ganancia'] >= 0 ? 'td-ganancia-pos' : 'td-ganancia-neg' }}">
                            ${{ number_format($r['ganancia'], 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- Por cobrar --}}
                    <td>
                        @if($r['por_cobrar'] > 0)
                            <span class="td-porcobrar">${{ number_format($r['por_cobrar'], 0, ',', '.') }}</span>
                        @else
                            <span class="td-empty">—</span>
                        @endif
                    </td>

                    {{-- Potencial --}}
                    <td><span class="td-potencial">${{ number_format($r['potencial'], 0, ',', '.') }}</span></td>

                    {{-- Avance --}}
                    <td>
                        <div class="td-avance">
                            <div class="avance-bar">
                                <div class="avance-fill" style="width: {{ $r['pct'] }}%"></div>
                            </div>
                            <span class="avance-pct">{{ $r['pct'] }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="td-empty-state">
                        <i class="fas fa-chart-bar"></i>
                        <span>No hay rifas registradas aún</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/ingresos-index.js') }}"></script>
@endpush
