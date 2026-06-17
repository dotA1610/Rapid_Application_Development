<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Starter Kit — ARC NEBU-PEN</title>
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

        /* ── Full-page ocean gradient bg ── */
        .page-bg {
            background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%);
        }

        /* ── Button styling ── */
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

    <?php require __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 py-24 max-w-7xl mx-auto w-full flex flex-col lg:flex-row items-center gap-12 mt-10">
        <!-- Left: Image / Showcase -->
        <div class="w-full lg:w-1/2 flex justify-center fade-in-up">
            <div class="relative w-full max-w-md aspect-square rounded-3xl overflow-hidden bg-white/5 border border-white/10 flex items-center justify-center backdrop-blur-sm shadow-2xl">
                <!-- Device Mockup Placeholder -->
                <div class="w-3/4 h-3/4 bg-gradient-to-tr from-ocean-800 to-ocean-600 rounded-2xl flex flex-col items-center justify-center shadow-inner relative overflow-hidden border border-white/5">
                     <div class="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-teal-400 via-transparent to-transparent mix-blend-screen"></div>
                     <svg class="w-24 h-24 text-teal-500/80 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                     </svg>
                     <span class="text-white/80 font-bold tracking-widest text-sm uppercase">ARC NEBU-PEN</span>
                </div>
            </div>
        </div>

        <!-- Right: Content -->
        <div class="w-full lg:w-1/2 flex flex-col items-start fade-in-up" style="animation-delay: 0.2s;">
            <div class="inline-block px-3 py-1 mb-6 border border-teal-500/30 rounded-full bg-teal-500/10 text-teal-400 text-xs font-bold tracking-widest uppercase">
                Best Value
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight mb-4">
                The ARC Starter Kit
            </h1>
            
            <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-lg">
                Everything you need to elevate your breath. The complete system includes our premium matte-finish NEBU-PEN device, a USB-C fast charging cable, and a variety pack of our four signature sensory profiles.
            </p>

            <ul class="space-y-4 mb-10 text-gray-300">
                <li class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>1x Premium ARC NEBU-PEN Device</span>
                </li>
                <li class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>4x Sensory Profile Pods (Mint, Berry, Citrus, Lavender)</span>
                </li>
                <li class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>1x USB-C Braided Charging Cable</span>
                </li>
            </ul>
            
            <div class="flex items-end gap-6 mb-10">
                <div>
                    <p class="text-sm text-gray-400 font-medium line-through mb-1">$85.00 Value</p>
                    <p class="text-3xl font-black text-white">$49.99</p>
                </div>
            </div>

            <div class="w-full max-w-md flex flex-col sm:flex-row gap-4">
                <a href="index.php?page=bundle_builder" class="flex-1 btn-teal py-4 px-8 rounded-xl font-bold text-center uppercase tracking-wide">
                    Add to Cart
                </a>
                <a href="index.php?page=science" class="flex-1 bg-white/5 border border-white/10 hover:bg-white/10 py-4 px-8 rounded-xl font-bold text-center text-white transition-colors">
                    Learn More
                </a>
            </div>
        </div>
    </main>

</body>
</html>
