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

    <?php require __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 pt-32 pb-12 max-w-7xl mx-auto w-full">
        
        <style>
            input[type="radio"]:checked ~ label {
                border-color: #2ECB80;
                background-color: rgba(46, 203, 128, 0.1);
            }
            input[type="radio"]:checked ~ label .radio-outer {
                border-color: #2ECB80;
            }
            input[type="radio"]:checked ~ label .radio-inner {
                opacity: 1;
            }
        </style>

        <div class="text-center mb-16 fade-in-up">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Core Club Subscription
            </h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Never run out of your favorite sensations.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 max-w-6xl mx-auto fade-in-up" style="animation-delay: 0.2s;">
            <!-- Left Card: Core Club Benefits -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 lg:p-10 flex flex-col backdrop-blur-sm relative h-full">
                <div class="mb-8 flex items-center gap-4 border-b border-white/10 pb-6">
                    <div class="w-12 h-12 rounded-xl bg-teal-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h2 class="text-2xl lg:text-3xl font-bold text-white">Core Club Benefits</h2>
                </div>
                <ul class="space-y-6 flex-1">
                    <li class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-300 text-lg">20% OFF every delivery</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-300 text-lg">Auto Ship - Hassle Free</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-300 text-lg">Cancel anytime</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-300 text-lg">Priority access to new flavors</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-300 text-lg">Exclusive member offers</span>
                    </li>
                </ul>
            </div>

            <!-- Right Card: Choose Your Plan -->
            <div class="bg-gradient-to-b from-ocean-800 to-ocean-900 border border-teal-500/30 rounded-3xl p-8 lg:p-10 flex flex-col backdrop-blur-sm relative shadow-[0_0_40px_rgba(46,203,128,0.1)] h-full">
                <h2 class="text-2xl lg:text-3xl font-bold text-white mb-8">Choose Your Plan</h2>
                <form action="index.php?page=cart/add" method="POST" class="flex flex-col flex-1">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="item_type" value="subscription">
                    
                    <div class="space-y-4 flex-1">
                        <!-- Radio 1 -->
                        <div class="relative block">
                            <input type="radio" id="plan_monthly" name="plan" value="monthly_45" class="sr-only" checked>
                            <label for="plan_monthly" class="cursor-pointer flex items-center w-full p-5 rounded-2xl border-2 border-white/10 bg-white/5 transition-all hover:bg-white/10">
                                <div class="radio-outer w-6 h-6 rounded-full border-2 border-gray-400 flex-shrink-0 flex items-center justify-center mr-5 relative transition-colors">
                                    <div class="radio-inner w-3 h-3 bg-teal-500 rounded-full opacity-0 absolute transition-opacity"></div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-white m-0">Monthly</h3>
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-white m-0 leading-tight">RM45.00</p>
                                        <p class="text-sm text-gray-400 m-0">/ month</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Radio 2 -->
                        <div class="relative block">
                            <input type="radio" id="plan_bimonthly" name="plan" value="bi_monthly_85" class="sr-only">
                            <label for="plan_bimonthly" class="cursor-pointer flex items-center w-full p-5 rounded-2xl border-2 border-white/10 bg-white/5 transition-all hover:bg-white/10">
                                <div class="radio-outer w-6 h-6 rounded-full border-2 border-gray-400 flex-shrink-0 flex items-center justify-center mr-5 relative transition-colors">
                                    <div class="radio-inner w-3 h-3 bg-teal-500 rounded-full opacity-0 absolute transition-opacity"></div>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-white m-0">Every 2 Months</h3>
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-white m-0 leading-tight">RM85.00</p>
                                        <p class="text-sm text-gray-400 m-0">/ 2 months</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-white/10">
                        <button type="submit" class="w-full btn-teal py-4 px-8 rounded-xl font-bold text-lg text-center uppercase tracking-wide mb-4 shadow-lg shadow-teal-500/20">
                            Join Core Club Now
                        </button>
                        <p class="text-center text-sm text-gray-400 font-medium">You can pause or cancel anytime.</p>
                    </div>
                </form>
            </div>
        </div>
    </main>

