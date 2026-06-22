<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart — ARC NEBU-PEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review your ARC NEBU-PEN shopping cart before checkout.">
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
        .glass-panel {
            background: rgba(10, 22, 28, 0.6);
            border: 1px solid rgba(46, 203, 128, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.04);
        }
        .btn-teal {
            background: linear-gradient(135deg, #2ECB80 0%, #25B870 100%);
            color: #040D0F;
            transition: all 0.25s ease;
        }
        .btn-teal:hover {
            background: linear-gradient(135deg, #4DD9A8 0%, #2ECB80 100%);
            box-shadow: 0 8px 24px rgba(46,203,128,0.3);
            transform: translateY(-1px);
        }
        .fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-1 px-4 sm:px-6 lg:px-16 pt-28 pb-16 max-w-5xl mx-auto w-full">

        <!-- Header -->
        <div class="flex items-center justify-between mb-10 fade-in-up">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white">Shopping Cart</h1>
                    <p class="text-sm text-gray-400">
                        <?= count($cart) ?> item<?= count($cart) !== 1 ? 's' : '' ?> in your cart
                    </p>
                </div>
            </div>
            <a href="index.php?page=home" class="text-sm text-gray-400 hover:text-teal-400 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Continue Shopping
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mb-6 p-4 rounded-xl bg-teal-500/15 border border-teal-500/30 text-teal-300 text-sm flex items-center gap-3 fade-in-up">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-sm flex items-center gap-3 fade-in-up">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>


        <?php if (empty($cart)): ?>
        <!-- ═══ EMPTY CART STATE ═══ -->
        <div class="glass-panel rounded-3xl p-12 text-center fade-in-up" style="animation-delay: 0.1s;">
            <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">Your cart is empty</h2>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">
                Explore our sensory profiles, starter kits, and subscription plans to begin your wellness journey.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="index.php?page=starter_kit" class="btn-teal px-8 py-3.5 rounded-xl font-bold text-sm tracking-wide">
                    View Starter Kit
                </a>
                <a href="index.php?page=profiles" class="bg-white/5 border border-white/10 hover:bg-white/10 px-8 py-3.5 rounded-xl font-bold text-sm text-white transition-colors">
                    Browse Profiles
                </a>
            </div>
        </div>

        <?php else: ?>
        <!-- ═══ CART WITH ITEMS ═══ -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- ── Cart Items List ────────────────────────── -->
            <div class="lg:col-span-8 space-y-4 fade-in-up" style="animation-delay: 0.1s;">

                <?php foreach ($cart as $index => $item): ?>
                <div class="glass-panel rounded-2xl p-6 flex items-center gap-5 group hover:border-teal-500/25 transition-colors">
                    <!-- Item icon -->
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0
                        <?php if ($item['type'] === 'kit'): ?> bg-emerald-500/15
                        <?php elseif ($item['type'] === 'subscription'): ?> bg-indigo-500/15
                        <?php else: ?> bg-teal-500/15
                        <?php endif; ?>">
                        <?php if ($item['type'] === 'kit'): ?>
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        <?php elseif ($item['type'] === 'subscription'): ?>
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <?php else: ?>
                        <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <?php endif; ?>
                    </div>

                    <!-- Item details -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white font-semibold text-base truncate"><?= htmlspecialchars($item['name']) ?></h3>
                        <?php if ($item['type'] === 'bundle' && !empty($item['meta'])): ?>
                            <p class="text-xs text-gray-500 mt-1 truncate">
                                <?= htmlspecialchars($item['meta']['flavor1'] ?? '') ?> · 
                                <?= htmlspecialchars($item['meta']['flavor2'] ?? '') ?> · 
                                <?= htmlspecialchars($item['meta']['flavor3'] ?? '') ?>
                            </p>
                        <?php elseif ($item['type'] === 'kit' && !empty($item['meta']['includes'])): ?>
                            <p class="text-xs text-gray-500 mt-1 truncate"><?= htmlspecialchars($item['meta']['includes']) ?></p>
                        <?php elseif ($item['type'] === 'subscription'): ?>
                            <p class="text-xs text-gray-500 mt-1">Auto-renewing · Cancel anytime</p>
                        <?php endif; ?>
                        <!-- Type badge -->
                        <span class="inline-block mt-2 text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full
                            <?php if ($item['type'] === 'kit'): ?> bg-emerald-500/10 text-emerald-400
                            <?php elseif ($item['type'] === 'subscription'): ?> bg-indigo-500/10 text-indigo-400
                            <?php else: ?> bg-teal-500/10 text-teal-400
                            <?php endif; ?>">
                            <?= htmlspecialchars(ucfirst($item['type'])) ?>
                        </span>
                    </div>

                    <!-- Qty -->
                    <div class="text-center shrink-0 hidden sm:block">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Qty</p>
                        <p class="text-white font-bold"><?= (int) $item['qty'] ?></p>
                    </div>

                    <!-- Price -->
                    <div class="text-right shrink-0">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Price</p>
                        <p class="text-white font-bold text-lg">RM<?= number_format($item['price'] * $item['qty'], 2) ?></p>
                        
                        <form action="index.php?page=cart/remove" method="POST" class="mt-2">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                            <input type="hidden" name="item_index" value="<?= $index ?>">
                            <button type="submit" class="text-[10px] text-red-500/80 hover:text-red-400 uppercase tracking-widest font-bold flex items-center gap-1 justify-end w-full transition-colors" title="Remove Item">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

            <!-- ── Order Summary Sidebar ────────────────────── -->
            <div class="lg:col-span-4 fade-in-up" style="animation-delay: 0.2s;">
                <div class="glass-panel rounded-3xl p-8 sticky top-28">

                    <h2 class="text-lg font-bold text-white mb-6 pb-4 border-b border-white/10">Order Summary</h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Subtotal (<?= count($cart) ?> item<?= count($cart) !== 1 ? 's' : '' ?>)</span>
                            <span class="text-gray-300">RM<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <?php if ($discount > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-emerald-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                Core Club 20% Off
                            </span>
                            <span class="text-emerald-400">−RM<?= number_format($discount, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Shipping</span>
                            <span class="text-teal-400 font-medium">Free</span>
                        </div>
                    </div>

                    <div class="border-t border-white/10 pt-5 mb-8">
                        <div class="flex justify-between">
                            <span class="text-base font-bold text-white">Total</span>
                            <span class="text-2xl font-black text-white">RM<?= number_format($total, 2) ?></span>
                        </div>
                    </div>

                    <!-- Proceed to Checkout -->
                    <a href="index.php?page=checkout"
                       class="btn-teal w-full py-4 rounded-xl font-bold text-sm tracking-wide flex items-center justify-center gap-2.5 shadow-lg shadow-teal-500/15">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Proceed to Checkout
                    </a>

                    <!-- Clear cart -->
                    <form action="index.php?page=cart/clear" method="POST" class="mt-4">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <button type="submit" class="w-full text-center text-xs text-gray-500 hover:text-red-400 transition-colors py-2">
                            Clear entire cart
                        </button>
                    </form>

                    <!-- Trust badges -->
                    <div class="mt-6 pt-5 border-t border-white/5 flex items-center justify-center gap-4 text-[10px] text-gray-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Secure Checkout
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            SSL Encrypted
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <?php endif; ?>

    </main>
