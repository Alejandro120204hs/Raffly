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
    <link rel="stylesheet" href="{{ asset('assets/css/responsive/landing-responsive.css') }}">

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
<nav class="navbar" x-bind:class="{ 'scrolled': nav }">
    <div class="container">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">
             
                {{ config('app.name', 'Raffly') }}
            </a>

            <ul class="nav-links">
                <li><a href="#primeros">Sé el Primero</a></li>
                 <li><a href="#faq">Preguntas</a></li>
                <li><a href="#beneficios">Confianza</a></li>
                <li><a href="#como-funciona">Cómo Funciona</a></li>
               
            </ul>

            <div class="nav-actions">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('cliente.dashboard') }}" class="nav-register">
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
                <button class="nav-toggle" x-on:click="mob = true" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

{{-- MOBILE MENU --}}
<div class="mobile-overlay" x-bind:class="{ 'open': mob }">
    <button class="mobile-close" x-on:click="mob = false" aria-label="Cerrar menú">
        <i class="fas fa-times"></i>
    </button>
    <a href="#primeros"      x-on:click="mob = false">Sé el Primero</a>
    <a href="#beneficios"    x-on:click="mob = false">Confianza</a>
    <a href="#como-funciona" x-on:click="mob = false">Cómo Funciona</a>
    <a href="#faq"           x-on:click="mob = false">Preguntas</a>
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
                <div class="fc"><div class="fc-motto-only">🏆 Las mejores rifas, los mejores premios</div></div>
                <div class="fc"><div class="fc-motto-only">🔥 Rifas exclusivas · Premios que cambian vidas</div></div>
                <div class="fc"><div class="fc-motto-only">🎯 Tu próximo premio está a un número de distancia</div></div>
            </div>{{-- /.hero-cards --}}

        </div>
    </div>
</section>

{{-- ══════════════ SÉ DE LOS PRIMEROS ══════════════ --}}
<section class="section early-section" id="primeros">
    <div class="container">
        <div class="early-wrap fade-up">
            <div class="badge badge-purple"><i class="fas fa-star"></i> Acceso Anticipado</div>
            <h2 class="section-title">Sé de los <span class="gold">Primeros</span> en Participar</h2>
            <p class="section-sub">Únete a Rafflys y disfruta de una experiencia fácil, segura y transparente. Regístrate gratis y recibe novedades sobre nuevos sorteos, promociones y oportunidades para participar.</p>

            <div class="early-perks">
                <div class="early-perk">
                    <div class="early-perk-icon"><i class="fas fa-bell"></i></div>
                    <span>Notificación inmediata cuando abramos nuevas rifas</span>
                </div>
                <div class="early-perk">
                    <div class="early-perk-icon"><i class="fas fa-tag"></i></div>
                    <span>Acceso a promociones exclusivas de lanzamiento</span>
                </div>
                <div class="early-perk">
                    <div class="early-perk-icon"><i class="fas fa-trophy"></i></div>
                    <span>Participa en los primeros sorteos con premios reales</span>
                </div>
            </div>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-gold">
                <i class="fas fa-rocket"></i> Registrarme Ahora — Es Gratis
            </a>
            @endif
            
        </div>
    </div>
</section>

{{-- ══════════════ FAQ ══════════════ --}}
<section class="section faq-section" id="faq">
    <div class="container">
        <div class="section-header fade-up">
            <div class="badge badge-purple"><i class="fas fa-question-circle"></i> FAQ</div>
            <h2 class="section-title">Preguntas Frecuentes</h2>
            <p class="section-sub">Todo lo que necesitas saber antes de participar.</p>
        </div>

        <div class="faq-list fade-up" x-data="{ open: null }">

            <div class="faq-item" x-bind:class="open === 1 ? 'faq-open' : ''">
                <button class="faq-q" x-on:click="open = open === 1 ? null : 1">
                    <span>¿Cómo sé que el sorteo es real y transparente?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </button>
                <div class="faq-a">Todos nuestros sorteos se realizan de manera transparente y segura. Los números ganadores son determinados mediante la lotería correspondiente a cada sorteo, garantizando un proceso confiable y verificable. Los resultados quedan publicados permanentemente en la plataforma para que cualquier persona pueda consultarlos.</div>
            </div>

            <div class="faq-item" x-bind:class="open === 2 ? 'faq-open' : ''">
                <button class="faq-q" x-on:click="open = open === 2 ? null : 2">
                    <span>¿Cómo recibo mi premio si gano?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </button>
                <div class="faq-a">Una vez finalizado el sorteo, contactamos directamente al ganador con los datos de registro. Coordinamos la entrega según el tipo de premio: envío a domicilio, transferencia bancaria o entrega presencial.</div>
            </div>

            <div class="faq-item" x-bind:class="open === 3 ? 'faq-open' : ''">
                <button class="faq-q" x-on:click="open = open === 3 ? null : 3">
                    <span>¿Qué métodos de pago aceptan?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </button>
                <div class="faq-a">Aceptamos todo tipo de métodos de pago para que puedas participar de forma fácil y segura. Todos los pagos son verificados antes de confirmar tu participación en el sorteo, garantizando un proceso confiable y transparente.</div>
            </div>

            <div class="faq-item" x-bind:class="open === 4 ? 'faq-open' : ''">
                <button class="faq-q" x-on:click="open = open === 4 ? null : 4">
                    <span>¿Puedo comprar varios números en la misma rifa?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </button>
                <div class="faq-a">¡Sí! Puedes adquirir todos los números que quieras en una misma rifa, lo que aumenta tus probabilidades de ganar. Cada número tiene un costo fijo y no hay límite de compra por persona.</div>
            </div>

            <div class="faq-item" x-bind:class="open === 5 ? 'faq-open' : ''">
                <button class="faq-q" x-on:click="open = open === 5 ? null : 5">
                    <span>¿Cuándo se realiza el sorteo?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </button>
                <div class="faq-a">Cada rifa tiene una fecha de sorteo establecida junto con la lotería asociada. El resultado del ganador será determinado por los resultados oficiales de dicha lotería, garantizando un proceso transparente y confiable. Los resultados quedarán publicados en la plataforma para su consulta.</div>
            </div>

            <div class="faq-item" x-bind:class="open === 6 ? 'faq-open' : ''">
                <button class="faq-q" x-on:click="open = open === 6 ? null : 6">
                    <span>¿Qué pasa si no se venden todos los números?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </button>
                <div class="faq-a">Si al llegar la fecha de sorteo no se han vendido todos los números, el sorteo se realiza igualmente entre los participantes registrados. El premio siempre tiene un ganador garantizado.</div>
            </div>

        </div>
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
                <div class="win-thumb-ph"><i class="fas fa-trophy"></i></div>

                <div class="win-info">
                    <div class="win-name">
                        <i class="fas fa-user-circle" style="color:var(--primary-light);margin-right:.35rem"></i>
                        {{ $rifa['ganador'] }}
                    </div>
                    <div class="win-premio">
                        <i class="fas fa-star" style="margin-right:.25rem"></i>{{ $rifa['nombre'] }}
                    </div>
                    <div>
                        <span class="win-numero">
                            <i class="fas fa-ticket-alt"></i> Número #{{ $rifa['numero'] }}
                        </span>
                    </div>
                    <div class="win-fecha">
                        <i class="fas fa-calendar-check" style="margin-right:.3rem"></i>
                        {{ $rifa['fecha'] }}
                    </div>
                    <div class="win-monto">
                        <i class="fas fa-coins" style="margin-right:.3rem"></i>
                        Premio: ${{ number_format($rifa['monto'], 0, ',', '.') }}
                    </div>
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
                        <i class="fas fa-envelope"></i> soporteraffly@gmail.com
                    </div>
                    <div class="footer-contact-item">
                        <i class="fab fa-whatsapp"></i> +57 321 3919596
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-clock"></i> Dom – Dom: 7am – 11pm
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
