<?php
/**
 * ARC NEBU-PEN  |  Admin Manage Products
 * ─────────────────────────────────────────────────────────────
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$products = $products ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products — ARC NEBU-PEN Admin</title>
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

    <main class="flex-1 px-4 sm:px-6 lg:px-16 pt-32 pb-16 max-w-7xl mx-auto w-full space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 fade-in-up">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="index.php?page=admin/dashboard" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Manage Products</h1>
                </div>
                <p class="text-gray-400">View, add, edit, or remove product cartridges from the catalog.</p>
            </div>
            
            <a href="index.php?page=admin/products/create" class="btn-teal inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Product
            </a>
        </div>

        <!-- Data Table -->
        <div class="glass-panel rounded-2xl overflow-hidden fade-in-up" style="animation-delay: 0.1s;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-gray-400 font-semibold">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Product Name</th>
                            <th class="px-6 py-4">Price (RM)</th>
                            <th class="px-6 py-4">Stock Level</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-sm">
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No products found in the database.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <?php 
                                    $isLowStock = (int)$p['stock'] < 5;
                                ?>
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4 text-gray-500">#<?= htmlspecialchars((string)$p['product_id']) ?></td>
                                    <td class="px-6 py-4 font-semibold text-gray-200">
                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($p['image'])): ?>
                                                <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 overflow-hidden flex-shrink-0">
                                                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="Img" class="w-full h-full object-cover">
                                                </div>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex-shrink-0"></div>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($p['name']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">RM<?= number_format((float)$p['price'], 2) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($isLowStock): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-500/10 border border-red-500/20 text-red-400 font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                <?= (int)$p['stock'] ?> Left
                                            </span>
                                        <?php else: ?>
                                            <span class="text-teal-400 font-medium"><?= (int)$p['stock'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="index.php?page=admin/products/edit&id=<?= $p['product_id'] ?>" class="text-gray-400 hover:text-teal-400 transition-colors font-medium">
                                            Edit
                                        </a>
                                        
                                        <form action="index.php?page=admin/products/destroy" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                            <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                                            <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
    
    <?php require __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
