<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription — ARC NEBU-PEN</title>
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
        .btn-teal {
            background-color: #2ECB80;
            color: #040D0F;
            transition: all 0.2s ease-in-out;
        }
        .btn-teal:hover {
            background-color: #4DD9A8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(46,203,128,0.5);
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

    <main class="flex-1 px-6 lg:px-16 py-24 max-w-7xl mx-auto w-full mt-10">
        
        <div class="text-center mb-16 fade-in-up">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                ARC <span class="text-teal-500">Core Club</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Never run out of your favorite sensory profiles. Join our flexible subscription tiers and save 20% on all automatic deliveries.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto fade-in-up" style="animation-delay: 0.2s;">
            <!-- Tier 1 -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 flex flex-col backdrop-blur-sm relative">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Wellness Routine</h2>
                    <p class="text-gray-400 text-sm">Perfect for daily baseline maintenance.</p>
                </div>
                <div class="mb-8 flex items-end gap-2">
                    <span class="text-5xl font-black text-white">$24</span>
                    <span class="text-gray-400 font-medium mb-1">/ month</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1">
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        2 Cartridges every month
                    </li>
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Free Shipping
                    </li>
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Swap flavors anytime
                    </li>
                </ul>
                <a href="index.php?page=subscription/join&tier=wellness" class="block w-full py-4 text-center rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold transition-colors">
                    Select Routine
                </a>
            </div>

            <!-- Tier 2 -->
            <div class="bg-gradient-to-b from-ocean-800 to-ocean-900 border border-teal-500/30 rounded-3xl p-8 flex flex-col backdrop-blur-sm relative shadow-[0_0_40px_rgba(46,203,128,0.1)]">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-teal-500 text-ocean-950 font-bold text-xs uppercase tracking-widest px-4 py-1.5 rounded-full">
                    Most Popular
                </div>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Deep Breath Tracker</h2>
                    <p class="text-gray-400 text-sm">For the dedicated mindful breather.</p>
                </div>
                <div class="mb-8 flex items-end gap-2">
                    <span class="text-5xl font-black text-white">$45</span>
                    <span class="text-gray-400 font-medium mb-1">/ month</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1">
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        4 Cartridges every month
                    </li>
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Priority Free Shipping
                    </li>
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Exclusive early access to new flavors
                    </li>
                </ul>
                <a href="index.php?page=subscription/join&tier=deep_breath" class="block w-full py-4 text-center rounded-xl btn-teal font-bold tracking-wide">
                    Subscribe Now
                </a>
            </div>
        </div>
    </main>

</body>
</html>
