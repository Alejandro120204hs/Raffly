@extends('admin.layouts.app')

@section('title', $rifa['nombre'])
@section('page-title', 'Rifas')

@section('content')

@php
    $pendientesCount = count($pendientes ?? []);
    $pct         = round(($rifa['vendidos'] / $rifa['total']) * 100);
    $disponibles = $rifa['total'] - $rifa['vendidos'] - $pendientesCount;
    $recaudado   = $rifa['vendidos'] * $rifa['precio'];
    $vendidosSet   = array_flip($vendidos);
    $pendientesSet = array_flip($pendientes ?? []);
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

            @if($ganador)
            <div class="rifa-card-divider"></div>

            {{-- Ganador --}}
            <div class="ganador-block">
                <div class="ganador-header">
                    <span class="ganador-trophy">🏆</span>
                    <span class="ganador-titulo">Ganador</span>
                </div>

                @if($ganador['tipo'] === 'sin_comprador')
                    <div class="ganador-empty">
                        <i class="fas fa-ticket-alt"></i>
                        El número ganador <strong>{{ $rifa['resultado'] }}</strong> no fue vendido
                    </div>
                @else
                    <div class="ganador-card">
                        <div class="ganador-avatar">
                            {{ strtoupper(substr($ganador['nombre'], 0, 1)) }}
                        </div>
                        <div class="ganador-info">
                            <span class="ganador-nombre">{{ $ganador['nombre'] }}</span>
                            @if($ganador['celular'])
                                <span class="ganador-dato"><i class="fas fa-phone"></i> {{ $ganador['celular'] }}</span>
                            @endif
                            @if($ganador['ubicacion'])
                                <span class="ganador-dato"><i class="fas fa-map-marker-alt"></i> {{ $ganador['ubicacion'] }}</span>
                            @endif
                            @if($ganador['tipo'] === 'registrado')
                                <span class="ganador-badge"><i class="fas fa-user-check"></i> Usuario registrado</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @endif

            <div class="rifa-card-divider"></div>

            {{-- KPIs mini --}}
            <div class="mini-kpis">
                <div class="mini-kpi mini-kpi--purple">
                    <span class="mini-kpi-val">{{ $rifa['vendidos'] }}</span>
                    <span class="mini-kpi-label">Vendidos</span>
                </div>
                <div class="mini-kpi mini-kpi--amber">
                    <span class="mini-kpi-val">{{ $pendientesCount }}</span>
                    <span class="mini-kpi-label">Por pagar</span>
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

{{-- Acciones --}}
        <div class="detail-actions">
            @if($rifa['estado'] === 'activa')
            <button class="btn-action btn-action--draw" id="btnRegistrarResultado">
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
                            @elseif(isset($pendientesSet[$n]))
                                <div class="num-bubble num-bubble--pending" data-n="{{ $n }}" title="Falta por pagar">{{ str_pad($n, $rifa['cifras'], '0', STR_PAD_LEFT) }}</div>
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
                <div class="flyer-cell {{ isset($vendidosSet[$n]) ? 'flyer-cell--sold' : (isset($pendientesSet[$n]) ? 'flyer-cell--pending' : '') }}" data-n="{{ $n }}">
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

{{-- Modal Registrar Resultado --}}
<div id="modalResultado" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-icon"><i class="fas fa-trophy"></i></div>
            <div>
                <h3 class="modal-title">Registrar Resultado</h3>
                <p class="modal-sub">Ingresa las últimas {{ $rifa['cifras'] }} cifras del sorteo</p>
            </div>
        </div>

        <form action="{{ route('admin.rifas.resultado', $rifa['id']) }}" method="POST" id="formResultado">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div class="form-field">
                    <label class="form-label" for="resultadoInput">
                        Número ganador <span class="required">*</span>
                    </label>
                    <input type="text" id="resultadoInput" name="resultado"
                           class="form-input resultado-input"
                           placeholder="{{ str_repeat('0', $rifa['cifras']) }}"
                           maxlength="{{ $rifa['cifras'] }}"
                           pattern="\d{!! '{' . $rifa['cifras'] . '}' !!}"
                           inputmode="numeric">
                    <span class="form-hint">
                        Debe tener exactamente {{ $rifa['cifras'] }} dígitos (del {{ str_repeat('0', $rifa['cifras']) }} al {{ str_repeat('9', $rifa['cifras']) }})
                    </span>
                </div>

                <div class="modal-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    La rifa pasará a estado <strong>Finalizada</strong> y no se podrá deshacer.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnCerrarModal">Cancelar</button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i> Confirmar resultado
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Comprador --}}
<div id="modalComprador" class="modal-overlay" style="display:none">
    <div class="modal-box" style="max-width:480px">
        <div class="modal-header">
            <div class="modal-header-icon" style="background:#EDE9FE;color:#7C3AED">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h3 class="modal-title">Datos del comprador</h3>
                <p class="modal-sub">Número <strong id="compradorNumLabel">00</strong></p>
            </div>
        </div>

        <div class="modal-body">
            {{-- Tabs --}}
            <div class="comprador-tabs">
                <button class="comprador-tab comprador-tab--active" data-tab="registrado">
                    <i class="fas fa-user-check"></i> Usuario registrado
                </button>
                <button class="comprador-tab" data-tab="externo">
                    <i class="fas fa-user-plus"></i> Sin cuenta
                </button>
            </div>

            {{-- Tab: registrado --}}
            <div id="tabRegistrado" class="comprador-tab-content">
                <div class="form-field">
                    <label class="form-label">Buscar cliente</label>
                    <input type="text" id="buscarCliente" class="form-input" placeholder="Escribe nombre o correo...">
                </div>
                <div id="listaClientes" class="cliente-lista"></div>
                <input type="hidden" id="selectedUserId">
            </div>

            {{-- Tab: externo --}}
            <div id="tabExterno" class="comprador-tab-content" style="display:none">
                <div class="form-row">
                    <div class="form-field">
                        <label class="form-label">Nombre <span class="required">*</span></label>
                        <input type="text" id="extNombre" class="form-input" placeholder="Nombre">
                    </div>
                    <div class="form-field">
                        <label class="form-label">Apellido <span class="required">*</span></label>
                        <input type="text" id="extApellido" class="form-input" placeholder="Apellido">
                    </div>
                </div>
                <div class="form-field">
                    <label class="form-label">Departamento</label>
                    <select id="extDepartamento" class="form-select">
                        <option value="">Seleccionar departamento...</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Municipio</label>
                    <select id="extMunicipio" class="form-select" disabled>
                        <option value="">Primero selecciona un departamento</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Celular <span class="required">*</span></label>
                    <input type="text" id="extCelular" class="form-input" placeholder="Ej: 3001234567">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCerrarComprador">Cancelar</button>
            <button type="button" class="btn-primary" id="btnConfirmarComprador">
                <i class="fas fa-check"></i> Confirmar
            </button>
        </div>
    </div>
</div>

{{-- Popup de estado de número --}}
<div id="numPopup" class="num-popup" style="display:none">
    <div class="num-popup-header">
        <span>Número <strong id="numPopupNum">00</strong></span>
        <button class="num-popup-close" id="numPopupClose">&times;</button>
    </div>
    <div class="num-popup-options">
        <button class="num-popup-btn num-popup-btn--free" data-state="free">
            <i class="fas fa-check-circle"></i> Disponible
        </button>
        <button class="num-popup-btn num-popup-btn--pending" data-state="pending">
            <i class="fas fa-clock"></i> Falta por pagar
        </button>
        <button class="num-popup-btn num-popup-btn--sold" data-state="sold">
            <i class="fas fa-times-circle"></i> Vendido
        </button>
    </div>
    <div id="numPopupVerDetalle" style="display:none;border-top:1px solid #e5e7eb;padding:.5rem .75rem 0">
        <button id="btnVerComprador" class="num-popup-btn-info">
            <i class="fas fa-user-circle"></i> Ver datos del comprador
        </button>
    </div>
</div>

{{-- Modal detalle comprador --}}
<div id="modalDetalleComprador" class="modal-overlay" style="display:none">
    <div class="modal-box" style="max-width:380px">
        <div class="modal-header">
            <div class="modal-header-icon" style="background:#FEF9C3;color:#B45309">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h3 class="modal-title">Datos del comprador</h3>
                <p class="modal-sub">Número <strong id="detalleNumLabel">00</strong></p>
            </div>
        </div>
        <div class="modal-body">
            <div class="detalle-comprador">
                <div class="detalle-row">
                    <span class="detalle-icon"><i class="fas fa-user"></i></span>
                    <div class="detalle-info">
                        <span class="detalle-label">Nombre</span>
                        <span class="detalle-val" id="detalleNombre">—</span>
                    </div>
                </div>
                <div class="detalle-row">
                    <span class="detalle-icon"><i class="fas fa-phone"></i></span>
                    <div class="detalle-info">
                        <span class="detalle-label">Celular</span>
                        <span class="detalle-val" id="detalleCelular">—</span>
                    </div>
                </div>
                <div class="detalle-row" id="detalleUbicacionRow">
                    <span class="detalle-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="detalle-info">
                        <span class="detalle-label">Ubicación</span>
                        <span class="detalle-val" id="detalleUbicacion">—</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCerrarDetalle">Cerrar</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="application/json" id="rifaConfig">{!! json_encode([
    'total'       => $rifa['total'],
    'cifras'      => $rifa['cifras'],
    'vendidos'    => $vendidos,
    'pendientes'  => $pendientes ?? [],
    'updateUrl'   => $updateUrl ?? null,
    'flyerSlug'   => Str::slug($rifa['nombre']),
    'clientes'    => ($clientes ?? collect())->values(),
    'compradores' => $compradores ?? [],
]) !!}</script>
<script src="{{ asset('assets/js/admin/colombia-geo.js') }}"></script>
<script src="{{ asset('assets/js/admin/rifas-show.js') }}"></script>
@endpush
