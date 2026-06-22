<?php
/**
 * ARC NEBU-PEN  |  Admin Product Form (Create / Edit)
 * ─────────────────────────────────────────────────────────────
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$isEdit = !empty($product['product_id']);
$formAction = $isEdit ? 'index.php?page=admin/products/update' : 'index.php?page=admin/products/store';
$pageTitle = $isEdit ? 'Edit Product' : 'Add New Product';

// Safe extraction of fields for pre-population
$pName  = $product['name'] ?? '';
$pDesc  = $product['description'] ?? '';
$pPrice = $product['price'] ?? '';
$pStock = $product['stock'] ?? '';
$pImg   = $product['image'] ?? '';

$errs = $errors ?? [];
$generalError = $error ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?> — ARC NEBU-PEN Admin</title>
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
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
        }
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #2ECB80;
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 0 3px rgba(46,203,128,0.1);
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

    <main class="flex-1 px-4 sm:px-6 lg:px-16 pt-32 pb-16 max-w-3xl mx-auto w-full space-y-8">
        
        <!-- Header -->
        <div class="fade-in-up">
            <div class="flex items-center gap-3 mb-2">
                <a href="index.php?page=admin/products" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-extrabold text-white tracking-tight"><?= $pageTitle ?></h1>
            </div>
            <p class="text-gray-400">Fill out the details below to <?= $isEdit ? 'update the' : 'create a new' ?> product.</p>
        </div>

        <?php if ($generalError): ?>
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl fade-in-up">
                <?= htmlspecialchars($generalError) ?>
            </div>
        <?php endif; ?>

        <!-- Form Panel -->
        <div class="glass-panel rounded-2xl p-6 md:p-8 fade-in-up" style="animation-delay: 0.1s;">
            <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars((string)$product['product_id']) ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Product Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="<?= htmlspecialchars($pName) ?>" required
                               class="form-input <?= isset($errs['name']) ? 'border-red-500' : '' ?>" 
                               placeholder="e.g. Mint Rush Cartridge">
                        <?php if (isset($errs['name'])): ?>
                            <p class="mt-1.5 text-sm text-red-400"><?= htmlspecialchars($errs['name']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Price (RM) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-medium">RM</span>
                            </div>
                            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars((string)$pPrice) ?>" required
                                   class="form-input pl-12 <?= isset($errs['price']) ? 'border-red-500' : '' ?>" 
                                   placeholder="0.00">
                        </div>
                        <?php if (isset($errs['price'])): ?>
                            <p class="mt-1.5 text-sm text-red-400"><?= htmlspecialchars($errs['price']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Stock Level <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" value="<?= htmlspecialchars((string)$pStock) ?>" required min="0"
                               class="form-input <?= isset($errs['stock']) ? 'border-red-500' : '' ?>" 
                               placeholder="e.g. 50">
                        <?php if (isset($errs['stock'])): ?>
                            <p class="mt-1.5 text-sm text-red-400"><?= htmlspecialchars($errs['stock']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Description <span class="text-red-400">*</span></label>
                        <textarea name="description" rows="4" required
                                  class="form-input resize-y <?= isset($errs['description']) ? 'border-red-500' : '' ?>" 
                                  placeholder="Describe the product's features, flavor profile, etc."><?= htmlspecialchars($pDesc) ?></textarea>
                        <?php if (isset($errs['description'])): ?>
                            <p class="mt-1.5 text-sm text-red-400"><?= htmlspecialchars($errs['description']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Image Upload -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">
                            Product Image <?= $isEdit ? '<span class="text-gray-500 font-normal">(Leave blank to keep current)</span>' : '<span class="text-red-400">*</span>' ?>
                        </label>
                        <div class="flex items-center gap-6">
                            <?php if ($isEdit && $pImg): ?>
                                <div class="w-16 h-16 rounded-xl bg-white/5 border border-white/10 overflow-hidden shrink-0">
                                    <img src="<?= htmlspecialchars($pImg) ?>" alt="Current Image" class="w-full h-full object-cover">
                                </div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <input type="file" name="image" accept="image/png, image/jpeg, image/webp" <?= !$isEdit ? 'required' : '' ?>
                                       class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all cursor-pointer border border-white/10 rounded-xl p-1.5 bg-black/20">
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Supported formats: JPG, PNG, WEBP. Max size: 2MB.</p>
                        <?php if (isset($errs['image'])): ?>
                            <p class="mt-1.5 text-sm text-red-400"><?= htmlspecialchars($errs['image']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 flex justify-end gap-3">
                    <a href="index.php?page=admin/products" class="px-6 py-3 rounded-xl font-semibold text-gray-300 hover:text-white hover:bg-white/5 transition-colors text-sm">Cancel</a>
                    <button type="submit" class="btn-teal px-8 py-3 rounded-xl font-bold text-sm">
                        <?= $isEdit ? 'Save Changes' : 'Create Product' ?>
                    </button>
                </div>
            </form>
        </div>

    </main>

    <?php require __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
