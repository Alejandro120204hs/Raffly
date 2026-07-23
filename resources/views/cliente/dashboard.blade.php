@extends('cliente.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/cliente/dashboard.css') }}">
@endpush

@section('title', 'Inicio')
@section('page-title', 'Inicio')

@section('content')

{{-- Bienvenida ─────────────────────────────────────── --}}
<div class="dash-welcome-banner">
    <div class="dash-welcome-text">
        <h2>Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
        <p>Aquí tienes el resumen de tus participaciones.</p>
    </div>
    <div class="dash-welcome-date">
        <i class="fas fa-calendar-alt"></i>
        {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
    </div>
</div>

{{-- KPIs ─────────────────────────────────────────────── --}}
<div class="dash-kpis">

    <div class="dash-kpi dash-kpi--purple">
        <div class="dash-kpi-icon"><i class="fas fa-ticket-alt"></i></div>
        <div class="dash-kpi-body">
            <span class="dash-kpi-val">{{ $misBoletas }}</span>
            <span class="dash-kpi-label">Mis boletas</span>
            <span class="dash-kpi-sub">{{ $misVendidos }} pagadas · {{ $misPendientes }} por pagar</span>
        </div>
    </div>

    <div class="dash-kpi dash-kpi--amber">
        <div class="dash-kpi-icon"><i class="fas fa-clock"></i></div>
        <div class="dash-kpi-body">
            <span class="dash-kpi-val">{{ $misPendientes }}</span>
            <span class="dash-kpi-label">Por pagar</span>
            <span class="dash-kpi-sub">Boletas apartadas sin confirmar</span>
        </div>
    </div>

    <div class="dash-kpi dash-kpi--blue">
        <div class="dash-kpi-icon"><i class="fas fa-star"></i></div>
        <div class="dash-kpi-body">
            <span class="dash-kpi-val">{{ $rifasActivas }}</span>
            <span class="dash-kpi-label">Rifas activas</span>
            <span class="dash-kpi-sub">En las que participo</span>
        </div>
    </div>

    <div class="dash-kpi dash-kpi--green">
        <div class="dash-kpi-icon"><i class="fas fa-trophy"></i></div>
        <div class="dash-kpi-body">
            <span class="dash-kpi-val">{{ $gane }}</span>
            <span class="dash-kpi-label">Rifas ganadas</span>
            <span class="dash-kpi-sub">¡Felicidades si ganaste!</span>
        </div>
    </div>

</div>

<div class="dash-grid">

    {{-- Mis rifas activas ─────────────────────────────── --}}
    <div class="panel dash-panel-rifas">
        <div class="panel-header">
            <h3 class="panel-title"><i class="fas fa-ticket-alt"></i> Mis rifas activas</h3>
            <a href="{{ route('cliente.mis-numeros') }}" class="panel-link">Ver todas <i class="fas fa-arrow-right"></i></a>
        </div>

        @forelse($misRifas as $r)
        <div class="mis-rifa-item">
            <div class="mis-rifa-info">
                <span class="mis-rifa-nombre">{{ $r['nombre'] }}</span>
                <span class="mis-rifa-meta">{{ $r['loteria'] }} · {{ $r['fecha'] }}</span>
            </div>
            <div class="mis-rifa-nums">
                @foreach($r['misNums'] as $n)
                    <span class="num-chip">{{ $n }}</span>
                @endforeach
            </div>
            <div class="mis-rifa-side">
                <div class="mis-rifa-prog">
                    @php $pct = $r['total'] > 0 ? round(($r['vendidos']/$r['total'])*100) : 0; @endphp
                    <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                    <span class="prog-pct">{{ $pct }}%</span>
                </div>
                @if($r['misPend'] > 0)
                    <span class="mis-rifa-pend"><i class="fas fa-exclamation-circle"></i> {{ $r['misPend'] }} por pagar</span>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-ticket-alt"></i>
            <p>Aún no participas en ninguna rifa activa.</p>
            <a href="{{ route('cliente.rifas') }}" class="btn-primary" style="margin-top:.75rem">Ver rifas disponibles</a>
        </div>
        @endforelse
    </div>

    {{-- Rifas disponibles ────────────────────────────── --}}
    <div class="panel dash-panel-disponibles">
        <div class="panel-header">
            <h3 class="panel-title"><i class="fas fa-star"></i> Rifas disponibles</h3>
            <a href="{{ route('cliente.rifas') }}" class="panel-link">Ver todas <i class="fas fa-arrow-right"></i></a>
        </div>

        @forelse($disponibles as $r)
        @php $pct = $r['total'] > 0 ? round(($r['vendidos']/$r['total'])*100) : 0; @endphp
        <a href="{{ route('cliente.rifas.show', $r['id']) }}" class="disp-rifa-item">
            <div class="disp-rifa-icon"><i class="fas fa-ticket-alt"></i></div>
            <div class="disp-rifa-info">
                <div class="disp-rifa-nombre-row">
                    <span class="disp-rifa-nombre">{{ $r['nombre'] }}</span>
                    @if($r['participo'])
                        <span class="disp-rifa-badge"><i class="fas fa-check-circle"></i> Participas</span>
                    @endif
                </div>
                <span class="disp-rifa-meta">{{ $r['loteria'] }} · {{ $r['fecha'] }}</span>
                <div class="prog-bar" style="margin-top:.4rem"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
            </div>
            <div class="disp-rifa-precio">${{ number_format($r['precio'], 0, ',', '.') }}</div>
        </a>
        @empty
        <div class="empty-state">
            <i class="fas fa-star"></i>
            <p>No hay rifas activas en este momento.</p>
        </div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/cliente/dashboard.js') }}"></script>
@endpush
