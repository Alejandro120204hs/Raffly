@extends('admin.layouts.app')

@section('title', 'Editar Rifa')
@section('page-title', 'Rifas')

@section('content')

<div class="breadcrumb-bar">
    <a href="{{ route('admin.rifas.index') }}" class="breadcrumb-link">
        <i class="fas fa-ticket-alt"></i> Rifas
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">Editar Rifa</span>
</div>

<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Editar Rifa</h2>
        <p class="page-header-sub">Modifica los datos de la rifa</p>
    </div>
    <a href="{{ route('admin.rifas.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="create-layout">

    <form class="create-form" action="{{ route('admin.rifas.update', $rifa->id) }}" method="POST">
        @csrf
        @method('PATCH')

        {{-- Premio --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-gift"></i></div>
                <div>
                    <h3 class="form-section-title">Información del Premio</h3>
                    <p class="form-section-sub">Qué recibirá el ganador</p>
                </div>
            </div>
            <div class="form-body">
                <div class="form-field">
                    <label class="form-label">Tipo de premio <span class="required">*</span></label>
                    <div class="tipo-selector">
                        <label class="tipo-card {{ $rifa->tipo === 'objeto' ? 'tipo-card--active' : '' }}" id="tipoObjeto">
                            <input type="radio" name="tipo" value="objeto" {{ $rifa->tipo === 'objeto' ? 'checked' : '' }} hidden>
                            <div class="tipo-card-icon tipo-card-icon--objeto"><i class="fas fa-gift"></i></div>
                            <span class="tipo-card-label">Objeto</span>
                            <span class="tipo-card-sub">Producto físico</span>
                            <div class="tipo-card-check"><i class="fas fa-check"></i></div>
                        </label>
                        <label class="tipo-card {{ $rifa->tipo === 'efectivo' ? 'tipo-card--active' : '' }}" id="tipoEfectivo">
                            <input type="radio" name="tipo" value="efectivo" {{ $rifa->tipo === 'efectivo' ? 'checked' : '' }} hidden>
                            <div class="tipo-card-icon tipo-card-icon--efectivo"><i class="fas fa-money-bill-wave"></i></div>
                            <span class="tipo-card-label">Efectivo</span>
                            <span class="tipo-card-sub">Dinero en efectivo</span>
                            <div class="tipo-card-check"><i class="fas fa-check"></i></div>
                        </label>
                    </div>
                </div>

                <div class="form-field">
                    <label class="form-label" for="premio">Premio <span class="required">*</span></label>
                    <input type="text" id="premio" name="premio" class="form-input"
                           value="{{ $rifa->premio }}" maxlength="80">
                </div>
            </div>
        </div>

        {{-- Boleta --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-ticket-alt"></i></div>
                <div>
                    <h3 class="form-section-title">Configuración de la Boleta</h3>
                    <p class="form-section-sub">Las cifras no se pueden cambiar una vez creada la rifa</p>
                </div>
            </div>
            <div class="form-body">
                <div class="form-field">
                    <label class="form-label">Cifras del número</label>
                    <div class="cifras-selector">
                        @foreach([2,3,4] as $c)
                        <div class="cifras-card {{ $rifa->cifras == $c ? 'cifras-card--active' : '' }}" style="opacity: {{ $rifa->cifras == $c ? '1' : '0.4' }}; cursor: default;">
                            <span class="cifras-num">{{ $c }}</span>
                            <span class="cifras-label">cifras</span>
                            <span class="cifras-total">{{ number_format(pow(10,$c), 0, ',', '.') }} números</span>
                        </div>
                        @endforeach
                    </div>
                    <span class="form-hint">Las cifras no se pueden modificar para no afectar los números vendidos.</span>
                </div>

                <div class="form-field">
                    <label class="form-label" for="precio">Precio por número <span class="required">*</span></label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">$</span>
                        <input type="number" id="precio" name="precio" class="form-input form-input--prefix"
                               value="{{ $rifa->precio }}" min="100" step="100">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sorteo --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-dice"></i></div>
                <div>
                    <h3 class="form-section-title">Sorteo y Lotería</h3>
                    <p class="form-section-sub">Cómo se determinará el ganador</p>
                </div>
            </div>
            <div class="form-body">
                <div class="form-row">
                    <div class="form-field">
                        <label class="form-label" for="loteria">Lotería <span class="required">*</span></label>
                        <div class="select-wrap">
                            <select id="loteria" name="loteria" class="form-select">
                                @foreach([
                                    'Lotería de Boyacá','Lotería del Tolima','Lotería de Medellín',
                                    'Lotería de Cundinamarca','Lotería del Huila','Lotería de Bogotá',
                                    'Lotería del Meta','Lotería de Manizales'
                                ] as $lot)
                                <option value="{{ $lot }}" {{ $rifa->loteria === $lot ? 'selected' : '' }}>{{ $lot }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    <div class="form-field">
                        <label class="form-label" for="juega">Día que juega <span class="required">*</span></label>
                        <div class="select-wrap">
                            <select id="juega" name="juega" class="form-select">
                                @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábados','Domingos'] as $dia)
                                <option value="{{ $dia }}" {{ $rifa->juega === $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                </div>

                <div class="form-field">
                    <label class="form-label" for="fecha">Fecha del sorteo <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-calendar input-icon"></i>
                        <input type="date" id="fecha" name="fecha" class="form-input form-input--icon"
                               value="{{ \Carbon\Carbon::parse($rifa->fecha)->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.rifas.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </form>

    {{-- Info rifa --}}
    <div class="create-preview">
        <div class="preview-sticky">
            <h4 class="preview-title"><i class="fas fa-info-circle"></i> Datos actuales</h4>
            <div class="preview-card">
                <div class="preview-card-top">
                    <div class="preview-icon"><i class="fas fa-ticket-alt"></i></div>
                    <span class="status-badge {{ $rifa->estado === 'activa' ? 'status-activa' : 'status-finalizada' }}">
                        <span class="status-dot"></span> {{ ucfirst($rifa->estado) }}
                    </span>
                </div>
                <h3 class="preview-nombre">{{ $rifa->premio }}</h3>
                <div class="preview-divider"></div>
                <div class="preview-stats">
                    <div class="preview-stat">
                        <span class="preview-stat-label">Números vendidos</span>
                        <span class="preview-stat-val">{{ $rifa->numeros()->where('estado','vendido')->count() }}</span>
                    </div>
                    <div class="preview-stat">
                        <span class="preview-stat-label">Falta por pagar</span>
                        <span class="preview-stat-val">{{ $rifa->numeros()->where('estado','pendiente')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
