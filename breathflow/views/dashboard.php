<?php
require_once __DIR__ . '/../models/Bundle.php';
require_once __DIR__ . '/../models/Subscription.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$fullname = $_SESSION['fullname'] ?? 'User';
$role = $_SESSION['role'] ?? 'customer';

$bundleModel = new Bundle();
$myBundle = $bundleModel->getByUser($userId);

$subModel = new Subscription();
$mySub = $subModel->getByUser($userId);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard — ARC NEBU-PEN</title>
    <meta name="description" content="Manage your ARC NEBU-PEN bundle and subscription." />

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

        .glass-sub-panel {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1rem;
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

        /* Ghost Button */
        .btn-ghost {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s ease;
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.4);
        }

        /* Danger Button */
        .btn-danger {
            background: rgba(248,113,113,0.1);
            color: #f87171;
            border: 1px solid rgba(248,113,113,0.3);
            transition: all 0.2s ease;
        }
        .btn-danger:hover {
            background: rgba(248,113,113,0.2);
        }

        /* Table Styles */
        .admin-table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.5);
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .admin-table td {
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.8);
            font-size: 0.875rem;
        }

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
         MAIN DASHBOARD
    ═══════════════════════════════════════════════════════ -->
    <main class="flex-1 px-6 lg:px-16 pt-32 pb-12 max-w-7xl mx-auto w-full space-y-12">
        
        <!-- Welcome Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 fade-in-up" style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">
                    Hi, <span class="text-teal-500"><?= htmlspecialchars($fullname) ?></span> 👋
                </h1>
                <p class="text-gray-400">Welcome back to your ARC dashboard.</p>
            </div>
            
            <?php if ($role === 'admin'): ?>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-widest">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                Administrator
            </div>
            <?php endif; ?>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             CUSTOMER PANEL
        ═══════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 fade-in-up" style="animation-delay: 0.2s;">
            
            <!-- My Bundle -->
            <?php if ($myBundle): ?>
            <div class="glass-panel rounded-3xl p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-white">My Bundle</h2>
                </div>
                
                <div class="mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold tracking-wide uppercase">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Order Status: Processing Shipment
                    </span>
                </div>

                <!-- Display saved bundle cartridges -->
                <div class="flex items-center justify-center gap-6 flex-1 py-4">
                    <!-- Pod 1 -->
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-36 rounded-full border border-teal-500/20 bg-gradient-to-b from-indigo-900/40 to-transparent flex items-center justify-center shadow-[0_0_20px_rgba(46,203,128,0.1)] overflow-hidden relative">
                            <?php if ($myBundle['flavor1_image']): ?>
                                <img src="<?= htmlspecialchars($myBundle['flavor1_image']) ?>" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-screen" />
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-gray-400"><?= htmlspecialchars($myBundle['flavor1_name']) ?></span>
                    </div>
                    <!-- Pod 2 -->
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-36 rounded-full border border-teal-500/20 bg-gradient-to-b from-green-900/40 to-transparent flex items-center justify-center shadow-[0_0_20px_rgba(46,203,128,0.1)] overflow-hidden relative">
                            <?php if ($myBundle['flavor2_image']): ?>
                                <img src="<?= htmlspecialchars($myBundle['flavor2_image']) ?>" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-screen" />
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-gray-400"><?= htmlspecialchars($myBundle['flavor2_name']) ?></span>
                    </div>
                    <!-- Pod 3 -->
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-36 rounded-full border border-teal-500/20 bg-gradient-to-b from-orange-900/40 to-transparent flex items-center justify-center shadow-[0_0_20px_rgba(46,203,128,0.1)] overflow-hidden relative">
                            <?php if ($myBundle['flavor3_image']): ?>
                                <img src="<?= htmlspecialchars($myBundle['flavor3_image']) ?>" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-screen" />
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-gray-400"><?= htmlspecialchars($myBundle['flavor3_name']) ?></span>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="glass-panel rounded-3xl p-8 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-full bg-teal-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-white mb-2">No Active Bundle</h2>
                <p class="text-gray-400 text-sm mb-6">You haven't built your custom cartridge bundle yet.</p>
                <a href="index.php?page=bundle_builder" class="btn-teal px-6 py-3 rounded-xl font-semibold text-sm">Build Your Bundle</a>
            </div>
            <?php endif; ?>

            <!-- My Subscription -->
            <?php if ($mySub): ?>
            <div class="glass-panel rounded-3xl p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-white">My Subscription</h2>
                    <?php if ($mySub['status'] === 'active'): ?>
                        <span class="px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold tracking-wide uppercase">Active</span>
                    <?php elseif ($mySub['status'] === 'paused'): ?>
                        <span class="px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs font-bold tracking-wide uppercase">Paused</span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold tracking-wide uppercase">Cancelled</span>
                    <?php endif; ?>
                </div>
                
                <div class="glass-sub-panel p-6 mb-8 flex-1">
                    <h3 class="text-lg font-bold text-white mb-1">Core Club (<?= htmlspecialchars(ucfirst($mySub['plan'])) ?>)</h3>
                    <?php $subPrice = ($mySub['plan'] === 'bimonthly') ? 'RM85.00' : 'RM45.00'; ?>
                    <p class="text-teal-400 text-sm font-semibold mb-6"><?= htmlspecialchars((string)($mySub['discount'] ?? 20)) ?>% Discount Applied &middot; <?= $subPrice ?></p>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Next Delivery</p>
                            <p class="text-base text-gray-200 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?= htmlspecialchars(date('d M Y', strtotime($mySub['next_delivery_date']))) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Payment Method</p>
                            <p class="text-base text-gray-200">•••• •••• •••• 4242</p>
                        </div>
                    </div>
                </div>

                <?php if ($mySub['status'] !== 'cancelled'): ?>
                <div class="grid grid-cols-2 gap-4">
                    <form action="index.php?page=subscription/status" method="POST" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>" />
                        <input type="hidden" name="status" value="<?= $mySub['status'] === 'active' ? 'paused' : 'active' ?>" />
                        <button type="submit" class="btn-ghost w-full py-3 rounded-xl font-semibold text-sm"><?= $mySub['status'] === 'active' ? 'Pause Sub' : 'Resume Sub' ?></button>
                    </form>
                    <form action="index.php?page=subscription/status" method="POST" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>" />
                        <input type="hidden" name="status" value="cancelled" />
                        <button type="submit" class="btn-danger w-full py-3 rounded-xl font-semibold text-sm">Cancel Sub</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="glass-panel rounded-3xl p-8 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-full bg-indigo-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-white mb-2">No Active Subscription</h2>
                <p class="text-gray-400 text-sm mb-6">Join the Core Club for automated deliveries and a 20% discount.</p>
                <a href="index.php?page=subscription" class="btn-ghost px-6 py-3 rounded-xl font-semibold text-sm hover:text-teal-400">Join Core Club</a>
            </div>
            <?php endif; ?>

        </div>

        <!-- ═══════════════════════════════════════════════════════
             ADMIN PANEL (Conditional)
        ═══════════════════════════════════════════════════════ -->
        <?php if ($role === 'admin' || $role === 'manager'): ?>
        <div class="mt-16 fade-in-up" style="animation-delay: 0.3s;">
            <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Admin Workspace
            </h2>
            <div class="mb-8">
                <a href="index.php?page=admin/dashboard" class="btn-teal inline-block px-6 py-3 rounded-xl font-semibold text-sm">Go to Internal Dashboard &rarr;</a>
            </div>
            <!-- Old static tables removed, redirecting to dedicated backend -->
        </div>
        <?php endif; ?>

    </main>
