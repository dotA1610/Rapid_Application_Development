<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ARC NEBU-PEN — Breathe Better. Feel Better.</title>
    <meta name="description" content="ARC NEBU-PEN is a modern breathing wellness device designed to support relaxation, focus, and everyday balance through cool, unheated air therapy." />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        teal: {
                            400: '#4DD9A8',
                            500: '#2ECB80',   /* Primary brand teal */
                            600: '#25B870',
                            700: '#1A9E5C',
                        },
                        ocean: {
                            950: '#040D0F',   /* Deepest background */
                            900: '#071419',
                            800: '#0C2028',
                            700: '#112B35',
                            600: '#163644',
                        },
                        charcoal: {
                            900: '#111827',
                            800: '#1C2A30',
                            700: '#253540',
                        }
                    },
                    backgroundImage: {
                        'hero-gradient': 'linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%)',
                        'badge-gradient': 'linear-gradient(180deg, rgba(14,30,40,0.85) 0%, rgba(6,16,22,0.95) 100%)',
                    },
                    boxShadow: {
                        'teal-glow': '0 0 24px rgba(46, 203, 128, 0.25)',
                        'card-dark': '0 4px 24px rgba(0, 0, 0, 0.45)',
                    },
                },
            },
        };
    </script>

    <style>
        /* Smooth scrolling & base */
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }

        /* Hero video overlay */
        .hero-video-overlay {
            background: linear-gradient(
                135deg,
                rgba(4, 13, 15, 0.82) 0%,
                rgba(7, 20, 25, 0.70) 35%,
                rgba(12, 32, 40, 0.55) 65%,
                rgba(22, 54, 68, 0.40) 100%
            );
        }

        /* Nav link hover teal underline */
        .nav-link {
            position: relative;
            transition: color 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: #2ECB80;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #2ECB80; }

        /* Teal CTA button pulse */
        .btn-teal {
            transition: all 0.25s ease;
            box-shadow: 0 0 0 0 rgba(46, 203, 128, 0.4);
        }
        .btn-teal:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(46, 203, 128, 0.35);
        }
        .btn-teal:active { transform: translateY(0); }

        /* Ghost button */
        .btn-ghost {
            transition: all 0.25s ease;
        }
        .btn-ghost:hover {
            background: rgba(46, 203, 128, 0.08);
            border-color: #2ECB80;
            color: #2ECB80;
            transform: translateY(-1px);
        }

        /* Feature badge card */
        .badge-card {
            background: linear-gradient(180deg, rgba(14,30,40,0.80) 0%, rgba(6,16,22,0.92) 100%);
            border: 1px solid rgba(46, 203, 128, 0.12);
            backdrop-filter: blur(12px);
            transition: border-color 0.25s ease, transform 0.25s ease;
        }
        .badge-card:hover {
            border-color: rgba(46, 203, 128, 0.35);
            transform: translateY(-2px);
        }

        /* Device image floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
        .device-float { animation: float 5s ease-in-out infinite; }

        /* Fade-in on load */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up           { animation: fadeUp 0.7s ease both; }
        .fade-up-delay-1   { animation: fadeUp 0.7s 0.15s ease both; }
        .fade-up-delay-2   { animation: fadeUp 0.7s 0.30s ease both; }
        .fade-up-delay-3   { animation: fadeUp 0.7s 0.45s ease both; }
        .fade-up-delay-4   { animation: fadeUp 0.7s 0.60s ease both; }
    </style>
</head>

<body class="bg-ocean-950 text-white antialiased overflow-x-hidden">

    <?php require __DIR__ . '/../includes/navbar.php'; ?>
    <!-- ═══════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════ -->
    <section id="hero"
             class="relative min-h-screen flex flex-col justify-between overflow-hidden pt-20"
             style="background: linear-gradient(135deg, #040D0F 0%, #071419 25%, #0C2028 55%, #112B35 80%, #163644 100%);">

        <!-- ── Background Video (placeholder) ─────────────────── -->
        <!--
            PRODUCTION: Replace this <div> with a <video> element:
            <video autoplay muted loop playsinline
                   class="absolute inset-0 w-full h-full object-cover opacity-40">
                <source src="assets/videos/ocean-hero.mp4" type="video/mp4" />
            </video>
        -->
        <div id="hero-video-placeholder"
             class="absolute inset-0 w-full h-full"
             aria-hidden="true">
            <!-- Ocean texture simulation using layered radial gradients -->
            <div class="absolute inset-0"
                 style="background:
                    radial-gradient(ellipse 90% 60% at 70% 80%, rgba(22,54,68,0.6) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 40% at 30% 90%, rgba(46,203,128,0.04) 0%, transparent 50%),
                    radial-gradient(ellipse 100% 50% at 50% 100%, rgba(12,32,40,0.9) 0%, transparent 70%);"></div>
            <!-- Subtle animated shimmer lines (wave simulation) -->
            <div class="absolute bottom-0 left-0 right-0 h-56 opacity-20"
                 style="background: repeating-linear-gradient(
                    180deg,
                    transparent 0px,
                    transparent 28px,
                    rgba(46,203,128,0.08) 28px,
                    rgba(46,203,128,0.08) 29px
                 );"></div>
        </div>

        <!-- ── Gradient overlay on video ─────────────────────── -->
        <div class="hero-video-overlay absolute inset-0" aria-hidden="true"></div>

        <!-- ── Hero content ──────────────────────────────────── -->
        <div class="relative z-10 max-w-7xl mx-auto w-full px-6 lg:px-12 flex-1 flex items-center">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center w-full py-16 lg:py-24">

                <!-- Left: Copy -->
                <div class="space-y-7">
                    <!-- Main headline -->
                    <div class="fade-up">
                        <h1 class="text-5xl lg:text-6xl xl:text-7xl font-extrabold leading-[1.05] tracking-tight">
                            <span class="text-white block">Breathe Better.</span>
                            <span class="block" style="color: #2ECB80;">Feel Better.</span>
                        </h1>
                    </div>

                    <!-- Sub-copy -->
                    <p class="fade-up-delay-1 text-base lg:text-lg text-gray-300 leading-relaxed max-w-md font-light">
                        ARC NEBU-PEN is a modern breathing wellness device designed to support
                        relaxation, focus, and everyday balance.
                    </p>

                    <!-- CTA buttons -->
                    <div class="fade-up-delay-2 flex flex-wrap gap-4">
                        <a href="index.php?page=profiles"
                           id="cta-starter-kit"
                           class="btn-teal inline-flex items-center gap-2 px-7 py-3.5 rounded-full
                                  font-semibold text-sm text-ocean-950"
                           style="background: #2ECB80;">
                            Get Starter Kit
                        </a>
                        <a href="index.php?page=science"
                           id="cta-learn-more"
                           class="btn-ghost inline-flex items-center gap-2 px-7 py-3.5 rounded-full
                                  font-semibold text-sm text-white border border-white/30">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Right: Device image -->
                <div class="fade-up-delay-3 flex justify-center lg:justify-end">
                    <div class="relative device-float">
                        <!--
                            PRODUCTION: Replace with:
                            <img src="assets/images/nebupen-hero.png"
                                 alt="ARC NEBU-PEN breathing wellness device"
                                 class="w-56 lg:w-72 xl:w-80 h-auto object-contain drop-shadow-2xl"
                                 width="320" height="480" />
                        -->
                        <!-- Device Image -->
                        <div class="relative w-52 lg:w-64 xl:w-72 mx-auto">
                            <!-- Glow behind device -->
                            <div class="absolute inset-0 rounded-full blur-3xl opacity-20"
                                 style="background: radial-gradient(circle, #2ECB80 0%, transparent 70%);
                                        transform: scale(1.2);"></div>
                            <img src="assets/images/nebu_pen.png" alt="ARC Nebu-Pen" class="img-fluid mx-auto d-block hero-product-image relative z-10 w-full" style="max-height: 500px; object-fit: contain; mix-blend-mode: screen;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Feature Badge Bar (bottom of hero) ────────────── -->
        <div class="relative z-10 w-full"
             style="background: linear-gradient(180deg, transparent 0%, rgba(4,13,15,0.6) 100%);">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 pb-8 pt-4">
                <div class="fade-up-delay-4 grid grid-cols-2 lg:grid-cols-4 gap-3">

                    <!-- Badge 1: Unheated Cool Air -->
                    <div class="badge-card rounded-2xl px-5 py-4 flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                             style="background: rgba(46,203,128,0.12);">
                            <!-- Snowflake / cool air icon -->
                            <svg class="w-5 h-5" style="color: #2ECB80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M19.07 4.93L4.93 19.07"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold leading-tight">Unheated</p>
                            <p class="text-gray-400 text-xs mt-0.5">Cool Air</p>
                        </div>
                    </div>

                    <!-- Badge 2: Portable Anywhere -->
                    <div class="badge-card rounded-2xl px-5 py-4 flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                             style="background: rgba(46,203,128,0.12);">
                            <!-- Location pin icon -->
                            <svg class="w-5 h-5" style="color: #2ECB80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold leading-tight">Portable</p>
                            <p class="text-gray-400 text-xs mt-0.5">Anywhere</p>
                        </div>
                    </div>

                    <!-- Badge 3: Replaceable Cartridges -->
                    <div class="badge-card rounded-2xl px-5 py-4 flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                             style="background: rgba(46,203,128,0.12);">
                            <!-- Refresh / cycle icon -->
                            <svg class="w-5 h-5" style="color: #2ECB80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold leading-tight">Replaceable</p>
                            <p class="text-gray-400 text-xs mt-0.5">Cartridges</p>
                        </div>
                    </div>

                    <!-- Badge 4: Wellness Everyday -->
                    <div class="badge-card rounded-2xl px-5 py-4 flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                             style="background: rgba(46,203,128,0.12);">
                            <!-- Heart / wellness icon -->
                            <svg class="w-5 h-5" style="color: #2ECB80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold leading-tight">Wellness</p>
                            <p class="text-gray-400 text-xs mt-0.5">Everyday</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </section><!-- /hero -->

