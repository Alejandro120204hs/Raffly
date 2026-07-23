@extends('cliente.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/cliente/rifas.css') }}">
@endpush

@section('title', 'Rifas disponibles')
@section('page-title', 'Rifas disponibles')

@section('content')

@if($rifas->isEmpty())
<div class="panel">
    <div class="rf-empty">
        <i class="fas fa-star"></i>
        <h3>No hay rifas activas</h3>
        <p>Por el momento no hay rifas disponibles. ¡Vuelve pronto!</p>
    </div>
</div>
@else

<div class="rifas-grid">
    @foreach($rifas as $r)
    <a href="{{ route('cliente.rifas.show', $r['id']) }}" class="rifa-card panel {{ $r['participo'] ? 'rifa-card--participo' : '' }}" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;">

        {{-- Badge participo ── --}}
        @if($r['participo'])
        <div class="rifa-card-badge">
            <i class="fas fa-check-circle"></i> Participas ({{ $r['misNums'] }} {{ $r['misNums'] === 1 ? 'número' : 'números' }})
        </div>
        @endif

        {{-- Header ── --}}
        <div class="rifa-card-header">
            <div class="rifa-card-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="rifa-card-info">
                <h3 class="rifa-card-nombre">{{ $r['nombre'] }}</h3>
                <span class="rifa-card-meta">{{ $r['loteria'] }}</span>
            </div>
        </div>

        {{-- Premio ── --}}
        <div class="rifa-card-premio">
            <i class="fas fa-gift"></i>
            <span>{{ $r['premio'] }}</span>
        </div>

        {{-- Stats ── --}}
        <div class="rifa-card-stats">
            <div class="rifa-stat">
                <span class="rifa-stat-val">${{ number_format($r['precio'], 0, ',', '.') }}</span>
                <span class="rifa-stat-label">Por boleta</span>
            </div>
            <div class="rifa-stat">
                <span class="rifa-stat-val">{{ $r['disponibles'] }}</span>
                <span class="rifa-stat-label">Disponibles</span>
            </div>
            <div class="rifa-stat">
                <span class="rifa-stat-val">{{ $r['fecha'] }}</span>
                <span class="rifa-stat-label">Fecha sorteo</span>
            </div>
        </div>

        {{-- Progreso ── --}}
        <div class="rifa-card-prog">
            <div class="prog-row">
                <span class="prog-label">{{ $r['vendidos'] }} / {{ $r['total'] }} vendidos</span>
                <span class="prog-pct-label">{{ $r['pct'] }}%</span>
            </div>
            <div class="prog-bar-full">
                <div class="prog-bar-fill" style="width: {{ $r['pct'] }}%"></div>
            </div>
        </div>

    </a>
    @endforeach
</div>

@endif

@endsection

@push('scripts')
<script src="{{ asset('assets/js/cliente/rifas.js') }}"></script>
@endpush
