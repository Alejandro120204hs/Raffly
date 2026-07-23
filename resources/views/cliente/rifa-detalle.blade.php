@extends('cliente.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/cliente/rifa-detalle.css') }}">
@endpush

@section('title', $rifa->nombre)
@section('page-title', 'Rifas disponibles')

@section('content')

{{-- Config para JS ── --}}
<script type="application/json" id="rifaConfig">
{
    "rifaId":   {{ $rifa->id }},
    "cifras":   {{ $rifa->cifras }},
    "precio":   {{ $rifa->precio }},
    "reservarUrl": "{{ url('/cliente/rifas/' . $rifa->id . '/numeros') }}"
}
</script>

{{-- Breadcrumb ── --}}
<div class="rd-breadcrumb">
    <a href="{{ route('cliente.rifas') }}"><i class="fas fa-arrow-left"></i> Rifas disponibles</a>
</div>

{{-- Header de la rifa ─────────────────────────────── --}}
<div class="rd-header panel">
    <div class="rd-header-main">
        <div class="rd-header-icon"><i class="fas fa-ticket-alt"></i></div>
        <div class="rd-header-info">
            <h2 class="rd-nombre">{{ $rifa->nombre }}</h2>
            <div class="rd-meta">
                <span><i class="fas fa-dice"></i> {{ $rifa->loteria }}</span>
                <span><i class="fas fa-calendar-day"></i> {{ $rifa->juega }}</span>
                <span><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($rifa->fecha)->format('d M Y') }}</span>
                <span><i class="fas fa-tag"></i> ${{ number_format($rifa->precio, 0, ',', '.') }} por número</span>
            </div>
        </div>
    </div>
    <div class="rd-header-premio">
        <i class="fas fa-gift"></i>
        <div>
            <span class="rd-premio-label">Premio</span>
            <span class="rd-premio-val">{{ $rifa->premio }}</span>
        </div>
    </div>
</div>

{{-- KPIs ─────────────────────────────────────────── --}}
<div class="rd-kpis">
    <div class="rd-kpi rd-kpi--green">
        <i class="fas fa-check-circle"></i>
        <span class="rd-kpi-val">{{ $vendidos }}</span>
        <span class="rd-kpi-label">Vendidos</span>
    </div>
    <div class="rd-kpi rd-kpi--amber">
        <i class="fas fa-clock"></i>
        <span class="rd-kpi-val">{{ $pendientes }}</span>
        <span class="rd-kpi-label">Pendientes</span>
    </div>
    <div class="rd-kpi rd-kpi--blue">
        <i class="fas fa-ticket-alt"></i>
        <span class="rd-kpi-val">{{ $disponibles }}</span>
        <span class="rd-kpi-label">Disponibles</span>
    </div>
    @if($misNums > 0)
    <div class="rd-kpi rd-kpi--purple">
        <i class="fas fa-star"></i>
        <span class="rd-kpi-val">{{ $misNums }}</span>
        <span class="rd-kpi-label">Mis números</span>
    </div>
    @endif
</div>

{{-- Leyenda ─────────────────────────────────────── --}}
<div class="rd-leyenda panel">
    <span class="rd-ley-item rd-ley--disp"><span class="rd-ley-dot"></span> Disponible (clic para apartar)</span>
    <span class="rd-ley-item rd-ley--pend"><span class="rd-ley-dot"></span> Pendiente de pago</span>
    <span class="rd-ley-item rd-ley--vend"><span class="rd-ley-dot"></span> Vendido</span>
    <span class="rd-ley-item rd-ley--mio"><span class="rd-ley-dot"></span> Mis números</span>
</div>

{{-- Grid de números ──────────────────────────────── --}}
<div class="panel rd-numeros-panel">
    <div class="rd-numeros-header">
        <span class="rd-numeros-info" id="numerosInfo">—</span>
        <div class="rd-pagination" id="paginacion"></div>
    </div>
    <div class="rd-numeros-grid" id="numerosGrid">
        @foreach($numeros as $n)
        <button
            class="rd-num rd-num--{{ $n['estado'] }} {{ $n['mio'] ? 'rd-num--mio' : '' }}"
            data-numero="{{ $n['numero'] }}"
            data-estado="{{ $n['estado'] }}"
            {{ $n['estado'] !== 'disponible' ? 'disabled' : '' }}>
            {{ $n['numero'] }}
        </button>
        @endforeach
    </div>
</div>

{{-- Modal de confirmación ───────────────────────── --}}
<div class="rd-modal-overlay" id="modalOverlay">
    <div class="rd-modal">
        <div class="rd-modal-header">
            <div class="rd-modal-icon"><i class="fas fa-ticket-alt"></i></div>
            <h3 class="rd-modal-title">Apartar número</h3>
        </div>
        <div class="rd-modal-body">
            <div class="rd-modal-num" id="modalNumero">—</div>
            <p class="rd-modal-info">
                Al apartar este número quedará en estado <strong>Pendiente</strong>.<br>
                El administrador lo marcará como <strong>Pagado</strong> una vez verifique tu pago.
            </p>
            <div class="rd-modal-precio">
                <i class="fas fa-tag"></i>
                <span>Valor a pagar: <strong>${{ number_format($rifa->precio, 0, ',', '.') }}</strong></span>
            </div>
        </div>
        <div class="rd-modal-footer">
            <button class="btn-secondary" id="btnCancelar">Cancelar</button>
            <button class="btn-primary" id="btnConfirmar">
                <i class="fas fa-check"></i> Confirmar
            </button>
        </div>
    </div>
</div>

{{-- Toast de éxito ─────────────────────────────── --}}
<div class="rd-toast" id="rdToast">
    <i class="fas fa-check-circle"></i>
    <span id="rdToastMsg">Número apartado correctamente</span>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/cliente/rifa-detalle.js') }}"></script>
@endpush
