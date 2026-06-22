<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Build Your Bundle — ARC NEBU-PEN</title>
    <meta name="description" content="Create your perfect 3-cartridge ARC NEBU-PEN bundle." />

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

        /* ── Glassmorphism Panels ── */
        .glass-panel {
            background: rgba(10, 22, 28, 0.6);
            border: 1px solid rgba(46, 203, 128, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.05);
        }

        /* ── Dropdown Select ── */
        .flavor-select {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='rgba(255,255,255,0.5)'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2em;
            transition: all 0.2s ease;
        }
        .flavor-select:focus {
            outline: none;
            border-color: #2ECB80;
            box-shadow: 0 0 0 3px rgba(46,203,128,0.15);
        }
        .flavor-select option {
            background: #0C2028;
            color: #fff;
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
        .btn-teal:disabled {
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.3);
            box-shadow: none;
            transform: none;
            cursor: not-allowed;
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

    <?php require __DIR__ . '/../includes/navbar.php'; ?>

    <!-- ═══════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════ -->
    <main class="flex-1 px-6 lg:px-16 py-24 max-w-7xl mx-auto w-full mt-10">
        
        <div class="text-center mb-12 fade-in-up" style="animation-delay: 0.1s;">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Build Your <span class="text-teal-500">Bundle</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-xl mx-auto">
                Create your perfect 3-cartridge bundle. You can change your selections anytime.
            </p>
        </div>

        <!-- Render errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="mb-8 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-200">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mb-8 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-200">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form id="bundle-form" action="index.php?page=bundle_builder/save" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>" />

            <!-- Left Column: The 3 Cartridge Selectors -->
            <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-3 gap-6 fade-in-up" style="animation-delay: 0.2s;">
                
                <?php 
                // Helper to render product options
                function renderOptions($products, $selectedId) {
                    $html = '<option value="">-- Select Cartridge --</option>';
                    foreach ($products as $p) {
                        $pid = $p['product_id'];
                        $sel = ($pid == $selectedId) ? 'selected' : '';
                        // We store JSON containing id, name, and price so JS can parse it
                        $val = htmlspecialchars(json_encode([
                            'id' => $pid,
                            'name' => $p['name'],
                            'price' => (float)$p['price']
                        ]));
                        $html .= "<option value=\"{$val}\" {$sel}>" . htmlspecialchars($p['name']) . "</option>";
                    }
                    return $html;
                }
                
                // Determine pre-selected values
                $f1 = $old['flavor1'] ?? ($bundle['product1_id'] ?? 0);
                $f2 = $old['flavor2'] ?? ($bundle['product2_id'] ?? 0);
                $f3 = $old['flavor3'] ?? ($bundle['product3_id'] ?? 0);
                ?>

                <!-- Cartridge 1 -->
                <div class="glass-panel rounded-2xl p-6 flex flex-col items-center">
                    <label for="flavor1" class="block w-full text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Cartridge 1</label>
                    <select id="flavor1" name="flavor1" class="flavor-select w-full rounded-xl px-4 py-3 text-sm font-medium mb-8 cursor-pointer" required>
                        <?= renderOptions($products ?? [], $f1) ?>
                    </select>
                    
                    <!-- Pod Image Placeholder -->
                    <div id="pod1-img" class="w-16 h-48 rounded-full border border-teal-500/20 bg-gradient-to-b from-ocean-800 to-transparent flex items-center justify-center shadow-[0_0_30px_rgba(46,203,128,0.1)] transition-colors duration-300">
                        <span class="text-xs font-bold text-gray-500 -rotate-90 whitespace-nowrap">SELECT POD</span>
                    </div>
                </div>

                <!-- Cartridge 2 -->
                <div class="glass-panel rounded-2xl p-6 flex flex-col items-center">
                    <label for="flavor2" class="block w-full text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Cartridge 2</label>
                    <select id="flavor2" name="flavor2" class="flavor-select w-full rounded-xl px-4 py-3 text-sm font-medium mb-8 cursor-pointer" required>
                        <?= renderOptions($products ?? [], $f2) ?>
                    </select>
                    
                    <div id="pod2-img" class="w-16 h-48 rounded-full border border-teal-500/20 bg-gradient-to-b from-ocean-800 to-transparent flex items-center justify-center shadow-[0_0_30px_rgba(46,203,128,0.1)] transition-colors duration-300">
                        <span class="text-xs font-bold text-gray-500 -rotate-90 whitespace-nowrap">SELECT POD</span>
                    </div>
                </div>

                <!-- Cartridge 3 -->
                <div class="glass-panel rounded-2xl p-6 flex flex-col items-center">
                    <label for="flavor3" class="block w-full text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Cartridge 3</label>
                    <select id="flavor3" name="flavor3" class="flavor-select w-full rounded-xl px-4 py-3 text-sm font-medium mb-8 cursor-pointer" required>
                        <?= renderOptions($products ?? [], $f3) ?>
                    </select>
                    
                    <div id="pod3-img" class="w-16 h-48 rounded-full border border-teal-500/20 bg-gradient-to-b from-ocean-800 to-transparent flex items-center justify-center shadow-[0_0_30px_rgba(46,203,128,0.1)] transition-colors duration-300">
                        <span class="text-xs font-bold text-gray-500 -rotate-90 whitespace-nowrap">SELECT POD</span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Summary Panel -->
            <div class="lg:col-span-4 fade-in-up" style="animation-delay: 0.3s;">
                <div class="glass-panel rounded-3xl p-8 sticky top-32">
                    <h2 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">Your Bundle</h2>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <span class="text-teal-500 font-mono text-sm">1.</span>
                            <span id="summary-item-1" class="text-gray-300 font-medium">--</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-teal-500 font-mono text-sm">2.</span>
                            <span id="summary-item-2" class="text-gray-300 font-medium">--</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-teal-500 font-mono text-sm">3.</span>
                            <span id="summary-item-3" class="text-gray-300 font-medium">--</span>
                        </li>
                    </ul>

                    <div class="border-t border-white/10 pt-6 mb-8">
                        <p class="text-sm text-gray-400 mb-1">Total</p>
                        <div class="flex items-end gap-2">
                            <span id="total-price" class="text-3xl font-extrabold text-gray-500">RM0.00</span>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="btn-teal w-full py-4 rounded-xl font-bold text-sm tracking-wide shadow-lg flex justify-center items-center gap-2">
                        <span>Add to Cart</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                    
                    <p class="text-center text-xs text-gray-500 mt-4">Free shipping on Core Club subscriptions.</p>
                </div>
            </div>

        </form>
    </main>

    <!-- ═══════════════════════════════════════════════════════
         JAVASCRIPT LOGIC
    ═══════════════════════════════════════════════════════ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flavor1 = document.getElementById('flavor1');
            const flavor2 = document.getElementById('flavor2');
            const flavor3 = document.getElementById('flavor3');

            const sumItem1 = document.getElementById('summary-item-1');
            const sumItem2 = document.getElementById('summary-item-2');
            const sumItem3 = document.getElementById('summary-item-3');
            
            const totalPriceEl = document.getElementById('total-price');

            const podImg1 = document.getElementById('pod1-img');
            const podImg2 = document.getElementById('pod2-img');
            const podImg3 = document.getElementById('pod3-img');

            // Form must submit IDs, but we have JSON in values.
            // Let's intercept form submission to inject IDs correctly, or adjust values.
            // Wait, we can just let the controller process 'flavor1' as int, 
            // but if value is JSON string, (int)$_POST['flavor1'] will cast to 0. 
            // We need to store ID in a hidden input instead.
            
            const form = document.getElementById('bundle-form');
            form.addEventListener('submit', (e) => {
                const parseId = (val) => val ? JSON.parse(val).id : 0;
                
                const id1 = document.createElement('input');
                id1.type = 'hidden'; id1.name = 'flavor1'; id1.value = parseId(flavor1.value);
                
                const id2 = document.createElement('input');
                id2.type = 'hidden'; id2.name = 'flavor2'; id2.value = parseId(flavor2.value);
                
                const id3 = document.createElement('input');
                id3.type = 'hidden'; id3.name = 'flavor3'; id3.value = parseId(flavor3.value);
                
                form.appendChild(id1);
                form.appendChild(id2);
                form.appendChild(id3);
                
                // Disable selects so they don't submit their string values
                flavor1.name = '';
                flavor2.name = '';
                flavor3.name = '';
            });

            const flavorStyles = {
                'Lavender': { bgClass: 'from-indigo-900/40', textClass: 'text-indigo-400/50', label: 'LAVENDER POD' },
                'Mint': { bgClass: 'from-green-900/40', textClass: 'text-green-400/50', label: 'MINT POD' },
                'Citrus': { bgClass: 'from-orange-900/40', textClass: 'text-orange-400/50', label: 'CITRUS POD' },
                'Berry': { bgClass: 'from-purple-900/40', textClass: 'text-pink-400/50', label: 'BERRY POD' },
                'Default': { bgClass: 'from-ocean-800', textClass: 'text-gray-500', label: 'SELECT POD' }
            };

            function updatePodVisual(podEl, name) {
                // simple substring match to apply styling
                let style = flavorStyles['Default'];
                if(name) {
                    for(const key in flavorStyles) {
                        if(name.includes(key)) {
                            style = flavorStyles[key];
                            break;
                        }
                    }
                }
                
                podEl.className = `w-16 h-48 rounded-full border border-teal-500/20 bg-gradient-to-b ${style.bgClass} to-transparent flex items-center justify-center shadow-[0_0_30px_rgba(46,203,128,0.1)] transition-colors duration-300`;
                const textSpan = podEl.querySelector('span');
                if (textSpan) {
                    textSpan.className = `text-xs font-bold ${style.textClass} -rotate-90 whitespace-nowrap`;
                    textSpan.textContent = style.label;
                }
            }

            function updateSummary() {
                const parseFlavor = (val) => val ? JSON.parse(val) : null;
                const p1 = parseFlavor(flavor1.value);
                const p2 = parseFlavor(flavor2.value);
                const p3 = parseFlavor(flavor3.value);

                sumItem1.textContent = p1 ? p1.name : '--';
                sumItem2.textContent = p2 ? p2.name : '--';
                sumItem3.textContent = p3 ? p3.name : '--';

                updatePodVisual(podImg1, p1 ? p1.name : null);
                updatePodVisual(podImg2, p2 ? p2.name : null);
                updatePodVisual(podImg3, p3 ? p3.name : null);

                let count = 0;
                let total = 0;
                
                if(p1) { count++; total += p1.price; }
                if(p2) { count++; total += p2.price; }
                if(p3) { count++; total += p3.price; }

                if (count === 3) {
                    totalPriceEl.textContent = `RM${total.toFixed(2)}`;
                    totalPriceEl.classList.add('text-white');
                    totalPriceEl.classList.remove('text-gray-500');
                } else {
                    totalPriceEl.textContent = 'RM0.00';
                    totalPriceEl.classList.add('text-gray-500');
                    totalPriceEl.classList.remove('text-white');
                }
            }

            flavor1.addEventListener('change', updateSummary);
            flavor2.addEventListener('change', updateSummary);
            flavor3.addEventListener('change', updateSummary);

            updateSummary();
        });
    </script>
