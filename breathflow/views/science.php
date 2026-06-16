<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Science — ARC NEBU-PEN</title>
    <meta name="description" content="Learn about the science of Pursed Lip Breathing and Vagus Nerve Activation." />

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

        /* ── Glassmorphism Container ── */
        .glass-panel {
            background: rgba(10, 22, 28, 0.6);
            border: 1px solid rgba(46, 203, 128, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.05);
        }

        /* ── Floating Animation ── */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
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
                Science of the <span class="text-teal-500">Deep Breath</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Deep, intentional breaths can transform how you feel—physically and mentally. Explore the physiological mechanisms behind ARC NEBU-PEN.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Left Column: Text Content -->
            <div class="space-y-10 fade-in-up" style="animation-delay: 0.2s;">
                
                <!-- Pursed Lip Breathing -->
                <div class="relative pl-8">
                    <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-teal-500 to-transparent rounded-full"></div>
                    <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-3">Pursed Lip Breathing</h2>
                    <p class="text-gray-400 leading-relaxed">
                        Inhale through your nose. Exhale slowly through pursed lips. ARC is designed with optimal resistance to naturally guide you into pursed lip breathing, which helps slow your breathing rate, improve oxygen exchange, and reduce shortness of breath.
                    </p>
                </div>

                <!-- Vagus Nerve Activation -->
                <div class="relative pl-8">
                    <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-teal-500 to-transparent rounded-full"></div>
                    <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-3">Vagus Nerve Activation</h2>
                    <p class="text-gray-400 leading-relaxed">
                        Deep, prolonged exhalations stimulate the vagus nerve—the main component of your parasympathetic nervous system. This activation directly signals your heart rate to slow down, supporting deep relaxation, calming the mind, and promoting overall well-being.
                    </p>
                </div>

            </div>

            <!-- Right Column: Glassmorphism Graphic Placeholder -->
            <div class="fade-in-up" style="animation-delay: 0.3s;">
                <div class="glass-panel rounded-3xl p-8 aspect-square lg:aspect-auto lg:h-[600px] flex flex-col items-center justify-center relative overflow-hidden group">
                    
                    <!-- Decorative background glows -->
                    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl group-hover:bg-teal-500/30 transition-all duration-700"></div>
                    <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-ocean-600/40 rounded-full blur-3xl"></div>

                    <!-- Placeholder Image Area -->
                    <div class="relative z-10 animate-float flex flex-col items-center">
                        <div class="w-48 h-48 sm:w-64 sm:h-64 rounded-full border border-teal-500/30 bg-gradient-to-tr from-ocean-800 to-ocean-900 flex items-center justify-center shadow-[0_0_40px_rgba(46,203,128,0.2)]">
                            <svg class="w-16 h-16 text-teal-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-teal-400 text-sm font-semibold tracking-widest uppercase mt-8">Body Diagram Graphic</p>
                        <p class="text-gray-500 text-xs mt-2 text-center max-w-xs">Drop your anatomical diagram PNG here to illustrate airflow and nerve activation.</p>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>
