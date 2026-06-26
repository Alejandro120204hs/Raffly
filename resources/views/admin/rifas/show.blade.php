@extends('admin.layouts.app')

@section('title', $rifa['nombre'])
@section('page-title', 'Rifas')

@section('content')

@php
    $pct = round(($rifa['vendidos'] / $rifa['total']) * 100);
    $disponibles = $rifa['total'] - $rifa['vendidos'];
    $recaudado = $rifa['vendidos'] * $rifa['precio'];
    $vendidosSet = array_flip($vendidos);
@endphp

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
    <a href="{{ route('admin.rifas.index') }}" class="breadcrumb-link">
        <i class="fas fa-ticket-alt"></i> Rifas
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">{{ $rifa['nombre'] }}</span>
</div>

{{-- Layout de dos columnas --}}
<div class="detail-layout">

    {{-- Columna izquierda: info --}}
    <div class="detail-sidebar">

        {{-- Card de la rifa --}}
        <div class="rifa-card">
            <div class="rifa-card-header">
                <div class="rifa-card-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div>
                    @if($rifa['estado'] === 'activa')
                        <span class="status-badge status-activa"><span class="status-dot"></span> Activa</span>
                    @else
                        <span class="status-badge status-finalizada"><span class="status-dot"></span> Finalizada</span>
                    @endif
                </div>
            </div>

            <h2 class="rifa-card-title">{{ $rifa['nombre'] }}</h2>

            <div class="rifa-card-premio">
                <span class="rifa-card-premio-label">
                    @if($rifa['tipo'] === 'efectivo')
                        <i class="fas fa-money-bill-wave"></i> Premio en efectivo
                    @else
                        <i class="fas fa-gift"></i> Premio
                    @endif
                </span>
                <span class="rifa-card-premio-value">{{ $rifa['premio'] }}</span>
            </div>

            <div class="rifa-card-divider"></div>

            <div class="rifa-card-stats">
                <div class="rifa-stat">
                    <span class="rifa-stat-label">Precio por número</span>
                    <span class="rifa-stat-value">${{ number_format($rifa['precio'], 0, ',', '.') }}</span>
                </div>
                <div class="rifa-stat">
                    <span class="rifa-stat-label">Fecha del sorteo</span>
                    <span class="rifa-stat-value"><i class="fas fa-calendar"></i> {{ $rifa['fecha'] }}</span>
                </div>
            </div>

            <div class="rifa-card-divider"></div>

            {{-- Progreso --}}
            <div class="rifa-progreso-wrap">
                <div class="rifa-progreso-top">
                    <span class="rifa-progreso-label">Números vendidos</span>
                    <span class="rifa-progreso-pct">{{ $pct }}%</span>
                </div>
                <div class="progreso-bar progreso-bar--lg">
                    <div class="progreso-fill {{ $pct >= 80 ? 'progreso-fill--hot' : ($pct >= 50 ? 'progreso-fill--warm' : 'progreso-fill--cool') }}"
                         style="width: {{ $pct }}%"></div>
                </div>
                <div class="rifa-progreso-nums">
                    <span>{{ $rifa['vendidos'] }} vendidos</span>
                    <span>{{ $rifa['total'] }} total</span>
                </div>
            </div>

            <div class="rifa-card-divider"></div>

            {{-- Info lotería --}}
            <div class="loteria-block">
                <div class="loteria-header">
                    <i class="fas fa-dice"></i>
                    <span class="loteria-titulo">Resultado por lotería</span>
                </div>
                <div class="loteria-name">{{ $rifa['loteria'] }}</div>
                <div class="loteria-meta">
                    <div class="loteria-meta-item">
                        <span class="loteria-meta-label">Juega</span>
                        <span class="loteria-meta-val">{{ $rifa['juega'] }}</span>
                    </div>
                    <div class="loteria-meta-item">
                        <span class="loteria-meta-label">Cifras</span>
                        <span class="loteria-meta-val">Últimas {{ $rifa['cifras'] }}</span>
                    </div>
                </div>

                @if($rifa['resultado'])
                    <div class="loteria-resultado">
                        <span class="loteria-resultado-label">Número ganador</span>
                        <div class="loteria-resultado-num">
                            @foreach(str_split($rifa['resultado']) as $digito)
                                <span class="digito-box">{{ $digito }}</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="loteria-pendiente">
                        <i class="fas fa-clock"></i> Pendiente del sorteo — {{ $rifa['fecha'] }}
                    </div>
                @endif
            </div>

            <div class="rifa-card-divider"></div>

            {{-- KPIs mini --}}
            <div class="mini-kpis">
                <div class="mini-kpi mini-kpi--purple">
                    <span class="mini-kpi-val">{{ $rifa['vendidos'] }}</span>
                    <span class="mini-kpi-label">Vendidos</span>
                </div>
                <div class="mini-kpi mini-kpi--green">
                    <span class="mini-kpi-val">{{ $disponibles }}</span>
                    <span class="mini-kpi-label">Disponibles</span>
                </div>
                <div class="mini-kpi mini-kpi--gold">
                    <span class="mini-kpi-val">${{ number_format($recaudado, 0, ',', '.') }}</span>
                    <span class="mini-kpi-label">Recaudado</span>
                </div>
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="legend-card">
            <h4 class="legend-title">Leyenda</h4>
            <div class="legend-items">
                <div class="legend-item">
                    <span class="num-bubble num-bubble--free">7</span>
                    <span>Disponible</span>
                </div>
                <div class="legend-item">
                    <span class="num-bubble num-bubble--sold">7</span>
                    <span>Vendido</span>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="detail-actions">
            @if($rifa['estado'] === 'activa')
            <button class="btn-action btn-action--draw">
                <i class="fas fa-trophy"></i> Registrar Resultado
            </button>
            @endif
            @if($rifa['cifras'] === 2)
            <button class="btn-action btn-action--flyer" id="btnGenerarFlyer">
                <i class="fas fa-image"></i> Generar Imagen
            </button>
            @endif
            <a href="{{ route('admin.rifas.index') }}" class="btn-action btn-action--back">
                <i class="fas fa-arrow-left"></i> Volver a Rifas
            </a>
        </div>

    </div>

    {{-- Columna derecha: grid de números --}}
    <div class="detail-main">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <h2 class="panel-title">Números</h2>
                </div>
                <span class="panel-count">{{ number_format($rifa['total'], 0, ',', '.') }} posibles</span>
            </div>
            <div class="panel-body">

                {{-- Buscador siempre visible --}}
                <div class="num-search-wrap">
                    <div class="num-search-box">
                        <i class="fas fa-search"></i>
                        <input type="number"
                               id="numSearch"
                               min="0"
                               max="{{ $rifa['total'] - 1 }}"
                               placeholder="Buscar número (ej: {{ str_pad(42, $rifa['cifras'], '0', STR_PAD_LEFT) }})"
                               class="num-search-input">
                    </div>
                    <div id="numSearchResult" class="num-search-result" style="display:none"></div>
                </div>

                @if($rifa['total'] <= 100)
                    {{-- Grid completo para rifas de 2 cifras (PHP) --}}
                    <div class="num-grid" id="numGrid">
                        @foreach($todos as $n)
                            @if(isset($vendidosSet[$n]))
                                <div class="num-bubble num-bubble--sold" data-n="{{ $n }}" title="Vendido">{{ str_pad($n, $rifa['cifras'], '0', STR_PAD_LEFT) }}</div>
                            @else
                                <div class="num-bubble num-bubble--free" data-n="{{ $n }}" title="Disponible">{{ str_pad($n, $rifa['cifras'], '0', STR_PAD_LEFT) }}</div>
                            @endif
                        @endforeach
                    </div>
                @else
                    {{-- Grid paginado JS para 3 y 4 cifras --}}
                    <div class="num-grid num-grid--sm" id="numGrid"></div>
                    <div class="table-footer">
                        <span class="table-info" id="gridInfo"></span>
                        <div class="pagination" id="gridPagination"></div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>

@if($rifa['cifras'] === 2)
{{-- Flyer off-screen --}}
<div id="rifaFlyer">

    <div class="flyer-header">
        <div class="flyer-rays"></div>
        <div class="flyer-stars">✦ ✦ ✦</div>
        <div class="flyer-title">Raffly</div>
        
    </div>

    <div class="flyer-info">
        <div class="flyer-info-row">
            <span class="flyer-info-icon">💰</span>
            <span><strong>Premio:</strong> {{ $rifa['premio'] }}</span>
        </div>
        <div class="flyer-info-row">
            <span class="flyer-info-icon">🎟️</span>
            <span><strong>Valor boleta:</strong> ${{ number_format($rifa['precio'], 0, ',', '.') }}</span>
        </div>
        <div class="flyer-info-row">
            <span class="flyer-info-icon">🎰</span>
            <span><strong>Lotería:</strong> {{ $rifa['loteria'] }}</span>
        </div>
        <div class="flyer-info-row flyer-info-row--note">
            <span class="flyer-info-icon">📅</span>
            <span>Sorteo: <strong>{{ $rifa['fecha'] }}</strong> — Últimas {{ $rifa['cifras'] }} cifras</span>
        </div>
    </div>

    <div class="flyer-grid-wrap">
        <div class="flyer-grid">
            @foreach($todos as $n)
                <div class="flyer-cell {{ isset($vendidosSet[$n]) ? 'flyer-cell--sold' : '' }}">
                    {{ str_pad($n, 2, '0', STR_PAD_LEFT) }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="flyer-footer">
        <span class="flyer-footer-brand">⚡ Raffly</span>
        <span class="flyer-footer-sub">rifas con confianza</span>
    </div>

</div>
@endif

@endsection

@push('scripts')
<script type="application/json" id="rifaConfig">{!! json_encode([
    'total'     => $rifa['total'],
    'cifras'    => $rifa['cifras'],
    'vendidos'  => $vendidos,
    'flyerSlug' => Str::slug($rifa['nombre']),
]) !!}</script>
<script src="{{ asset('assets/js/admin/rifas-show.js') }}"></script>
@endpush
