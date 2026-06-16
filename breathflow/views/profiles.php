<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sensory Profiles — ARC NEBU-PEN</title>
    <meta name="description" content="Choose your favorite sensations. Elevate every breath with our sensory profiles." />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        teal:  { 400: '#4DD9A8', 500: '#2ECB80', 600: '#25B870' },
                        ocean: { 950: '#040D0F', 900: '#071419', 800: '#0C2028', 700: '#112B35', 600: '#163644' },
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Full-page ocean gradient bg ── */
        .page-bg {
            background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%);
        }

        /* ── Glassmorphism Product Card ── */
        .profile-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .profile-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(46, 203, 128, 0.3);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(46,203,128,0.1);
        }

        /* ── Teal CTA button ── */
        .btn-teal {
            background: #2ECB80;
            color: #040D0F;
            transition: all 0.25s ease;
        }
        .btn-teal:hover {
            background: #4DD9A8;
            box-shadow: 0 4px 15px rgba(46,203,128,0.4);
            transform: translateY(-1px);
        }

        /* Nav link hover */
        .nav-link { transition: color 0.2s ease; }
        .nav-link:hover { color: #2ECB80; }
        
        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/header.php'; ?>

    <!-- ═══════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════ -->
    <main class="flex-1 px-6 lg:px-16 py-16 lg:py-24 max-w-7xl mx-auto w-full">
        
        <div class="text-center mb-16 fade-in-up" style="animation-delay: 0.1s;">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Sensory <span class="text-teal-500">Profiles</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Choose your favorite sensations. Elevate every breath with our carefully crafted aromatic blends.
            </p>
        </div>

        <!-- Profiles Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- Mint Profile -->
            <div class="profile-card rounded-3xl overflow-hidden flex flex-col fade-in-up" style="animation-delay: 0.2s;">
                <!-- Image Placeholder -->
                <div class="h-48 w-full bg-gradient-to-br from-green-900/40 to-green-800/20 relative flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span class="absolute bottom-4 right-4 text-xs font-bold tracking-widest text-green-400/50 uppercase">Mint.png</span>
                </div>
                
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-white mb-2">Mint</h3>
                    <p class="text-teal-400 text-sm font-semibold mb-3">Cooling & Refreshing</p>
                    <p class="text-gray-400 text-sm flex-1">
                        Awakens your senses and improves focus. The perfect crisp sensation for morning clarity or mid-day energy.
                    </p>
                    
                    <button class="btn-teal w-full mt-6 py-3 rounded-xl font-semibold text-sm tracking-wide">
                        Select Profile
                    </button>
                </div>
            </div>

            <!-- Berry Profile -->
            <div class="profile-card rounded-3xl overflow-hidden flex flex-col fade-in-up" style="animation-delay: 0.3s;">
                <!-- Image Placeholder -->
                <div class="h-48 w-full bg-gradient-to-br from-purple-900/40 to-pink-900/20 relative flex items-center justify-center">
                    <svg class="w-12 h-12 text-pink-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="absolute bottom-4 right-4 text-xs font-bold tracking-widest text-pink-400/50 uppercase">Berry.png</span>
                </div>
                
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-white mb-2">Berry</h3>
                    <p class="text-teal-400 text-sm font-semibold mb-3">Smooth & Soothing</p>
                    <p class="text-gray-400 text-sm flex-1">
                        A gentle blend for everyday balance. Sweet notes designed to comfort and ground your nervous system.
                    </p>
                    
                    <button class="btn-teal w-full mt-6 py-3 rounded-xl font-semibold text-sm tracking-wide">
                        Select Profile
                    </button>
                </div>
            </div>

            <!-- Citrus Profile -->
            <div class="profile-card rounded-3xl overflow-hidden flex flex-col fade-in-up" style="animation-delay: 0.4s;">
                <!-- Image Placeholder -->
                <div class="h-48 w-full bg-gradient-to-br from-orange-900/40 to-yellow-900/20 relative flex items-center justify-center">
                    <svg class="w-12 h-12 text-orange-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="absolute bottom-4 right-4 text-xs font-bold tracking-widest text-orange-400/50 uppercase">Citrus.png</span>
                </div>
                
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-white mb-2">Citrus</h3>
                    <p class="text-teal-400 text-sm font-semibold mb-3">Bright & Uplifting</p>
                    <p class="text-gray-400 text-sm flex-1">
                        Boosts mood and energizes your day. A zesty, invigorating profile perfect for fighting afternoon fatigue.
                    </p>
                    
                    <button class="btn-teal w-full mt-6 py-3 rounded-xl font-semibold text-sm tracking-wide">
                        Select Profile
                    </button>
                </div>
            </div>

            <!-- Lavender Profile -->
            <div class="profile-card rounded-3xl overflow-hidden flex flex-col fade-in-up" style="animation-delay: 0.5s;">
                <!-- Image Placeholder -->
                <div class="h-48 w-full bg-gradient-to-br from-indigo-900/40 to-violet-900/20 relative flex items-center justify-center">
                    <svg class="w-12 h-12 text-indigo-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span class="absolute bottom-4 right-4 text-xs font-bold tracking-widest text-indigo-400/50 uppercase">Lavender.png</span>
                </div>
                
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-white mb-2">Lavender</h3>
                    <p class="text-teal-400 text-sm font-semibold mb-3">Calming & Relaxing</p>
                    <p class="text-gray-400 text-sm flex-1">
                        Helps you unwind and promote better sleep. Your essential companion for evening wind-down routines.
                    </p>
                    
                    <button class="btn-teal w-full mt-6 py-3 rounded-xl font-semibold text-sm tracking-wide">
                        Select Profile
                    </button>
                </div>
            </div>

        </div>
        
        <div class="mt-16 text-center fade-in-up" style="animation-delay: 0.6s;">
            <a href="index.php?page=profiles" class="inline-block border border-teal-500 text-teal-400 hover:bg-teal-500 hover:text-ocean-950 font-bold py-3.5 px-8 rounded-xl transition-all duration-300">
                Explore Starter Kit
            </a>
        </div>

    </main>

</body>
</html>
