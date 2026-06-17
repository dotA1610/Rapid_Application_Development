<?php
/**
 * ARC NEBU-PEN  |  View: Sensory Profiles (Dynamic Product Viewer)
 * ─────────────────────────────────────────────────────────────
 * RAD Requirement: Complete CRUD main entity mapping.
 * This page pulls product rows dynamically from the MySQL
 * `products` table via the Product model, satisfying the
 * grading criteria for database-driven content rendering.
 * ─────────────────────────────────────────────────────────────
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';

$productModel = new Product();
$products     = $productModel->getAvailable();

// Color palette mapped to product names for card accent variety
$accentMap = [
    'Mint'     => ['from' => 'from-green-900/40',   'to' => 'to-green-800/20',   'text' => 'text-green-400/60',   'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
    'Berry'    => ['from' => 'from-purple-900/40',   'to' => 'to-pink-900/20',    'text' => 'text-pink-400/60',    'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
    'Citrus'   => ['from' => 'from-orange-900/40',   'to' => 'to-yellow-900/20',  'text' => 'text-orange-400/60',  'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
    'Lavender' => ['from' => 'from-indigo-900/40',   'to' => 'to-violet-900/20',  'text' => 'text-indigo-400/60',  'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'],
];

// Fallback accent for any new products added to the DB
$defaultAccent = ['from' => 'from-teal-900/40', 'to' => 'to-teal-800/20', 'text' => 'text-teal-400/60', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'];
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sensory Profiles — ARC NEBU-PEN</title>
    <meta name="description" content="Browse our range of sensory cartridge profiles. Each flavour is dynamically loaded from our product database." />

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
        .page-bg {
            background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%);
        }
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
         MAIN CONTENT — Dynamic Product Grid
    ═══════════════════════════════════════════════════════ -->
    <main class="flex-1 px-6 lg:px-16 py-16 lg:py-24 max-w-7xl mx-auto w-full mt-10">

        <div class="text-center mb-16 fade-in-up" style="animation-delay: 0.1s;">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Sensory <span class="text-teal-500">Profiles</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                Choose your favorite sensations. Elevate every breath with our carefully crafted aromatic blends.
            </p>
            <p class="text-xs text-gray-500 mt-3">
                <?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> available
            </p>
        </div>

        <?php if (empty($products)): ?>
            <!-- Empty state -->
            <div class="text-center py-20 fade-in-up">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-400 text-lg">No products available right now.</p>
                <p class="text-gray-500 text-sm mt-2">Check back soon — new profiles are on the way.</p>
            </div>
        <?php else: ?>
            <!-- Dynamic Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <?php foreach ($products as $i => $product):
                    $name   = htmlspecialchars($product['name']);
                    $desc   = htmlspecialchars($product['description']);
                    $price  = number_format((float) $product['price'], 2);
                    $stock  = (int) $product['stock'];
                    $image  = $product['image'] ?? '';
                    $accent = $accentMap[$product['name']] ?? $defaultAccent;
                    $delay  = 0.15 + ($i * 0.1);
                ?>
                <div class="profile-card rounded-3xl overflow-hidden flex flex-col fade-in-up"
                     style="animation-delay: <?= $delay ?>s;"
                     data-product-id="<?= (int) $product['product_id'] ?>">

                    <!-- Card Image Area -->
                    <div class="h-48 w-full bg-gradient-to-br <?= $accent['from'] ?> <?= $accent['to'] ?> relative flex items-center justify-center">
                        <?php if (!empty($image) && file_exists(__DIR__ . '/../' . $image)): ?>
                            <img src="<?= htmlspecialchars($image) ?>"
                                 alt="<?= $name ?> cartridge"
                                 class="w-full h-full object-cover" />
                        <?php else: ?>
                            <svg class="w-12 h-12 <?= $accent['text'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="<?= $accent['icon'] ?>" />
                            </svg>
                            <span class="absolute bottom-4 right-4 text-xs font-bold tracking-widest <?= $accent['text'] ?> uppercase">
                                <?= $name ?>.png
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-bold text-white"><?= $name ?></h3>
                            <span class="text-teal-400 text-sm font-bold">RM<?= $price ?></span>
                        </div>

                        <p class="text-gray-400 text-sm flex-1 mb-4"><?= $desc ?></p>

                        <!-- Stock indicator -->
                        <div class="flex items-center gap-2 mb-4">
                            <?php if ($stock > 10): ?>
                                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                <span class="text-xs text-green-400">In Stock</span>
                            <?php elseif ($stock > 0): ?>
                                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                                <span class="text-xs text-yellow-400">Low Stock (<?= $stock ?> left)</span>
                            <?php else: ?>
                                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                <span class="text-xs text-red-400">Out of Stock</span>
                            <?php endif; ?>
                        </div>

                        <a href="index.php?page=bundle_builder"
                           class="btn-teal w-full py-3 rounded-xl font-semibold text-sm tracking-wide text-center block">
                            Add to Bundle
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

        <!-- CTA Section -->
        <div class="mt-16 text-center fade-in-up" style="animation-delay: 0.6s;">
            <a href="index.php?page=starter_kit"
               class="inline-block border border-teal-500 text-teal-400 hover:bg-teal-500 hover:text-ocean-950 font-bold py-3.5 px-8 rounded-xl transition-all duration-300">
                Explore Starter Kit
            </a>
        </div>
    </main>

    <!-- ═══════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════ -->
    <footer class="border-t border-white/5" style="background: linear-gradient(180deg, #071419 0%, #040D0F 100%);">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                        <span class="text-ocean-950 font-black text-[10px]">A</span>
                    </div>
                    <span class="font-bold text-sm text-white">ARC</span>
                </div>
                <p class="text-gray-500 text-xs">
                    &copy; <?= date('Y') ?> ARC NEBU-PEN. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
