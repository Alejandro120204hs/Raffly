@extends('cliente.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/cliente/mis-numeros.css') }}">
@endpush

@section('title', 'Mis Números')
@section('page-title', 'Historial')

@section('content')

<div class="mn-top-bar">
    <p class="mn-info-text">
        <i class="fas fa-info-circle"></i>
        Aquí aparecen todos los números que tienes en cada rifa. Los verdes ya están confirmados; los amarillos están apartados y esperan confirmación de pago.
    </p>
    <div class="mn-top-bar-right">
        <div class="mn-view-toggle">
            <button class="mn-view-btn active" id="btnViewCards" title="Vista de tarjetas">
                <i class="fas fa-th-large"></i>
            </button>
            <button class="mn-view-btn" id="btnViewTable" title="Vista de tabla">
                <i class="fas fa-list"></i>
            </button>
        </div>
      
    </div>
</div>

{{-- Alerta global si hay boletas pendientes ─────── --}}
@php $totalPendientes = $rifas->sum('pendientes'); @endphp
@if($totalPendientes > 0)
<div class="mn-alert-pend">
    <div class="mn-alert-ico"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="mn-alert-body">
        <strong>Tienes {{ $totalPendientes }} {{ $totalPendientes === 1 ? 'boleta' : 'boletas' }} pendientes de pago</strong>
        <span>Comunícate con el organizador para confirmar tu pago y asegurar tus números.</span>
    </div>
</div>
@endif

{{-- Tabs de filtro ──────────────────────────────── --}}
@php
    $totalGanadas     = $rifas->where('gane', true)->count();
    $totalActivas     = $rifas->where('estado', 'activa')->count();
    $totalFinalizadas = $rifas->where('estado', 'finalizada')->where('gane', false)->count();
@endphp
<div class="mn-tabs" id="mnTabs">
    <button class="mn-tab active" data-filter="all">
        Todas <span class="mn-tab-count">{{ $rifas->count() }}</span>
    </button>
    <button class="mn-tab" data-filter="activa">
        Activas <span class="mn-tab-count">{{ $totalActivas }}</span>
    </button>
    <button class="mn-tab" data-filter="ganada">
        <i class="fas fa-trophy"></i> Ganadas <span class="mn-tab-count mn-tab-count--win">{{ $totalGanadas }}</span>
    </button>
    <button class="mn-tab" data-filter="finalizada">
        Finalizadas <span class="mn-tab-count">{{ $totalFinalizadas }}</span>
    </button>
</div>

@if($rifas->isEmpty())
<div class="mn-empty-full panel">
    <div class="mn-empty-icon"><i class="fas fa-ticket-alt"></i></div>
    <h3>Aún no tienes números</h3>
    <p>Cuando el administrador te asigne un número aparecerá aquí.</p>
    <a href="{{ route('cliente.rifas') }}" class="btn-primary">Explorar rifas</a>
</div>
@else

{{-- ══════════════════════════════════════════
     VISTA TARJETAS
════════════════════════════════════════════ --}}
<div id="mnViewCards">
    @foreach($rifas as $r)
    <div class="mn-rifa {{ $r['gane'] ? 'mn-rifa--ganador' : '' }} {{ $r['estado'] === 'finalizada' && !$r['gane'] ? 'mn-rifa--finalizada' : '' }}"
         data-estado="{{ $r['estado'] }}"
         data-gane="{{ $r['gane'] ? 'true' : 'false' }}">

        <div class="mn-rifa-stripe"></div>

        <div class="mn-rifa-header">
            <div class="mn-rifa-header-left">
                <div class="mn-rifa-ico">
                    @if($r['gane'])
                        <i class="fas fa-trophy"></i>
                    @elseif($r['estado'] === 'finalizada')
                        <i class="fas fa-flag-checkered"></i>
                    @else
                        <i class="fas fa-ticket-alt"></i>
                    @endif
                </div>
                <div>
                    <h3 class="mn-rifa-nombre">{{ $r['nombre'] }}</h3>
                    <div class="mn-rifa-meta-row">
                        <span class="mn-rifa-meta-item"><i class="fas fa-dice"></i> {{ $r['loteria'] }}</span>
                        <span class="mn-rifa-meta-sep">·</span>
                        <span class="mn-rifa-meta-item"><i class="fas fa-calendar-alt"></i> {{ $r['fecha'] }}</span>
                    </div>
                </div>
            </div>
            <div class="mn-rifa-header-right">
                @if($r['gane'])
                    <span class="mn-badge mn-badge--win"><i class="fas fa-trophy"></i> ¡Ganaste!</span>
                @elseif($r['estado'] === 'activa')
                    <span class="mn-badge mn-badge--activa"><span class="mn-badge-dot"></span> Activa</span>
                @else
                    <span class="mn-badge mn-badge--final"><span class="mn-badge-dot"></span> Finalizada</span>
                @endif
            </div>
        </div>

        @if($r['gane'])
        <div class="mn-win-banner">
            <div class="mn-win-trophy">🏆</div>
            <div class="mn-win-text">
                <span class="mn-win-title">¡Felicidades, ganaste esta rifa!</span>
                <span class="mn-win-sub">Tu número <strong>{{ $r['resultado'] }}</strong> fue el ganador · Premio: <strong>{{ $r['premio'] }}</strong></span>
            </div>
        </div>
        @elseif($r['resultado'] && $r['estado'] === 'finalizada')
        <div class="mn-fin-banner">
            <i class="fas fa-flag-checkered"></i>
            <span>Rifa finalizada · Número ganador: <strong>{{ $r['resultado'] }}</strong></span>
        </div>
        @endif

        <div class="mn-numeros-section">
            <div class="mn-numeros-head">
                <span class="mn-numeros-titulo">Mis números</span>
                @if($r['pendientes'] > 0)
                <span class="mn-pend-tag">
                    <i class="fas fa-clock"></i>
                    {{ $r['pendientes'] }} {{ $r['pendientes'] === 1 ? 'boleta pendiente' : 'boletas pendientes' }}
                </span>
                @endif
            </div>
            <div class="mn-numeros">
                @foreach($r['numeros'] as $n)
                @php
                    $esGanador = $r['resultado'] === $n->numero;
                    $clase = $esGanador ? 'mn-num--win' : 'mn-num--' . $n->estado;
                @endphp
                <div class="mn-num {{ $clase }}">
                    <span class="mn-num-val">{{ $n->numero }}</span>
                    @if($esGanador)
                        <span class="mn-num-tag"><i class="fas fa-star"></i></span>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mn-ley-mini">
                <span class="mn-ley-item mn-ley-item--pagado"><span class="mn-ley-dot"></span> Pagado</span>
                <span class="mn-ley-item mn-ley-item--pendiente"><span class="mn-ley-dot"></span> Pendiente</span>
                @if($r['gane'])<span class="mn-ley-item mn-ley-item--ganador"><span class="mn-ley-dot"></span> Ganador</span>@endif
            </div>
        </div>

    </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════════
     VISTA TABLA
════════════════════════════════════════════ --}}
<div id="mnViewTable" class="panel mn-tabla-panel">
    <div class="mn-tabla-wrap">
        <table class="mn-tabla">
            <thead>
                <tr>
                    <th>Rifa</th>
                    <th>Lotería / Día</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Mis números</th>
                    <th>Premio</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rifas as $r)
                <tr class="mn-tabla-row {{ $r['gane'] ? 'mn-tabla-row--win' : '' }}"
                    data-estado="{{ $r['estado'] }}"
                    data-gane="{{ $r['gane'] ? 'true' : 'false' }}">

                    <td>
                        <div class="mn-td-nombre">
                            <div class="mn-td-ico {{ $r['gane'] ? 'mn-td-ico--win' : ($r['estado'] === 'finalizada' ? 'mn-td-ico--fin' : '') }}">
                                @if($r['gane'])
                                    <i class="fas fa-trophy"></i>
                                @elseif($r['estado'] === 'finalizada')
                                    <i class="fas fa-flag-checkered"></i>
                                @else
                                    <i class="fas fa-ticket-alt"></i>
                                @endif
                            </div>
                            <span>{{ $r['nombre'] }}</span>
                        </div>
                    </td>

                    <td class="mn-td-sub">{{ $r['loteria'] }}</td>

                    <td class="mn-td-sub">{{ $r['fecha'] }}</td>

                    <td>
                        @if($r['gane'])
                            <span class="mn-badge mn-badge--win"><i class="fas fa-trophy"></i> ¡Ganaste!</span>
                        @elseif($r['estado'] === 'activa')
                            <span class="mn-badge mn-badge--activa"><span class="mn-badge-dot"></span> Activa</span>
                        @else
                            <span class="mn-badge mn-badge--final"><span class="mn-badge-dot"></span> Finalizada</span>
                        @endif
                    </td>

                    <td>
                        <div class="mn-td-nums">
                            @foreach($r['numeros'] as $n)
                            @php $esGanador = $r['resultado'] === $n->numero; @endphp
                            <span class="mn-td-num {{ $esGanador ? 'mn-num--win' : 'mn-num--' . $n->estado }}">
                                {{ $n->numero }}@if($esGanador) <i class="fas fa-star"></i>@endif
                            </span>
                            @endforeach
                        </div>
                    </td>

                    <td class="mn-td-premio">${{ $r['premio'] }}</td>

                    <td>
                        @if($r['resultado'])
                            <span class="mn-td-resultado {{ $r['gane'] ? 'mn-td-resultado--win' : '' }}">
                                {{ $r['resultado'] }}
                            </span>
                        @else
                            <span class="mn-td-empty">—</span>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mn-pagination" id="mnPagination">
        <button class="mn-page-btn" id="mnPagePrev" disabled>
            <i class="fas fa-chevron-left"></i>
        </button>
        <span class="mn-page-info" id="mnPageInfo"></span>
        <button class="mn-page-btn" id="mnPageNext">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

@endif

@endsection

@push('scripts')
<script src="{{ asset('assets/js/cliente/mis-numeros.js') }}"></script>
@endpush
