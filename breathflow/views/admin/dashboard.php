<?php
/**
 * ARC NEBU-PEN  |  Admin / Manager Dashboard
 * ─────────────────────────────────────────────────────────────
 * FR-01: RBAC — only 'admin' and 'manager' roles reach this view.
 * Accessed via:  index.php?page=admin/dashboard
 * ─────────────────────────────────────────────────────────────
 */

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$adminName = $_SESSION['fullname'] ?? 'Admin';
$adminRole = $_SESSION['role']     ?? 'admin';

// Variables injected by ProductController::adminDashboard()
$totalProducts = $total_products ?? 0;
$activeSubs    = $active_subs    ?? 0;
$totalUsers    = $total_users    ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard — ARC NEBU-PEN</title>
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
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
        }
        .metric-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .metric-card:hover {
            border-color: rgba(46,203,128,0.3);
            box-shadow: 0 0 24px rgba(46,203,128,0.08);
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
        .btn-ghost {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #d1d5db;
            transition: all 0.2s ease-in-out;
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.10);
            color: #fff;
        }
        .fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 pt-32 pb-16 max-w-7xl mx-auto w-full space-y-12">

        <!-- ═══════════════════════════════════════════════════════
             PAGE HEADER
        ═══════════════════════════════════════════════════════ -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 fade-in-up">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-teal-500 mb-2">Internal Workspace</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white">
                    Hi, <span class="text-teal-400"><?= htmlspecialchars(explode(' ', $adminName)[0]) ?></span> 👋
                </h1>
                <p class="text-gray-400 mt-1">Here's an overview of your ARC system.</p>
            </div>
            <div class="flex items-center gap-3">
                <?php
                $badgeClass = match($adminRole) {
                    'admin'   => 'bg-amber-500/20 text-amber-400',
                    'manager' => 'bg-blue-500/20 text-blue-400',
                    default   => 'bg-gray-500/20 text-gray-400',
                };
                $badgeLabel = strtoupper($adminRole);
                ?>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full <?= $badgeClass ?> text-xs font-bold tracking-widest uppercase border border-current/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <?= $badgeLabel ?>
                </span>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             METRIC CARDS (Live DB counts)
        ═══════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 fade-in-up" style="animation-delay: 0.1s;">

            <!-- Card 1: Total Products -->
            <div class="metric-card rounded-3xl p-8 flex flex-col gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-black text-white mb-1"><?= number_format($totalProducts) ?></p>
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Total Products</p>
                </div>
                <a href="index.php?page=admin/products" class="mt-auto text-xs font-semibold text-teal-400 hover:text-teal-300 transition-colors flex items-center gap-1">
                    Manage Products
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Card 2: Active Subscriptions -->
            <div class="metric-card rounded-3xl p-8 flex flex-col gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-black text-white mb-1"><?= number_format($activeSubs) ?></p>
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Active Subscriptions</p>
                </div>
                <a href="index.php?page=admin/subscriptions" class="mt-auto text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                    Manage Subscriptions
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Card 3: User Accounts -->
            <div class="metric-card rounded-3xl p-8 flex flex-col gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-4xl font-black text-white mb-1"><?= number_format($totalUsers) ?></p>
                    <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">User Accounts</p>
                </div>
                <span class="mt-auto text-xs font-semibold text-gray-500 uppercase tracking-wider">All Roles</span>
            </div>

        </div>



    </main>
