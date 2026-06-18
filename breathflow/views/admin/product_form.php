<?php
/**
 * ARC NEBU-PEN  |  Admin: Product Form (Create / Edit)
 * ─────────────────────────────────────────────────────────────
 * Dual-purpose view — handles both Add and Edit flows:
 *   • Create:  $product === null  →  POST to admin/products/store
 *   • Edit:    $product is array  →  POST to admin/products/update
 *
 * Injected by ProductController::create() and ProductController::edit()
 * ─────────────────────────────────────────────────────────────
 */

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Variables from controller (with safe defaults)
$product    = $product    ?? null;
$errors     = $errors     ?? [];
$csrfToken  = $csrf_token ?? ($_SESSION['csrf_token'] ?? '');

$isEdit     = ($product !== null && !empty($product['product_id']));
$pageTitle  = $isEdit ? 'Edit Product' : 'Add New Product';
$formAction = $isEdit
    ? 'index.php?page=admin/products/update'
    : 'index.php?page=admin/products/store';

// Repopulate field values (survive validation re-render)
$val = [
    'name'        => htmlspecialchars($product['name']        ?? ''),
    'description' => htmlspecialchars($product['description'] ?? ''),
    'price'       => htmlspecialchars((string)($product['price'] ?? '')),
    'stock'       => htmlspecialchars((string)($product['stock'] ?? '')),
    'image'       => $product['image'] ?? '',
];

// Suggested preset names for quick-fill
$presets = ['Mint', 'Berry', 'Citrus', 'Lavender'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?> — ARC Admin</title>
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

        /* ── Field base ── */
        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem;
            padding: 0.8rem 1rem;
            color: #fff;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .field-input::placeholder { color: #6b7280; }
        .field-input:focus {
            border-color: #2ECB80;
            box-shadow: 0 0 0 3px rgba(46,203,128,0.15);
        }
        .field-input.error {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248,113,113,0.15);
        }
        textarea.field-input { resize: vertical; min-height: 120px; }
        select.field-input option { background: #0C2028; }

        /* ── Buttons ── */
        .btn-teal {
            background-color: #2ECB80; color: #040D0F;
            transition: all 0.2s ease-in-out;
        }
        .btn-teal:hover { background-color: #4DD9A8; transform: translateY(-2px); box-shadow: 0 10px 20px -10px rgba(46,203,128,0.5); }
        .btn-ghost {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #d1d5db;
            transition: all 0.2s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.10); color: #fff; }

        /* ── Animations ── */
        .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Image drop zone ── */
        .drop-zone {
            border: 2px dashed rgba(255,255,255,0.15);
            border-radius: 1rem;
            transition: border-color 0.2s, background 0.2s;
            cursor: pointer;
        }
        .drop-zone:hover, .drop-zone.drag-over {
            border-color: #2ECB80;
            background: rgba(46,203,128,0.05);
        }
        .drop-zone.error { border-color: #f87171; }
    </style>
</head>
<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 pt-32 pb-16 max-w-4xl mx-auto w-full">

        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-6 fade-in-up">
            <a href="index.php?page=admin/dashboard"  class="hover:text-teal-400 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="index.php?page=admin/products"   class="hover:text-teal-400 transition-colors">Products</a>
            <span>/</span>
            <span class="text-gray-300"><?= $pageTitle ?></span>
        </div>

        <!-- Page heading -->
        <div class="mb-8 fade-in-up">
            <h1 class="text-3xl font-extrabold text-white"><?= $pageTitle ?></h1>
            <p class="text-gray-400 mt-1 text-sm">
                <?= $isEdit ? 'Update the details for this cartridge.' : 'Add a new flavour cartridge to the ARC catalogue.' ?>
            </p>
        </div>

        <!-- Global error banner -->
        <?php if (!empty($errors)): ?>
        <div class="flex items-start gap-3 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm mb-8 fade-in-up">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div>
                <p class="font-semibold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <?php foreach ($errors as $field => $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <!-- ══════════════════════════════════════════════════════
             FORM
        ══════════════════════════════════════════════════════ -->
        <form action="<?= $formAction ?>"
              method="POST"
              enctype="multipart/form-data"
              id="product-form"
              novalidate>

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <?php if ($isEdit): ?>
            <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
            <?php endif; ?>

            <div class="glass-panel rounded-3xl p-8 lg:p-10 space-y-8 fade-in-up" style="animation-delay:0.05s;">

                <!-- ── Product Name ─────────────────────────────── -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-200 mb-2">
                        Product Name <span class="text-red-400">*</span>
                    </label>
                    <!-- Quick preset chips -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php foreach ($presets as $p): ?>
                        <button type="button"
                                onclick="document.getElementById('name').value='<?= $p ?> Pod'"
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/10 border border-teal-500/20 text-teal-400 hover:bg-teal-500/20 transition-colors">
                            + <?= $p ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <input type="text"
                           id="name"
                           name="name"
                           value="<?= $val['name'] ?>"
                           placeholder="e.g. Mint Pod"
                           maxlength="100"
                           class="field-input <?= isset($errors['name']) ? 'error' : '' ?>">
                    <?php if (isset($errors['name'])): ?>
                    <p class="text-red-400 text-xs mt-1.5"><?= htmlspecialchars($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- ── Price & Stock (side by side) ─────────────── -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-200 mb-2">
                            Price (RM) <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold select-none">RM</span>
                            <input type="number"
                                   id="price"
                                   name="price"
                                   value="<?= $val['price'] ?>"
                                   placeholder="19.00"
                                   min="0.01"
                                   step="0.01"
                                   class="field-input pl-12 <?= isset($errors['price']) ? 'error' : '' ?>">
                        </div>
                        <?php if (isset($errors['price'])): ?>
                        <p class="text-red-400 text-xs mt-1.5"><?= htmlspecialchars($errors['price']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-200 mb-2">
                            Stock Quantity <span class="text-red-400">*</span>
                        </label>
                        <input type="number"
                               id="stock"
                               name="stock"
                               value="<?= $val['stock'] ?>"
                               placeholder="100"
                               min="0"
                               step="1"
                               class="field-input <?= isset($errors['stock']) ? 'error' : '' ?>">
                        <?php if (isset($errors['stock'])): ?>
                        <p class="text-red-400 text-xs mt-1.5"><?= htmlspecialchars($errors['stock']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ── Description ──────────────────────────────── -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-200 mb-2">
                        Description <span class="text-red-400">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              placeholder="Describe the sensory profile — aroma, cooling effect, intended use..."
                              class="field-input <?= isset($errors['description']) ? 'error' : '' ?>"><?= $val['description'] ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                    <p class="text-red-400 text-xs mt-1.5"><?= htmlspecialchars($errors['description']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- ── Image Upload ─────────────────────────────── -->
                <div>
                    <label class="block text-sm font-semibold text-gray-200 mb-2">
                        Product Image <?= !$isEdit ? '<span class="text-red-400">*</span>' : '<span class="text-gray-500 text-xs font-normal">(leave blank to keep current)</span>' ?>
                    </label>

                    <!-- Current image preview (edit mode) -->
                    <?php if ($isEdit && !empty($val['image'])): ?>
                    <div class="mb-4 flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10">
                        <img src="<?= htmlspecialchars($val['image']) ?>"
                             alt="Current image"
                             class="w-16 h-16 rounded-xl object-cover">
                        <div>
                            <p class="text-sm text-gray-300 font-medium">Current image</p>
                            <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($val['image']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Drop zone -->
                    <div id="drop-zone"
                         class="drop-zone <?= isset($errors['image']) ? 'error' : '' ?> p-8 text-center"
                         onclick="document.getElementById('image').click()">
                        <div id="drop-preview" class="hidden mb-4">
                            <img id="preview-img" src="" alt="Preview" class="mx-auto w-24 h-24 rounded-2xl object-cover shadow-lg">
                        </div>
                        <div id="drop-placeholder">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-400 font-medium">Click to upload or drag &amp; drop</p>
                            <p class="text-xs text-gray-600 mt-1">JPG, PNG, WEBP — max 2 MB</p>
                        </div>
                        <p id="drop-filename" class="hidden text-sm text-teal-400 font-semibold mt-2"></p>
                    </div>
                    <input type="file"
                           id="image"
                           name="image"
                           accept=".jpg,.jpeg,.png,.webp"
                           class="sr-only">
                    <?php if (isset($errors['image'])): ?>
                    <p class="text-red-400 text-xs mt-1.5"><?= htmlspecialchars($errors['image']) ?></p>
                    <?php endif; ?>
                </div>

            </div><!-- /glass-panel -->

            <!-- ── Form Actions ─────────────────────────────────── -->
            <div class="flex flex-col sm:flex-row gap-4 mt-6 fade-in-up" style="animation-delay:0.1s;">
                <button type="submit"
                        class="btn-teal flex-1 py-4 px-8 rounded-xl font-bold text-base text-center flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= $isEdit ? 'Save Changes' : 'Add Product' ?>
                </button>
                <a href="index.php?page=admin/products"
                   class="btn-ghost flex-1 py-4 px-8 rounded-xl font-bold text-base text-center">
                    Cancel
                </a>
            </div>

        </form>
    </main>

    <script>
        // ── Image drop zone + preview ────────────────────────────
        const input    = document.getElementById('image');
        const zone     = document.getElementById('drop-zone');
        const preview  = document.getElementById('drop-preview');
        const previewImg = document.getElementById('preview-img');
        const placeholder = document.getElementById('drop-placeholder');
        const filename = document.getElementById('drop-filename');

        function handleFile(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                filename.textContent = file.name;
                filename.classList.remove('hidden');
                zone.style.borderColor = '#2ECB80';
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', () => handleFile(input.files[0]));

        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                // Transfer to the actual file input
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                handleFile(file);
            }
        });
    </script>
</body>
</html>
