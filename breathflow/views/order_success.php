<?php
// Ensure we have an order to show, otherwise fallback to dashboard
$orderItems = $_SESSION['last_order'] ?? null;
$orderId = 'ARC-' . strtoupper(substr(uniqid(), -5));
$orderDate = date('d M Y, h:i A');

$subtotal = 0.0;
$discount = 0.0;

if ($orderItems) {
    foreach ($orderItems as $item) {
        $lineTotal = $item['price'] * $item['qty'];
        $subtotal += $lineTotal;
        if ($item['type'] === 'subscription') {
            $discount += $lineTotal * 0.20;
        }
    }
}
$total = $subtotal - $discount;

// Clear last_order so it doesn't show again on refresh
unset($_SESSION['last_order']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation — ARC NEBU-PEN</title>
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

    <main class="flex-1 px-4 sm:px-6 lg:px-16 pt-32 pb-16 w-full flex items-center justify-center">

        <?php if (!$orderItems): ?>
            <!-- No Recent Order State -->
            <div class="glass-panel rounded-3xl p-12 text-center max-w-lg w-full fade-in-up">
                <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">No Recent Orders Found</h2>
                <p class="text-gray-400 mb-8">We couldn't find details for your most recent purchase.</p>
                <a href="index.php?page=dashboard" class="btn-teal inline-flex px-8 py-3.5 rounded-xl font-bold text-sm">
                    Go to Your Dashboard
                </a>
            </div>
        <?php else: ?>
            <!-- Success Invoice View -->
            <div class="glass-panel rounded-3xl p-8 md:p-12 max-w-2xl w-full fade-in-up">
                <!-- Header -->
                <div class="text-center mb-10">
                    <div class="w-20 h-20 rounded-full bg-teal-500/15 flex items-center justify-center mx-auto mb-6 shadow-[0_0_30px_rgba(46,203,128,0.2)]">
                        <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-white mb-2">Thank you for your purchase!</h1>
                    <p class="text-gray-400">Your order has been confirmed and is now being processed.</p>
                </div>

                <!-- Order Details -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-8">
                    <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Order ID</p>
                            <p class="text-white font-bold tracking-wider"><?= htmlspecialchars($orderId) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Date</p>
                            <p class="text-white font-medium"><?= htmlspecialchars($orderDate) ?></p>
                        </div>
                    </div>

                    <!-- Itemized List -->
                    <div class="space-y-4">
                        <?php foreach ($orderItems as $item): ?>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-white font-semibold text-sm">
                                        <?= htmlspecialchars($item['name']) ?>
                                        <span class="text-gray-500 font-normal">x<?= (int)$item['qty'] ?></span>
                                    </p>
                                    <?php if ($item['type'] === 'bundle' && !empty($item['meta'])): ?>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">
                                            <?= htmlspecialchars($item['meta']['flavor1'] ?? '') ?>, 
                                            <?= htmlspecialchars($item['meta']['flavor2'] ?? '') ?>, 
                                            <?= htmlspecialchars($item['meta']['flavor3'] ?? '') ?>
                                        </p>
                                    <?php elseif ($item['type'] === 'subscription'): ?>
                                        <p class="text-xs text-teal-400 font-medium truncate mt-0.5">Core Club Auto-renew</p>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-white font-bold">RM<?= number_format($item['price'] * $item['qty'], 2) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Totals -->
                <div class="space-y-3 mb-10">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-gray-300">RM<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <?php if ($discount > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-emerald-400">Core Club 20% Off</span>
                        <span class="text-emerald-400">−RM<?= number_format($discount, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Shipping</span>
                        <span class="text-teal-400 font-medium">Free</span>
                    </div>
                    <div class="border-t border-white/10 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-400">Total Paid</span>
                            <span class="text-2xl font-black text-white">RM<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center">
                    <a href="index.php?page=dashboard" class="btn-teal inline-flex px-10 py-4 rounded-xl font-bold text-sm tracking-wide w-full sm:w-auto justify-center">
                        Go to Your Dashboard
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>
