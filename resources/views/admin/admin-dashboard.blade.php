@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Bienvenida --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>¡Bienvenido, {{ auth()->user()->name }}! 👋</h2>
        <p>Aquí tienes el resumen de hoy en Rafflys.</p>
    </div>
    <div class="welcome-date">
        <i class="fas fa-calendar-alt"></i>
        {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
    </div>
</div>

{{-- KPI Cards --}}
<div class="kpi-grid">

    <div class="kpi-card kpi-card--purple">
        <div class="kpi-card-inner">
            <div class="kpi-icon-wrap">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-value">{{ $stats['rifas_activas'] }}</span>
                <span class="kpi-label">Rifas Activas</span>
            </div>
        </div>
        <div class="kpi-decoration">
            <i class="fas fa-ticket-alt"></i>
        </div>
    </div>

    <div class="kpi-card kpi-card--gold">
        <div class="kpi-card-inner">
            <div class="kpi-icon-wrap">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-value">{{ $stats['rifas_finalizadas'] }}</span>
                <span class="kpi-label">Rifas Finalizadas</span>
            </div>
        </div>
        <div class="kpi-decoration">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="kpi-card kpi-card--green">
        <div class="kpi-card-inner">
            <div class="kpi-icon-wrap">
                <i class="fas fa-users"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-value">{{ $stats['usuarios'] }}</span>
                <span class="kpi-label">Usuarios Registrados</span>
            </div>
        </div>
        <div class="kpi-decoration">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="kpi-card kpi-card--blue">
        <div class="kpi-card-inner">
            <div class="kpi-icon-wrap">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-value">${{ number_format($stats['premios_entregados'], 0, ',', '.') }}</span>
                <span class="kpi-label">Premios Entregados</span>
            </div>
        </div>
        <div class="kpi-decoration">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

</div>

{{-- Panels --}}
<div class="dashboard-grid">

    {{-- Próximos Sorteos --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title-wrap">
                <div class="panel-title-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2 class="panel-title">Próximos Sorteos</h2>
            </div>
            <span class="panel-count">{{ count($proximosSorteos) }} activas</span>
        </div>
        <div class="panel-body">
            @forelse($proximosSorteos as $rifa)
            @php
                $pct    = $rifa['total'] > 0 ? round(($rifa['vendidos'] / $rifa['total']) * 100) : 0;
                $status = $pct >= 80 ? 'hot' : ($pct >= 50 ? 'warm' : 'cool');
            @endphp
            <div class="sorteo-item">
                <div class="sorteo-top">
                    <div>
                        <span class="sorteo-nombre">{{ $rifa['nombre'] }}</span>
                        <span class="sorteo-loteria">{{ $rifa['loteria'] }}</span>
                    </div>
                    <span class="sorteo-badge sorteo-badge--{{ $status }}">
                        {{ $pct >= 80 ? '🔥 Casi lleno' : ($pct >= 50 ? '⚡ En curso' : '🎯 Disponible') }}
                    </span>
                </div>
                <div class="sorteo-meta">
                    <span class="sorteo-fecha"><i class="fas fa-clock"></i> {{ $rifa['fecha'] }}</span>
                    <span class="sorteo-nums">{{ $rifa['vendidos'] }}/{{ $rifa['total'] }} números</span>
                </div>
                <div class="progreso-bar">
                    <div class="progreso-fill progreso-fill--{{ $status }}" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <div class="sorteo-empty">
                <i class="fas fa-calendar-times"></i>
                <p>No hay rifas activas en este momento.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Últimos Ganadores --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title-wrap">
                <div class="panel-title-icon panel-title-icon--gold">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 class="panel-title">Últimos Ganadores</h2>
            </div>
            <span class="panel-count">{{ count($ultimosGanadores) }} sorteos</span>
        </div>
        <div class="panel-body">
            @foreach($ultimosGanadores as $i => $rifa)
            <div class="ganador-item">
                <div class="ganador-rank ganador-rank--{{ $i + 1 }}">
                    {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '#' . ($i + 1))) }}
                </div>
                <div class="ganador-info">
                    <span class="ganador-rifa">{{ $rifa['nombre'] }}</span>
                    <span class="ganador-numero">Número ganador: <strong>#{{ $rifa['numero'] }}</strong></span>
                </div>
                <div class="ganador-premio-wrap">
                    <span class="ganador-premio">${{ number_format($rifa['premio'], 0, ',', '.') }}</span>
                    <span class="ganador-label">premio</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
