<?php
/**
 * ARC NEBU-PEN  |  Admin: Product Management
 * ─────────────────────────────────────────────────────────────
 * Accessed via:  index.php?page=admin/products
 * FR-01: RBAC — admin and staff only (enforced by controller)
 * ─────────────────────────────────────────────────────────────
 */

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error']   ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// $products is injected by ProductController::adminIndex()
$products = $products ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products — ARC Admin</title>
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
        .page-bg { background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%); }
        .glass-panel { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(12px); }
        .btn-teal { background-color: #2ECB80; color: #040D0F; transition: all 0.2s ease-in-out; }
        .btn-teal:hover { background-color: #4DD9A8; transform: translateY(-2px); box-shadow: 0 10px 20px -10px rgba(46,203,128,0.5); }
        .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .admin-table th { padding: 0.75rem 1rem; color: #6b7280; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .admin-table td { padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,0.02); }
    </style>
</head>
<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 pt-32 pb-16 max-w-7xl mx-auto w-full space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 fade-in-up">
            <div>
                <a href="index.php?page=admin/dashboard" class="text-xs text-gray-500 hover:text-teal-400 transition-colors flex items-center gap-1 mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Admin Dashboard
                </a>
                <h1 class="text-3xl font-extrabold text-white">Manage Products</h1>
                <p class="text-gray-400 mt-1"><?= count($products) ?> cartridge<?= count($products) !== 1 ? 's' : '' ?> in catalogue</p>
            </div>
            <a href="index.php?page=admin/products/create" class="btn-teal inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-sm shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </a>
        </div>

        <!-- Flash messages -->
        <?php if ($flashSuccess): ?>
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-teal-300 text-sm fade-in-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= htmlspecialchars($flashSuccess) ?>
        </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm fade-in-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?= htmlspecialchars($flashError) ?>
        </div>
        <?php endif; ?>

        <!-- Product Table -->
        <div class="glass-panel rounded-3xl overflow-hidden fade-in-up" style="animation-delay:0.1s;">
            <?php if (empty($products)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="w-16 h-16 rounded-2xl bg-teal-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No products yet</h3>
                <p class="text-gray-400 text-sm mb-6">Add your first cartridge to the catalogue.</p>
                <a href="index.php?page=admin/products/create" class="btn-teal px-6 py-3 rounded-xl font-bold text-sm">Add First Product</a>
            </div>
            <?php else: ?>
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Product</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Stock</th>
                        <th class="text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-4">
                                <?php if (!empty($product['image'])): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                     class="w-12 h-12 rounded-xl object-cover bg-white/5">
                                <?php else: ?>
                                <div class="w-12 h-12 rounded-xl bg-teal-500/10 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-teal-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-semibold text-white text-sm"><?= htmlspecialchars($product['name']) ?></p>
                                    <p class="text-gray-500 text-xs mt-0.5 line-clamp-1"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="text-gray-300 font-mono text-sm">RM<?= number_format((float)$product['price'], 2) ?></td>
                        <td>
                            <?php
                            $stock = (int)$product['stock'];
                            $stockClass = $stock > 20 ? 'text-teal-400' : ($stock > 5 ? 'text-yellow-400' : 'text-red-400');
                            ?>
                            <span class="font-mono text-sm <?= $stockClass ?>"><?= $stock ?></span>
                            <span class="text-gray-500 text-xs ml-1">units</span>
                        </td>
                        <td class="text-right pr-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="index.php?page=admin/products/edit&id=<?= (int)$product['product_id'] ?>"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 text-gray-300 hover:text-white text-xs font-semibold transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                </a>
                                <form action="index.php?page=admin/products/destroy" method="POST" class="inline m-0"
                                      onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($product['name'])) ?>? This cannot be undone.')">
                                    <input type="hidden" name="csrf_token"  value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-xs font-semibold transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </main>
</body>
</html>
