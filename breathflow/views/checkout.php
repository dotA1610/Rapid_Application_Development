<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout — ARC NEBU-PEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure checkout for ARC NEBU-PEN products.">
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
                        stripe: { bg: '#0A2540', card: '#1A3A5C', accent: '#635BFF' },
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        .page-bg {
            background: linear-gradient(145deg, #0A2540 0%, #040D0F 50%, #071419 100%);
        }

        .checkout-card {
            background: rgba(12, 32, 40, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.5);
        }

        .input-field {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #2ECB80;
            box-shadow: 0 0 0 3px rgba(46, 203, 128, 0.12);
            background: rgba(255, 255, 255, 0.06);
        }
        .input-field::placeholder { color: rgba(255, 255, 255, 0.3); }

        .pay-btn {
            background: linear-gradient(135deg, #2ECB80 0%, #25B870 100%);
            color: #040D0F;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .pay-btn:hover {
            box-shadow: 0 8px 24px rgba(46, 203, 128, 0.35);
            transform: translateY(-1px);
        }
        .pay-btn:active { transform: translateY(0); }
        .pay-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        .pay-btn:hover::after { transform: translateX(100%); }

        .fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card number formatting */
        .card-icons { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); }
    </style>
</head>

<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-1 px-4 sm:px-6 lg:px-8 pt-28 pb-16 max-w-6xl mx-auto w-full">

        <!-- Back link -->
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-teal-400 transition-colors mb-8 fade-in-up">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>

        <?php if (empty($cart)): ?>
        <!-- Empty cart state -->
        <div class="text-center py-24 fade-in-up">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">Your cart is empty</h2>
            <p class="text-gray-400 mb-8">Add products to your cart to proceed with checkout.</p>
            <a href="index.php?page=home" class="inline-block bg-teal-500 text-ocean-950 font-bold px-8 py-3 rounded-xl hover:bg-teal-400 transition-colors">
                Continue Shopping
            </a>
        </div>

        <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <!-- ── LEFT: Order Summary ────────────────────────── -->
            <div class="lg:col-span-5 fade-in-up" style="animation-delay: 0.1s;">
                <div class="checkout-card rounded-3xl p-8">

                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/15 flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Order Summary</h2>
                    </div>

                    <!-- Cart items -->
                    <div class="space-y-5 mb-8">
                        <?php foreach ($cart as $item): ?>
                        <div class="flex items-start justify-between gap-4 pb-5 border-b border-white/5 last:border-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                                    <?php if ($item['type'] === 'kit'): ?> bg-emerald-500/15
                                    <?php elseif ($item['type'] === 'subscription'): ?> bg-indigo-500/15
                                    <?php else: ?> bg-teal-500/15
                                    <?php endif; ?>">
                                    <?php if ($item['type'] === 'kit'): ?>
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                    <?php elseif ($item['type'] === 'subscription'): ?>
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <?php else: ?>
                                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-white text-sm"><?= htmlspecialchars($item['name']) ?></p>
                                    <?php if ($item['type'] === 'bundle' && !empty($item['meta'])): ?>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <?= htmlspecialchars($item['meta']['flavor1'] ?? '') ?>,
                                            <?= htmlspecialchars($item['meta']['flavor2'] ?? '') ?>,
                                            <?= htmlspecialchars($item['meta']['flavor3'] ?? '') ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($item['type'] === 'kit' && !empty($item['meta']['includes'])): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($item['meta']['includes']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-500 mt-1">Qty: <?= (int) $item['qty'] ?></p>
                                </div>
                            </div>
                            <p class="font-bold text-white text-sm whitespace-nowrap">RM<?= number_format($item['price'] * $item['qty'], 2) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="text-gray-300">RM<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <?php if ($discount > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-emerald-400 flex items-center gap-1.5">
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
                        <div class="border-t border-white/10 pt-4 mt-4 flex justify-between">
                            <span class="text-lg font-bold text-white">Total</span>
                            <span class="text-2xl font-black text-white">RM<?= number_format($total, 2) ?></span>
                        </div>
                    </div>

                </div>

                <!-- Clear cart -->
                <form action="index.php?page=cart/clear" method="POST" class="mt-4">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" class="text-xs text-gray-500 hover:text-red-400 transition-colors underline underline-offset-2">
                        Clear cart
                    </button>
                </form>
            </div>

            <!-- ── RIGHT: Payment Form ────────────────────────── -->
            <div class="lg:col-span-7 fade-in-up" style="animation-delay: 0.2s;">
                <div class="checkout-card rounded-3xl p-8 lg:p-10">

                    <div class="flex items-center justify-between mb-8 pb-6 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/15 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white">Payment Details</h2>
                        </div>
                        <!-- Security badge -->
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Secured</span>
                        </div>
                    </div>

                    <!-- Test mode banner -->
                    <div class="mb-6 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-3">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-amber-300/90">
                            <strong>Test Mode</strong> — Use card number <code class="bg-white/10 px-1.5 py-0.5 rounded font-mono text-amber-200">4242 4242 4242 4242</code> with any expiry and CVC.
                        </p>
                    </div>

                    <form action="index.php?page=checkout/process" method="POST" id="payment-form">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <!-- Email -->
                        <div class="mb-5">
                            <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                            <input type="email" id="email" name="email"
                                   class="input-field w-full px-4 py-3.5 rounded-xl text-sm"
                                   placeholder="your@email.com"
                                   value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>"
                                   required>
                        </div>

                        <!-- Card Information -->
                        <div class="mb-5">
                            <label for="card_number" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Card Information</label>
                            <div class="relative">
                                <input type="text" id="card_number" name="card_number"
                                       class="input-field w-full px-4 py-3.5 rounded-t-xl rounded-b-none text-sm font-mono tracking-wider"
                                       placeholder="4242 4242 4242 4242"
                                       maxlength="19"
                                       autocomplete="cc-number"
                                       required>
                                <!-- Card brand icons -->
                                <div class="card-icons flex items-center gap-1.5">
                                    <div class="w-8 h-5 rounded bg-white/10 flex items-center justify-center">
                                        <span class="text-[9px] font-bold text-blue-400">VISA</span>
                                    </div>
                                    <div class="w-8 h-5 rounded bg-white/10 flex items-center justify-center">
                                        <span class="text-[8px] font-bold text-orange-400">MC</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <input type="text" id="expiry" name="expiry"
                                       class="input-field px-4 py-3.5 rounded-bl-xl border-t-0 border-r-0 text-sm"
                                       placeholder="MM / YY"
                                       maxlength="7"
                                       autocomplete="cc-exp"
                                       required>
                                <input type="text" id="cvc" name="cvc"
                                       class="input-field px-4 py-3.5 rounded-br-xl border-t-0 text-sm"
                                       placeholder="CVC"
                                       maxlength="4"
                                       autocomplete="cc-csc"
                                       required>
                            </div>
                        </div>

                        <!-- Cardholder Name -->
                        <div class="mb-8">
                            <label for="cardholder" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Cardholder Name</label>
                            <input type="text" id="cardholder" name="cardholder"
                                   class="input-field w-full px-4 py-3.5 rounded-xl text-sm"
                                   placeholder="Full name on card"
                                   value="<?= htmlspecialchars($_SESSION['fullname'] ?? '') ?>"
                                   required>
                        </div>

                        <!-- Pay button -->
                        <button type="submit" id="pay-btn"
                                class="pay-btn w-full py-4 rounded-xl font-bold text-base tracking-wide flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Pay RM<?= number_format($total, 2) ?></span>
                        </button>

                        <p class="text-center text-xs text-gray-500 mt-4 flex items-center justify-center gap-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            256-bit SSL encrypted · Simulated gateway
                        </p>
                    </form>

                </div>
            </div>

        </div>
        <?php endif; ?>

    </main>

    <script>
        // ── Auto-format card number ──────────────────────────
        const cardInput = document.getElementById('card_number');
        if (cardInput) {
            cardInput.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '').substring(0, 16);
                e.target.value = v.replace(/(.{4})/g, '$1 ').trim();
            });
        }

        // ── Auto-format expiry ───────────────────────────────
        const expiryInput = document.getElementById('expiry');
        if (expiryInput) {
            expiryInput.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '').substring(0, 4);
                if (v.length >= 3) {
                    v = v.substring(0, 2) + ' / ' + v.substring(2);
                }
                e.target.value = v;
            });
        }

        // ── Prevent double-submit ────────────────────────────
        const payForm = document.getElementById('payment-form');
        const payBtn  = document.getElementById('pay-btn');
        if (payForm && payBtn) {
            payForm.addEventListener('submit', () => {
                payBtn.disabled = true;
                payBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Processing…</span>
                `;
            });
        }
    </script>
