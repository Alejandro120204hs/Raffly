@extends('admin.layouts.app')

@section('title', 'Rifas')
@section('page-title', 'Rifas')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Gestión de Rifas</h2>
        <p class="page-header-sub">Administra todas las rifas de la plataforma</p>
    </div>
    <a href="#" class="btn-primary">
        <i class="fas fa-plus"></i> Crear Rifa
    </a>
</div>

{{-- Filtros --}}
<div class="filter-tabs">
    <button class="filter-tab active" data-filter="todas">
        Todas <span class="filter-count">{{ count($rifas) }}</span>
    </button>
    <button class="filter-tab" data-filter="activa">
        Activas <span class="filter-count">{{ count(array_filter($rifas, fn($r) => $r['estado'] === 'activa')) }}</span>
    </button>
    <button class="filter-tab" data-filter="finalizada">
        Finalizadas <span class="filter-count">{{ count(array_filter($rifas, fn($r) => $r['estado'] === 'finalizada')) }}</span>
    </button>
</div>

{{-- Tabla --}}
<div class="panel">
    <div class="table-wrap">
        <table class="data-table" id="rifasTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rifa</th>
                    <th>Precio / Número</th>
                    <th>Progreso</th>
                    <th>Fecha Sorteo</th>
                    <th>Premio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="rifasBody">
                @foreach($rifas as $rifa)
                @php $pct = round(($rifa['vendidos'] / $rifa['total']) * 100); @endphp
                <tr class="table-row" data-estado="{{ $rifa['estado'] }}">
                    <td class="td-id">#{{ $rifa['id'] }}</td>
                    <td class="td-nombre">
                        <div class="rifa-avatar">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="rifa-meta">
                            <span class="rifa-name">{{ $rifa['nombre'] }}</span>
                            <span class="rifa-nums-small">{{ $rifa['vendidos'] }}/{{ $rifa['total'] }} números</span>
                        </div>
                    </td>
                    <td class="td-precio">
                        ${{ number_format($rifa['precio'], 0, ',', '.') }}
                    </td>
                    <td class="td-progreso">
                        <div class="mini-bar">
                            <div class="mini-fill" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="mini-pct">{{ $pct }}%</span>
                    </td>
                    <td class="td-fecha">
                        <i class="fas fa-calendar"></i> {{ $rifa['fecha'] }}
                    </td>
                    <td class="td-premio">
                        @if($rifa['tipo'] === 'efectivo')
                            <span class="premio-efectivo"><i class="fas fa-money-bill-wave"></i> {{ $rifa['premio'] }}</span>
                        @else
                            <span class="premio-objeto"><i class="fas fa-gift"></i> {{ $rifa['premio'] }}</span>
                        @endif
                    </td>
                    <td class="td-estado">
                        @if($rifa['estado'] === 'activa')
                            <span class="status-badge status-activa">
                                <span class="status-dot"></span> Activa
                            </span>
                        @else
                            <span class="status-badge status-finalizada">
                                <span class="status-dot"></span> Finalizada
                            </span>
                        @endif
                    </td>
                    <td class="td-acciones">
                        <div class="action-btns">
                            <a href="{{ route('admin.rifas.show', $rifa['id']) }}" class="action-btn action-btn--view" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="action-btn action-btn--edit" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="action-btn action-btn--delete" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="table-footer">
        <span class="table-info" id="tableInfo"></span>
        <div class="pagination" id="pagination"></div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/rifas.js') }}"></script>
@endpush
