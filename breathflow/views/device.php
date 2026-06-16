<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Device — ARC NEBU-PEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        .page-bg {
            background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%);
        }
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

    <main class="flex-1 px-6 lg:px-16 py-24 max-w-7xl mx-auto w-full flex flex-col gap-24 mt-10">
        
        <!-- Hero Section -->
        <section class="flex flex-col lg:flex-row items-center gap-16 fade-in-up">
            <div class="w-full lg:w-1/2 flex justify-center relative">
                <!-- Abstract Device Graphic -->
                <div class="relative w-72 h-96 bg-gradient-to-t from-ocean-800 to-ocean-600 rounded-[3rem] border border-white/10 shadow-2xl flex flex-col justify-end p-6 overflow-hidden">
                    <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-teal-400 via-transparent to-transparent mix-blend-screen"></div>
                    <div class="w-full h-1/2 bg-gradient-to-t from-ocean-950/80 to-transparent absolute bottom-0 left-0"></div>
                    <div class="relative z-10 w-full h-2 bg-teal-500 rounded-full shadow-[0_0_15px_rgba(46,203,128,1)]"></div>
                </div>
            </div>
            <div class="w-full lg:w-1/2 flex flex-col items-start">
                <div class="inline-block px-3 py-1 mb-4 border border-teal-500/30 rounded-full bg-teal-500/10 text-teal-400 text-xs font-bold tracking-widest uppercase">
                    Hardware Innovation
                </div>
                <h1 class="text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                    Meet the ARC <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-600">NEBU-PEN</span>
                </h1>
                <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-lg">
                    A breakthrough in functional aromatics. Engineered to deliver unheated, cool-air sensory profiles instantly without combustion or vapor. Pure, clean, and beautifully portable.
                </p>
            </div>
        </section>

        <!-- Features Grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-in-up" style="animation-delay: 0.2s;">
            <!-- Feature 1 -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/20 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Unheated Cool Air</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Zero heat. Zero smoke. Our proprietary airflow technology diffuses essential aromas mechanically, maintaining the pure integrity of the plant compounds.
                </p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/20 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Ultra Portable</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Designed to fit seamlessly into your life. The NEBU-PEN is discreet, pocket-friendly, and perfect for instant access to focus or calm, wherever you are.
                </p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/20 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Premium Build</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Machined from aerospace-grade aluminum with a soft-touch matte finish. It feels as premium as the experiences it delivers.
                </p>
            </div>
        </section>

    </main>

</body>
</html>
