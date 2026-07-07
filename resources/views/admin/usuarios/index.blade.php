@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/usuarios.css') }}">
@endpush

@section('title', 'Usuarios')
@section('page-title', 'Usuarios')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Clientes registrados</h2>
        <p class="page-header-sub">{{ count($usuarios) }} usuarios en la plataforma</p>
    </div>
</div>

{{-- Búsqueda + filtros --}}
<div class="usuarios-toolbar">
    <div class="toolbar-search">
        <i class="fas fa-search toolbar-search-icon"></i>
        <input type="text" id="usuariosSearch" class="toolbar-search-input" placeholder="Buscar por nombre, correo o celular...">
    </div>

    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="todos">
            Todos <span class="filter-count">{{ count($usuarios) }}</span>
        </button>
        <button class="filter-tab" data-filter="compras">
            Con compras <span class="filter-count">{{ count($usuarios->filter(fn($u) => $u['vendidas'] > 0)) }}</span>
        </button>
        <button class="filter-tab" data-filter="sin">
            Sin compras <span class="filter-count">{{ count($usuarios->filter(fn($u) => $u['vendidas'] === 0 && $u['pendientes'] === 0)) }}</span>
        </button>
        <button class="filter-tab" data-filter="ganadores">
            Ganadores <span class="filter-count">{{ count($usuarios->filter(fn($u) => $u['gano'])) }}</span>
        </button>
    </div>
</div>

{{-- Tabla --}}
<div class="panel">
    <div class="table-wrap">
        <table class="data-table" id="usuariosTable">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Ubicación</th>
                    <th class="th-center">Boletas</th>
                    <th class="th-center">Por pagar</th>
                    <th>Total gastado</th>
                    <th class="th-center">Ganador</th>
                    <th>Miembro desde</th>
                </tr>
            </thead>
            <tbody id="usuariosBody">
                @forelse($usuarios as $u)
                <tr class="table-row usuario-row"
                    data-filter="{{ $u['vendidas'] > 0 ? 'compras' : ($u['pendientes'] > 0 ? 'compras' : 'sin') }}"
                    data-gano="{{ $u['gano'] ? 'si' : 'no' }}"
                    data-search="{{ strtolower($u['name'] . ' ' . $u['email'] . ' ' . $u['celular']) }}">

                    {{-- Cliente --}}
                    <td>
                        <div class="usuario-cell">
                            <div class="usuario-avatar">
                                {{ strtoupper(substr($u['name'], 0, 1)) }}
                            </div>
                            <div class="usuario-meta">
                                <span class="usuario-name">{{ $u['name'] }}</span>
                                <span class="usuario-email">{{ $u['email'] }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Contacto --}}
                    <td>
                        @if($u['celular'])
                            <span class="td-celular"><i class="fas fa-phone"></i> {{ $u['celular'] }}</span>
                        @else
                            <span class="td-empty">—</span>
                        @endif
                    </td>

                    {{-- Ubicación --}}
                    <td>
                        @if($u['municipio'] || $u['departamento'])
                            <span class="td-ubicacion">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $u['municipio'] }}@if($u['municipio'] && $u['departamento']), @endif{{ $u['departamento'] }}
                            </span>
                        @else
                            <span class="td-empty">—</span>
                        @endif
                    </td>

                    {{-- Boletas vendidas --}}
                    <td class="td-center">
                        @if($u['vendidas'] > 0)
                            <span class="badge-boletas">{{ $u['vendidas'] }}</span>
                        @else
                            <span class="td-empty">0</span>
                        @endif
                    </td>

                    {{-- Pendientes --}}
                    <td class="td-center">
                        @if($u['pendientes'] > 0)
                            <span class="badge-pendientes">{{ $u['pendientes'] }}</span>
                        @else
                            <span class="td-empty">0</span>
                        @endif
                    </td>

                    {{-- Total gastado --}}
                    <td>
                        @if($u['total_gastado'] > 0)
                            <span class="td-gastado">${{ number_format($u['total_gastado'], 0, ',', '.') }}</span>
                        @else
                            <span class="td-empty">$0</span>
                        @endif
                    </td>

                    {{-- Ganador --}}
                    <td class="td-center">
                        @if($u['gano'])
                            <span class="badge-ganador"><i class="fas fa-trophy"></i> Sí</span>
                        @else
                            <span class="td-empty">—</span>
                        @endif
                    </td>

                    {{-- Miembro desde --}}
                    <td>
                        <span class="td-fecha">{{ $u['miembro_desde'] }}</span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="td-empty-state">
                        <i class="fas fa-users"></i>
                        <span>No hay clientes registrados aún</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span class="table-info" id="usuariosInfo"></span>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/usuarios-index.js') }}"></script>
@endpush
