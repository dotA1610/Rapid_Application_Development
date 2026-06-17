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
    <main class="flex-1 px-6 lg:px-16 py-12 max-w-7xl mx-auto w-full space-y-12">
        
        <!-- Welcome Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 fade-in-up" style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">
                    Hi, <span class="text-teal-500"><?= htmlspecialchars($user['name'] ?? 'Aiman') ?></span> 👋
                </h1>
                <p class="text-gray-400">Welcome back to your ARC dashboard.</p>
            </div>
            
            <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
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
            <div class="glass-panel rounded-3xl p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-white">My Bundle</h2>
                    <a href="index.php?page=bundle_builder" class="text-sm font-semibold text-teal-400 hover:text-teal-300">Edit Bundle</a>
                </div>
                
                <!-- Display saved bundle cartridges -->
                <div class="flex items-center justify-center gap-6 flex-1 py-4">
                    <!-- Pod 1 -->
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-36 rounded-full border border-teal-500/20 bg-gradient-to-b from-indigo-900/40 to-transparent flex items-center justify-center shadow-[0_0_20px_rgba(46,203,128,0.1)]"></div>
                        <span class="text-xs font-semibold text-gray-400">Lavender</span>
                    </div>
                    <!-- Pod 2 -->
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-36 rounded-full border border-teal-500/20 bg-gradient-to-b from-green-900/40 to-transparent flex items-center justify-center shadow-[0_0_20px_rgba(46,203,128,0.1)]"></div>
                        <span class="text-xs font-semibold text-gray-400">Mint</span>
                    </div>
                    <!-- Pod 3 -->
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-36 rounded-full border border-teal-500/20 bg-gradient-to-b from-orange-900/40 to-transparent flex items-center justify-center shadow-[0_0_20px_rgba(46,203,128,0.1)]"></div>
                        <span class="text-xs font-semibold text-gray-400">Citrus</span>
                    </div>
                </div>
            </div>

            <!-- My Subscription -->
            <div class="glass-panel rounded-3xl p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-white">My Subscription</h2>
                    <span class="px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold tracking-wide uppercase">
                        Active
                    </span>
                </div>
                
                <div class="glass-sub-panel p-6 mb-8 flex-1">
                    <h3 class="text-lg font-bold text-white mb-1">Core Club (Monthly)</h3>
                    <p class="text-teal-400 text-sm font-semibold mb-6">20% Discount Applied</p>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Next Delivery</p>
                            <p class="text-base text-gray-200 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                20 May 2026
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Payment Method</p>
                            <p class="text-base text-gray-200">•••• •••• •••• 4242</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <form action="index.php?page=subscription&action=pause" method="POST" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>" />
                        <button type="submit" class="btn-ghost w-full py-3 rounded-xl font-semibold text-sm">Pause Sub</button>
                    </form>
                    <form action="index.php?page=subscription&action=cancel" method="POST" class="m-0">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>" />
                        <button type="submit" class="btn-danger w-full py-3 rounded-xl font-semibold text-sm">Cancel Sub</button>
                    </form>
                </div>
            </div>

        </div>

        <!-- ═══════════════════════════════════════════════════════
             ADMIN PANEL (Conditional)
        ═══════════════════════════════════════════════════════ -->
        <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
        <div class="mt-16 fade-in-up" style="animation-delay: 0.3s;">
            <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Admin Workspace
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Product Catalog Management -->
                <div class="glass-panel rounded-3xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white">Product Catalog</h3>
                        <button class="text-sm font-semibold text-teal-400 hover:text-teal-300 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Product
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left admin-table border-collapse">
                            <thead>
                                <tr>
                                    <th class="w-2/5">Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-medium text-white">Lavender Pod</td>
                                    <td>RM19.00</td>
                                    <td><span class="text-teal-400 font-mono">145</span></td>
                                    <td class="text-right">
                                        <button class="text-gray-400 hover:text-white mr-2"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        <form action="index.php?controller=product&action=delete" method="POST" class="inline m-0">
                                            <input type="hidden" name="id" value="1" />
                                            <button type="submit" class="text-red-400 hover:text-red-300"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-white">Mint Pod</td>
                                    <td>RM19.00</td>
                                    <td><span class="text-yellow-400 font-mono">12</span></td>
                                    <td class="text-right">
                                        <button class="text-gray-400 hover:text-white mr-2"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        <form action="index.php?controller=product&action=delete" method="POST" class="inline m-0">
                                            <input type="hidden" name="id" value="2" />
                                            <button type="submit" class="text-red-400 hover:text-red-300"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Subscription Management -->
                <div class="glass-panel rounded-3xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white">Recent Subscriptions</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left admin-table border-collapse">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-medium text-white">Sarah Jenkins</td>
                                    <td>Monthly</td>
                                    <td><span class="inline-block w-2 h-2 rounded-full bg-teal-500 mr-2"></span>Active</td>
                                    <td class="text-right">
                                        <form action="index.php?controller=subscription&action=cancel_user" method="POST" class="inline m-0">
                                            <input type="hidden" name="sub_id" value="101" />
                                            <button type="submit" class="text-xs font-semibold text-gray-400 hover:text-red-400 transition-colors border border-gray-600 hover:border-red-400 rounded px-2 py-1">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-white">Michael Chen</td>
                                    <td>Every 2 Months</td>
                                    <td><span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>Paused</td>
                                    <td class="text-right">
                                        <form action="index.php?controller=subscription&action=activate_user" method="POST" class="inline m-0">
                                            <input type="hidden" name="sub_id" value="102" />
                                            <button type="submit" class="text-xs font-semibold text-gray-400 hover:text-teal-400 transition-colors border border-gray-600 hover:border-teal-400 rounded px-2 py-1">Resume</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <?php endif; ?>

    </main>
</body>
</html>
