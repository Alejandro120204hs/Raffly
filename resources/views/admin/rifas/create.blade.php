@extends('admin.layouts.app')

@section('title', 'Crear Rifa')
@section('page-title', 'Rifas')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
    <a href="{{ route('admin.rifas.index') }}" class="breadcrumb-link">
        <i class="fas fa-ticket-alt"></i> Rifas
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">Crear Rifa</span>
</div>

{{-- Header --}}
<div class="page-header">
    <div class="page-header-left">
        <h2 class="page-header-title">Crear Nueva Rifa</h2>
        <p class="page-header-sub">Configura todos los detalles antes de publicar</p>
    </div>
    <a href="{{ route('admin.rifas.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

{{-- Layout form + preview --}}
<div class="create-layout">

    {{-- Formulario --}}
    <form class="create-form" id="rifaForm" action="{{ route('admin.rifas.store') }}" method="POST">
        @csrf

        {{-- Sección 1: Premio --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div>
                    <h3 class="form-section-title">Información del Premio</h3>
                    <p class="form-section-sub">Qué recibirá el ganador</p>
                </div>
            </div>

            <div class="form-body">
                <div class="form-field">
                    <label class="form-label">Tipo de premio <span class="required">*</span></label>
                    <div class="tipo-selector">
                        <label class="tipo-card" id="tipoObjeto">
                            <input type="radio" name="tipo" value="objeto" checked hidden>
                            <div class="tipo-card-icon tipo-card-icon--objeto">
                                <i class="fas fa-gift"></i>
                            </div>
                            <span class="tipo-card-label">Objeto</span>
                            <span class="tipo-card-sub">Producto físico</span>
                            <div class="tipo-card-check"><i class="fas fa-check"></i></div>
                        </label>
                        <label class="tipo-card" id="tipoEfectivo">
                            <input type="radio" name="tipo" value="efectivo" hidden>
                            <div class="tipo-card-icon tipo-card-icon--efectivo">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <span class="tipo-card-label">Efectivo</span>
                            <span class="tipo-card-sub">Dinero en efectivo</span>
                            <div class="tipo-card-check"><i class="fas fa-check"></i></div>
                        </label>
                    </div>
                </div>

                <div class="form-field">
                    <label class="form-label" for="premio" id="premioLabel">Premio (objeto) <span class="required">*</span></label>
                    <input type="text" id="premio" name="premio" class="form-input"
                           placeholder="Ej: iPhone 15 Pro Max 256GB" maxlength="80">
                    <span class="form-hint" id="premioHint">Describe exactamente lo que recibe el ganador</span>
                </div>
            </div>
        </div>

        {{-- Sección 2: Boleta --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div>
                    <h3 class="form-section-title">Configuración de la Boleta</h3>
                    <p class="form-section-sub">Cantidad de números y precio por boleta</p>
                </div>
            </div>

            <div class="form-body">
                <div class="form-field">
                    <label class="form-label">Cifras del número <span class="required">*</span></label>
                    <div class="cifras-selector">
                        <label class="cifras-card" id="cifras2">
                            <input type="radio" name="cifras" value="2" checked hidden>
                            <span class="cifras-num">2</span>
                            <span class="cifras-label">cifras</span>
                            <span class="cifras-total">100 números</span>
                            <div class="cifras-range">Del 00 al 99</div>
                        </label>
                        <label class="cifras-card" id="cifras3">
                            <input type="radio" name="cifras" value="3" hidden>
                            <span class="cifras-num">3</span>
                            <span class="cifras-label">cifras</span>
                            <span class="cifras-total">1.000 números</span>
                            <div class="cifras-range">Del 000 al 999</div>
                        </label>
                        <label class="cifras-card" id="cifras4">
                            <input type="radio" name="cifras" value="4" hidden>
                            <span class="cifras-num">4</span>
                            <span class="cifras-label">cifras</span>
                            <span class="cifras-total">10.000 números</span>
                            <div class="cifras-range">Del 0000 al 9999</div>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label class="form-label" for="precio">Precio por número <span class="required">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">$</span>
                            <input type="text" id="precio" name="precio" class="form-input form-input--prefix"
                                   placeholder="0" inputmode="numeric" autocomplete="off">
                        </div>
                        <span class="form-hint">En pesos colombianos (COP)</span>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Ingresos potenciales</label>
                        <div class="ingreso-display">
                            <span class="ingreso-value" id="ingresoValue">$0</span>
                            <span class="ingreso-formula" id="ingresoFormula">100 × $0</span>
                        </div>
                        <span class="form-hint">Si se venden todos los números</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección 3: Sorteo --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <i class="fas fa-dice"></i>
                </div>
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
                                <option value="">Selecciona una lotería</option>
                                <optgroup label="── Lunes">
                                <option value="Lotería del Tolima">Lotería del Tolima</option>
                                <option value="Lotería de Cundinamarca">Lotería de Cundinamarca</option>
                                <option value="Lotería de Nariño">Lotería de Nariño</option>
                                <option value="Lotería de Córdoba">Lotería de Córdoba</option>
                                </optgroup>
                                <optgroup label="── Martes">
                                <option value="Pijao de Oro">Pijao de Oro</option>
                                <option value="La Primera del Vichada">La Primera del Vichada</option>
                                </optgroup>
                                <optgroup label="── Miércoles">
                                <option value="Lotería del Huila">Lotería del Huila</option>
                                <option value="Lotería de Manizales">Lotería de Manizales</option>
                                <option value="Lotería del Valle">Lotería del Valle</option>
                                </optgroup>
                                <optgroup label="── Jueves">
                                <option value="Lotería de Bogotá">Lotería de Bogotá</option>
                                <option value="Lotería del Meta">Lotería del Meta</option>
                                <option value="Lotería del Quindío">Lotería del Quindío</option>
                                </optgroup>
                                <optgroup label="── Viernes">
                                <option value="Lotería de Medellín">Lotería de Medellín</option>
                                <option value="Lotería de Santander">Lotería de Santander</option>
                                <option value="Lotería del Chocó">Lotería del Chocó</option>
                                <option value="Lotería del Risaralda">Lotería del Risaralda</option>
                                <option value="Lotería del Norte">Lotería del Norte</option>
                                </optgroup>
                                <optgroup label="── Sábados">
                                <option value="Lotería de Boyacá">Lotería de Boyacá</option>
                                <option value="Lotería del Cauca">Lotería del Cauca</option>
                                </optgroup>
                                <optgroup label="── Varios días">
                                <option value="Baloto">Baloto (Mié y Sáb)</option>
                                <option value="Chance">Chance (Lun–Sáb)</option>
                                <option value="Super Astro">Super Astro (Diario)</option>
                                </optgroup>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="juega">Día que juega <span class="required">*</span></label>
                        <div class="select-wrap">
                            <select id="juega" name="juega" class="form-select">
                                <option value="">Selecciona un día</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábados">Sábados</option>
                                <option value="Domingos">Domingos</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        <span class="form-hint" id="juegaHint">Se autocompleta al elegir la lotería</span>
                    </div>
                </div>

                <div class="form-field">
                    <label class="form-label" for="fecha">Fecha del sorteo <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-calendar input-icon"></i>
                        <input type="date" id="fecha" name="fecha" class="form-input form-input--icon"
                               min="{{ date('Y-m-d') }}">
                    </div>
                    <span class="form-hint" id="fechaHint">Selecciona primero la lotería para filtrar fechas válidas</span>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="form-actions">
            <a href="{{ route('admin.rifas.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn-primary" id="btnCrear">
                <i class="fas fa-plus"></i> Crear Rifa
            </button>
        </div>

    </form>

    {{-- Preview en vivo --}}
    <div class="create-preview">
        <div class="preview-sticky">
            <h4 class="preview-title"><i class="fas fa-eye"></i> Vista previa</h4>

            <div class="preview-card" id="previewCard">
                <div class="preview-card-top">
                    <div class="preview-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <span class="status-badge status-activa"><span class="status-dot"></span> Activa</span>
                    </div>
                </div>

                <h3 class="preview-nombre" id="pvNombre">Nombre de la rifa</h3>

                <div class="preview-premio-row" id="pvPremioRow">
                    <span class="preview-premio-icon" id="pvPremioIcon"><i class="fas fa-gift"></i></span>
                    <span class="preview-premio-text" id="pvPremio">Premio</span>
                </div>

                <div class="preview-divider"></div>

                <div class="preview-stats">
                    <div class="preview-stat">
                        <span class="preview-stat-label">Precio</span>
                        <span class="preview-stat-val" id="pvPrecio">$—</span>
                    </div>
                    <div class="preview-stat">
                        <span class="preview-stat-label">Total números</span>
                        <span class="preview-stat-val" id="pvTotal">100</span>
                    </div>
                </div>

                <div class="preview-divider"></div>

                <div class="preview-loteria-block">
                    <div class="preview-loteria-name" id="pvLoteria">Selecciona una lotería</div>
                    <div class="preview-loteria-meta">
                        <span id="pvJuega">—</span>
                        &bull;
                        Últimas <span id="pvCifras">2</span> cifras
                    </div>
                </div>

                <div class="preview-divider"></div>

                <div class="preview-fecha-row">
                    <i class="fas fa-calendar"></i>
                    <span id="pvFecha">Fecha del sorteo</span>
                </div>

                <div class="preview-ingreso">
                    <span class="preview-ingreso-label">Ingreso potencial</span>
                    <span class="preview-ingreso-val" id="pvIngreso">$0</span>
                </div>
            </div>

            {{-- Tips --}}
            <div class="preview-tips">
                <div class="tip-item">
                    <i class="fas fa-lightbulb"></i>
                    <span>El precio × total debe superar el valor del premio para ser rentable.</span>
                </div>
                <div class="tip-item" id="tipCifras">
                    <i class="fas fa-info-circle"></i>
                    <span>Con 2 cifras el flyer se puede generar como imagen para compartir.</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/rifas-create.js') }}"></script>
@endpush
