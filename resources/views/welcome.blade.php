<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="La plataforma más confiable de rifas en línea. Sorteos 100% transparentes, premios reales y pagos seguros.">
    <title>{{ config('app.name', 'Raffly') }} – Participa y Gana Premios Increíbles</title>

    {{-- Tipografía --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Íconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    {{-- CSS de la landing --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles-landing.css') }}">

    {{-- Alpine.js (desde Vite build si existe, o CDN como fallback) --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif
</head>

<body>
<div x-data="{
    nav: false,
    mob: false,
    init() {
        window.addEventListener('scroll', () => { this.nav = window.scrollY > 50; }, { passive: true });
    }
}">

{{-- ══════════════ NAVBAR ══════════════ --}}
<nav class="navbar" :class="{ 'scrolled': nav }">
    <div class="container">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <div class="logo-box"><i class="fas fa-ticket"></i></div>
                {{ config('app.name', 'Raffly') }}
            </a>

            <ul class="nav-links">
                <li><a href="#rifas">Rifas Activas</a></li>
                <li><a href="#como-funciona">Cómo Funciona</a></li>
                <li><a href="#ganadores">Ganadores</a></li>
                <li><a href="#estadisticas">Estadísticas</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-register">
                        <i class="fas fa-th-large"></i> Mi Panel
                    </a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="nav-login">Iniciar Sesión</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-register">Registrarse</a>
                    @endif
                @endauth
                <button class="nav-toggle" @click="mob = true" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

{{-- MOBILE MENU --}}
<div class="mobile-overlay" :class="{ 'open': mob }">
    <button class="mobile-close" @click="mob = false" aria-label="Cerrar menú">
        <i class="fas fa-times"></i>
    </button>
    <a href="#rifas"         @click="mob = false">Rifas Activas</a>
    <a href="#como-funciona" @click="mob = false">Cómo Funciona</a>
    <a href="#ganadores"     @click="mob = false">Ganadores</a>
    <a href="#estadisticas"  @click="mob = false">Estadísticas</a>
    <div class="mobile-divider"></div>
    @auth
        <a href="{{ url('/dashboard') }}" style="background:var(--primary);border-radius:.5rem;font-weight:700;">Mi Panel</a>
    @else
        <a href="{{ route('login') }}" style="border:2px solid rgba(255,255,255,.3);border-radius:.5rem;">Iniciar Sesión</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" style="background:var(--secondary);border-radius:.5rem;font-weight:700;">Registrarse Gratis</a>
        @endif
    @endauth
</div>

{{-- ══════════════ HERO ══════════════ --}}
<section class="hero">
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-dots"></div>

    <div class="container">
        <div class="hero-grid">

            {{-- Texto --}}
            <div class="hero-content">
                <div class="hero-tag">
                    <i class="fas fa-star"></i>
                    Plataforma #1 de Rifas en Línea
                </div>
                <h1>Participa y Gana <span class="gold">Premios Increíbles</span> Cada Semana</h1>
                <p>Sorteos 100&nbsp;% transparentes y verificables. Elige tu número de suerte, realiza el pago y espera el gran sorteo. ¡Tu premio podría estar a un número de distancia!</p>

                <div class="hero-btns">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-gold">
                            <i class="fas fa-rocket"></i> Participar Ahora
                        </a>
                    @endif
                    <a href="#rifas" class="btn btn-outline-white">
                        <i class="fas fa-eye"></i> Ver Rifas Activas
                    </a>
                </div>

                <div class="hero-trust">
                    <div class="trust-pill"><span class="dot"></span> Sorteos Verificados</div>
                    <div class="trust-pill"><span class="dot"></span> Pagos Seguros</div>
                    <div class="trust-pill"><span class="dot"></span> Resultados en Vivo</div>
                </div>
            </div>

            {{-- Tarjetas flotantes --}}
            <div class="hero-visual hero-cards">
                @php $previewRifas = $rifasActivas->take(3); @endphp
                @if($previewRifas->isNotEmpty())
                    @foreach($previewRifas as $pr)
                    <div class="fc">
                        <div class="fc-top">
                            <div class="fc-icon" style="background:linear-gradient(135deg,var(--primary),var(--secondary))">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div>
                                <div class="fc-name">{{ Str::limit($pr->nombre, 28) }}</div>
                                <div class="fc-price">${{ number_format($pr->precio_numero) }} / número</div>
                            </div>
                        </div>
                        <div class="fc-bar"><div class="fc-fill" style="width:{{ $pr->porcentaje_vendido }}%"></div></div>
                        <div class="fc-labels">
                            <span>{{ $pr->numeros_vendidos }} vendidos</span>
                            <span>{{ $pr->numeros_disponibles }} disponibles</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="fc">
                        <div class="fc-top">
                            <div class="fc-icon" style="background:linear-gradient(135deg,var(--primary),var(--secondary))"><i class="fas fa-mobile-alt"></i></div>
                            <div><div class="fc-name">iPhone 15 Pro Max</div><div class="fc-price">$5,000 / número</div></div>
                        </div>
                        <div class="fc-bar"><div class="fc-fill" style="width:73%"></div></div>
                        <div class="fc-labels"><span>73 vendidos</span><span>27 disponibles</span></div>
                    </div>
                    <div class="fc">
                        <div class="fc-top">
                            <div class="fc-icon" style="background:linear-gradient(135deg,#059669,#10B981)"><i class="fas fa-laptop"></i></div>
                            <div><div class="fc-name">MacBook Pro M3</div><div class="fc-price">$8,000 / número</div></div>
                        </div>
                        <div class="fc-bar"><div class="fc-fill" style="width:45%"></div></div>
                        <div class="fc-labels"><span>36 vendidos</span><span>44 disponibles</span></div>
                    </div>
                    <div class="fc">
                        <div class="fc-top">
                            <div class="fc-icon" style="background:linear-gradient(135deg,#DC2626,#EF4444)"><i class="fas fa-gamepad"></i></div>
                            <div><div class="fc-name">PlayStation 5</div><div class="fc-price">$3,000 / número</div></div>
                        </div>
                        <div class="fc-bar"><div class="fc-fill" style="width:59%"></div></div>
                        <div class="fc-labels"><span>89 vendidos</span><span>61 disponibles</span></div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>

{{-- ══════════════ RIFAS ACTIVAS ══════════════ --}}
<section class="section" id="rifas">
    <div class="container">
        <div class="section-header fade-up">
            <div class="badge badge-purple"><i class="fas fa-fire"></i> En Curso</div>
            <h2 class="section-title">Rifas Activas</h2>
            <p class="section-sub">Elige tu rifa favorita y adquiere tus números antes de que se agoten. ¡Los premios son reales y los sorteos verificables!</p>
        </div>

        <div class="rifas-grid">
            @forelse($rifasActivas as $index => $rifa)
            <div class="rifa-card fade-up delay-{{ min($index + 1, 5) }}">
                @if($rifa->imagen)
                    <img class="rifa-thumb" src="{{ $rifa->imagen }}" alt="{{ $rifa->nombre }}" loading="lazy">
                @else
                    <div class="rifa-thumb-ph"><i class="fas fa-coins"></i></div>
                @endif

                <div class="rifa-body">
                    <span class="rifa-status"><span class="dot"></span> Activa</span>
                    <div class="rifa-name">{{ $rifa->nombre }}</div>
                    @if($rifa->premio_descripcion)
                        <div class="rifa-premio">
                            <i class="fas fa-trophy" style="color:var(--secondary);margin-right:.3rem"></i>
                            {{ $rifa->premio_descripcion }}
                        </div>
                    @endif
                    <div class="rifa-price">${{ number_format($rifa->precio_numero) }} <small>/ número</small></div>

                    <div class="progress-wrap">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $rifa->porcentaje_vendido }}%"></div>
                        </div>
                        <div class="progress-labels">
                            <span>{{ $rifa->numeros_vendidos }} vendidos ({{ $rifa->porcentaje_vendido }}%)</span>
                            <span>{{ $rifa->numeros_disponibles }} disp.</span>
                        </div>
                    </div>

                    <div class="rifa-date">
                        <i class="fas fa-calendar-alt"></i>
                        Sorteo: {{ $rifa->fecha_sorteo->format('d/m/Y') }}
                    </div>
                </div>

                <div class="rifa-footer">
                    <a href="{{ route('register') }}" class="rifa-btn">
                        <i class="fas fa-ticket-alt"></i> Ver Detalles
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state fade-up">
                <div class="es-icon"><i class="fas fa-ticket-alt"></i></div>
                <h3>Próximamente nuevas rifas</h3>
                <p>Estamos preparando premios increíbles. ¡Regístrate para ser el primero en enterarte!</p>
                <a href="{{ route('register') }}" class="btn btn-purple">
                    <i class="fas fa-bell"></i> Notificarme
                </a>
            </div>
            @endforelse
        </div>

        @if($rifasActivas->count() >= 6)
        <div style="text-align:center;margin-top:2.5rem;">
            <a href="{{ route('register') }}" class="btn btn-outline-purple">
                <i class="fas fa-th-list"></i> Ver Todas las Rifas
            </a>
        </div>
        @endif
    </div>
</section>

{{-- ══════════════ BENEFICIOS ══════════════ --}}
<section class="section beneficios-bg" id="beneficios">
    <div class="container">
        <div class="section-header fade-up">
            <div class="badge badge-purple"><i class="fas fa-shield-alt"></i> Por Qué Elegirnos</div>
            <h2 class="section-title">Tu Confianza es Nuestra Prioridad</h2>
            <p class="section-sub">Diseñamos cada aspecto de la plataforma para que participes con total seguridad, transparencia y tranquilidad.</p>
        </div>

        <div class="ben-grid">
            <div class="ben-card fade-up delay-1">
                <div class="ben-icon" style="background:#EDE9FE;color:var(--primary)"><i class="fas fa-eye"></i></div>
                <div class="ben-title">Sorteos Transparentes</div>
                <p class="ben-desc">Todos los sorteos se realizan en vivo y son 100&nbsp;% verificables. Los resultados se publican inmediatamente.</p>
            </div>
            <div class="ben-card fade-up delay-2">
                <div class="ben-icon" style="background:#FEF3C7;color:#D97706"><i class="fas fa-lock"></i></div>
                <div class="ben-title">Pagos 100&nbsp;% Seguros</div>
                <p class="ben-desc">Información protegida con cifrado bancario. Múltiples métodos de pago disponibles para tu comodidad.</p>
            </div>
            <div class="ben-card fade-up delay-3">
                <div class="ben-icon" style="background:#ECFDF5;color:#059669"><i class="fas fa-check-double"></i></div>
                <div class="ben-title">Resultados Verificables</div>
                <p class="ben-desc">Consulta el historial completo de todos los sorteos realizados. Total transparencia en cada resultado.</p>
            </div>
            <div class="ben-card fade-up delay-1">
                <div class="ben-icon" style="background:#FFF1F2;color:#E11D48"><i class="fas fa-headset"></i></div>
                <div class="ben-title">Atención al Cliente</div>
                <p class="ben-desc">Nuestro equipo está disponible para resolver cualquier consulta. Respuesta rápida y efectiva siempre.</p>
            </div>
            <div class="ben-card fade-up delay-2">
                <div class="ben-icon" style="background:#EFF6FF;color:#2563EB"><i class="fas fa-clock"></i></div>
                <div class="ben-title">Disponible 24/7</div>
                <p class="ben-desc">Participa cuando quieras, donde quieras. La plataforma nunca cierra, tu oportunidad siempre está disponible.</p>
            </div>
            <div class="ben-card fade-up delay-3">
                <div class="ben-icon" style="background:#F0FDF4;color:#16A34A"><i class="fas fa-award"></i></div>
                <div class="ben-title">Premios Garantizados</div>
                <p class="ben-desc">Cada rifa tiene un ganador garantizado. Entregamos los premios dentro de los plazos establecidos, siempre.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════ CÓMO FUNCIONA ══════════════ --}}
<section class="section" id="como-funciona">
    <div class="container">
        <div class="section-header fade-up">
            <div class="badge badge-purple"><i class="fas fa-map"></i> Guía Rápida</div>
            <h2 class="section-title">¿Cómo Participar?</h2>
            <p class="section-sub">En solo 5 pasos sencillos puedes estar participando en tu primera rifa y tener la oportunidad de ganar premios increíbles.</p>
        </div>

        <div class="steps-grid">
            <div class="steps-connector"></div>
            <div class="step fade-up delay-1">
                <div class="step-num">1</div>
                <div class="step-title">Crea tu Cuenta</div>
                <p class="step-desc">Regístrate gratis en segundos. Solo necesitas tu correo electrónico y una contraseña.</p>
            </div>
            <div class="step fade-up delay-2">
                <div class="step-num">2</div>
                <div class="step-title">Elige una Rifa</div>
                <p class="step-desc">Explora las rifas activas y selecciona el premio que más te emocione ganar.</p>
            </div>
            <div class="step fade-up delay-3">
                <div class="step-num">3</div>
                <div class="step-title">Elige tus Números</div>
                <p class="step-desc">Selecciona uno o varios números de suerte. ¡Más números, más posibilidades de ganar!</p>
            </div>
            <div class="step fade-up delay-4">
                <div class="step-num">4</div>
                <div class="step-title">Realiza el Pago</div>
                <p class="step-desc">Paga de forma segura por transferencia, tarjeta u otros métodos disponibles.</p>
            </div>
            <div class="step fade-up delay-5">
                <div class="step-num">5</div>
                <div class="step-title">¡Espera el Sorteo!</div>
                <p class="step-desc">El día del sorteo te notificamos. Si ganas, nos comunicamos contigo de inmediato.</p>
            </div>
        </div>

        <div style="text-align:center;margin-top:3rem;" class="fade-up">
            <a href="{{ route('register') }}" class="btn btn-purple">
                <i class="fas fa-play-circle"></i> Comenzar Ahora — Es Gratis
            </a>
        </div>
    </div>
</section>

{{-- ══════════════ GANADORES ══════════════ --}}
<section class="section ganadores-bg" id="ganadores">
    <div class="container">
        <div class="section-header fade-up">
            <div class="badge badge-gold"><i class="fas fa-trophy"></i> Hall de la Fama</div>
            <h2 class="section-title">Ganadores Recientes</h2>
            <p class="section-sub">Personas reales, premios reales. Conoce a algunos de nuestros ganadores más recientes y únete a la lista.</p>
        </div>

        <div class="win-grid">
            @forelse($ganadoresRecientes as $index => $rifa)
            <div class="win-card fade-up delay-{{ $index + 1 }}">
                @if($rifa->imagen)
                    <img class="win-thumb" src="{{ $rifa->imagen }}" alt="{{ $rifa->nombre }}">
                @else
                    <div class="win-thumb-ph"><i class="fas fa-trophy"></i></div>
                @endif

                <div class="win-info">
                    <div class="win-name">
                        <i class="fas fa-user-circle" style="color:var(--primary-light);margin-right:.35rem"></i>
                        {{ $rifa->ganador_participacion?->user?->name
                            ?? $rifa->ganador_participacion?->nombre_participante
                            ?? 'Ganador Anónimo' }}
                    </div>
                    <div class="win-premio">
                        <i class="fas fa-star" style="margin-right:.25rem"></i>{{ $rifa->nombre }}
                    </div>
                    <div>
                        <span class="win-numero">
                            <i class="fas fa-ticket-alt"></i> Número #{{ $rifa->numero_ganador }}
                        </span>
                    </div>
                    <div class="win-fecha">
                        <i class="fas fa-calendar-check" style="margin-right:.3rem"></i>
                        {{ $rifa->fecha_sorteo->format('d/m/Y') }}
                    </div>
                    @if($rifa->monto_premio)
                        <div class="win-monto">
                            <i class="fas fa-coins" style="margin-right:.3rem"></i>
                            Premio: ${{ number_format($rifa->monto_premio) }}
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="win-empty fade-up">
                <i class="fas fa-trophy"></i>
                <p>¡Los primeros ganadores aparecerán aquí pronto! Participa y sé el primero en ganar.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ══════════════ ESTADÍSTICAS ══════════════ --}}
<section class="section" id="estadisticas">
    <div class="container">
        <div class="section-header fade-up">
            <div class="badge badge-purple"><i class="fas fa-chart-line"></i> Números que Hablan</div>
            <h2 class="section-title">Una Plataforma en la que Confían Miles</h2>
            <p class="section-sub">Nuestros resultados reflejan el compromiso que tenemos con cada participante. Transparencia desde el primer día.</p>
        </div>

        <div class="stats-grid" id="statsGrid">
            <div class="stat-card fade-up delay-1">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-num" data-target="{{ $stats['rifas_realizadas'] }}">0</div>
                <div class="stat-label">Rifas Realizadas</div>
            </div>
            <div class="stat-card fade-up delay-2">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-num" data-target="{{ $stats['usuarios_registrados'] }}">0</div>
                <div class="stat-label">Usuarios Registrados</div>
            </div>
            <div class="stat-card fade-up delay-3">
                <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                <div class="stat-num" data-target="{{ $stats['premios_entregados'] }}">0</div>
                <div class="stat-label">Premios Entregados</div>
            </div>
            <div class="stat-card fade-up delay-4">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-num" data-target="{{ $stats['monto_total'] }}" data-prefix="$">$0</div>
                <div class="stat-label">En Premios Entregados</div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════ CTA ══════════════ --}}
<section class="cta-bg">
    <div class="container">
        <div class="cta-inner fade-up">
            <div class="badge badge-white" style="margin-bottom:1.5rem">
                <i class="fas fa-bolt"></i> ¡Tu momento es ahora!
            </div>
            <h2>¿Listo para Ganar tu Premio?</h2>
            <p>Únete a miles de participantes y aumenta tus posibilidades de ganar premios increíbles. El registro es completamente gratuito y toma menos de un minuto.</p>
            <div class="cta-btns">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-gold">
                        <i class="fas fa-user-plus"></i> Crear Cuenta Gratis
                    </a>
                @endif
                <a href="#rifas" class="btn btn-outline-white">
                    <i class="fas fa-search"></i> Ver Rifas Disponibles
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════ FOOTER ══════════════ --}}
<footer class="footer-bg">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="footer-brand-logo">
                        <div class="logo-box"><i class="fas fa-ticket"></i></div>
                        {{ config('app.name', 'Raffly') }}
                    </a>
                    <p>La plataforma más confiable para participar en rifas en línea. Sorteos transparentes, premios reales y atención personalizada en cada paso.</p>
                    <div class="socials">
                        <a href="#" class="social-btn" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="social-btn" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="social-btn" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Plataforma</h4>
                    <ul class="footer-links">
                        <li><a href="#rifas">Rifas Activas</a></li>
                        <li><a href="#como-funciona">Cómo Funciona</a></li>
                        <li><a href="#ganadores">Ganadores</a></li>
                        <li><a href="#estadisticas">Estadísticas</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">Registrarse</a></li>
                        @endif
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                        @endif
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul class="footer-links">
                        <li><a href="#">Términos y Condiciones</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                        <li><a href="#">Política de Cookies</a></li>
                        <li><a href="#">Reglamento de Sorteos</a></li>
                        <li><a href="#">Preguntas Frecuentes</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contacto</h4>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i> soporte@raffly.com
                    </div>
                    <div class="footer-contact-item">
                        <i class="fab fa-whatsapp"></i> +57 300 000 0000
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-clock"></i> Lun – Vie: 8am – 6pm
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i> Colombia
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <span>© {{ date('Y') }} {{ config('app.name', 'Raffly') }}. Todos los derechos reservados.</span>
                <div class="footer-bottom-links">
                    <a href="#">Términos</a>
                    <a href="#">Privacidad</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </div>
</footer>

</div>{{-- /x-data --}}

{{-- JS de la landing --}}
<script src="{{ asset('assets/js/js-landing.js') }}" defer></script>
</body>
</html>
