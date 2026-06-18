<?php

/**
 * ARC NEBU-PEN  |  Controller: Product
 * ─────────────────────────────────────────────────────────────
 * Public routes  (no auth required)
 *   index()   — list all available products (Sensory Profiles page)
 *   show()    — single product detail
 *
 * Admin routes  (requireAdmin guard applied)
 *   adminIndex()  — list all products with stock + actions
 *   create()      — show "Add Product" form
 *   store()       — POST: insert new product
 *   edit()        — show "Edit Product" form
 *   update()      — POST: update existing product
 *   destroy()     — POST: delete product
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/AuthController.php';

class ProductController
{
    private Product        $productModel;
    private AuthController $auth;

    /** Maximum image upload size: 2 MB */
    private const MAX_IMAGE_BYTES = 2 * 1024 * 1024;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->auth         = new AuthController();
    }

    // ──────────────────────────────────────────────────────────
    //  PUBLIC ROUTES
    // ──────────────────────────────────────────────────────────

    /**
     * GET /products  — Sensory Profiles catalogue (in-stock only).
     */
    public function index(): void
    {
        $products = $this->productModel->getAvailable();

        $this->renderView('products', ['products' => $products]);
    }

    /**
     * GET /products/show?id=X  — Single product detail.
     */
    public function show(): void
    {
        $id      = $this->resolveIntParam('id');
        $product = $this->productModel->getById($id);

        if ($product === false) {
            $this->abort404('Product not found.');
        }

        $this->renderView('product_detail', ['product' => $product]);
    }

    // ──────────────────────────────────────────────────────────
    //  ADMIN ROUTES
    // ──────────────────────────────────────────────────────────

    /**
     * GET /admin/dashboard  — Manager/Admin dashboard.
     */
    public function adminDashboard(): void
    {
        $this->auth->requireRoles(['admin', 'manager']);

        $db = Database::getInstance();

        $totalProducts = count($this->productModel->getAll());

        $stmtSubs = $db->query("SELECT COUNT(*) FROM subscriptions WHERE status = 'active'");
        $activeSubs = (int) $stmtSubs->fetchColumn();

        $stmtUsers = $db->query("SELECT COUNT(*) FROM users");
        $totalUsers = (int) $stmtUsers->fetchColumn();

        $this->renderView('admin/dashboard', [
            'total_products' => $totalProducts,
            'active_subs'    => $activeSubs,
            'total_users'    => $totalUsers,
        ]);
    }

    /**
     * GET /admin/products  — Full CRUD grid for admin.
     */
    public function adminIndex(): void
    {
        $this->auth->requireRoles(['admin', 'staff']);

        $products = $this->productModel->getAll();

        $this->renderView('admin/products', ['products' => $products]);
    }

    /**
     * GET /admin/products/create  — Blank "Add Product" form.
     */
    public function create(): void
    {
        $this->auth->requireRoles(['admin', 'staff']);

        $this->renderView('admin/product_form', [
            'product'    => null,
            'csrf_token' => $this->getCsrfToken(),
        ]);
    }

    /**
     * POST /admin/products/store  — Persist a new product.
     */
    public function store(): void
    {
        $this->auth->requireRoles(['admin', 'staff']);
        $this->requirePost();
        $this->verifyCsrf();

        [$name, $description, $price, $stock, $errors] = $this->extractProductFields();

        // Handle image upload.
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                $imagePath = $this->processImageUpload($_FILES['image']);
            } catch (InvalidArgumentException $e) {
                $errors['image'] = $e->getMessage();
            }
        }

        if ($imagePath === '' && empty($errors['image'])) {
            $errors['image'] = 'A product image is required.';
        }

        if (!empty($errors)) {
            $this->renderView('admin/product_form', [
                'errors'     => $errors,
                'product'    => compact('name', 'description', 'price', 'stock'),
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        try {
            $this->productModel->create($name, $description, $price, $imagePath, $stock);
        } catch (RuntimeException $e) {
            error_log('[ProductController::store] ' . $e->getMessage());
            $this->renderView('admin/product_form', [
                'error'      => 'Could not save the product. Please try again.',
                'product'    => compact('name', 'description', 'price', 'stock'),
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        $this->flashSuccess('Product created successfully.');
        $this->redirect('admin/products');
    }

    /**
     * GET /admin/products/edit?id=X  — Pre-filled edit form.
     */
    public function edit(): void
    {
        $this->auth->requireRoles(['admin', 'staff']);

        $id      = $this->resolveIntParam('id');
        $product = $this->productModel->getById($id);

        if ($product === false) {
            $this->abort404('Product not found.');
        }

        $this->renderView('admin/product_form', [
            'product'    => $product,
            'csrf_token' => $this->getCsrfToken(),
        ]);
    }

    /**
     * POST /admin/products/update  — Persist edits to an existing product.
     */
    public function update(): void
    {
        $this->auth->requireRoles(['admin', 'staff']);
        $this->requirePost();
        $this->verifyCsrf();

        $id      = (int) ($_POST['product_id'] ?? 0);
        $product = $this->productModel->getById($id);

        if ($product === false) {
            $this->abort404('Product not found.');
        }

        [$name, $description, $price, $stock, $errors] = $this->extractProductFields();

        // New image is optional on edit.
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                $imagePath = $this->processImageUpload($_FILES['image']);
                // Delete old image if it exists and a new one was uploaded.
                $oldPath = __DIR__ . '/../' . $product['image'];
                if ($product['image'] && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            } catch (InvalidArgumentException $e) {
                $errors['image'] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->renderView('admin/product_form', [
                'errors'     => $errors,
                'product'    => array_merge($product, compact('name', 'description', 'price', 'stock')),
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        try {
            $this->productModel->update($id, $name, $description, $price, $imagePath, $stock);
        } catch (RuntimeException $e) {
            error_log('[ProductController::update] ' . $e->getMessage());
            $this->renderView('admin/product_form', [
                'error'      => 'Could not update the product. Please try again.',
                'product'    => $product,
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        $this->flashSuccess('Product updated successfully.');
        $this->redirect('admin/products');
    }

    /**
     * POST /admin/products/destroy  — Delete a product.
     */
    public function destroy(): void
    {
        $this->auth->requireRoles(['admin', 'staff']);
        $this->requirePost();
        $this->verifyCsrf();

        $id = (int) ($_POST['product_id'] ?? 0);

        try {
            $deleted = $this->productModel->delete($id);
        } catch (PDOException $e) {
            // FK RESTRICT violation — product is used in a saved bundle.
            $this->flashError('Cannot delete: this product is referenced in a user bundle.');
            $this->redirect('admin/products');
            return;
        }

        if (!$deleted) {
            $this->flashError('Product not found or already deleted.');
        } else {
            $this->flashSuccess('Product deleted successfully.');
        }

        $this->redirect('admin/products');
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Extract, sanitise, and validate shared product fields from $_POST.
     *
     * @return array{string, string, float, int, array<string,string>}
     */
    private function extractProductFields(): array
    {
        $name        = trim(strip_tags($_POST['name']        ?? ''));
        $description = trim(strip_tags($_POST['description'] ?? ''));
        $price       = (float) ($_POST['price'] ?? 0);
        $stock       = (int)   ($_POST['stock'] ?? 0);
        $errors      = [];

        if ($name === '') {
            $errors['name'] = 'Product name is required.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'Name must be 100 characters or fewer.';
        }

        if ($description === '') {
            $errors['description'] = 'Description is required.';
        }

        if ($price <= 0) {
            $errors['price'] = 'Price must be greater than zero.';
        }

        if ($stock < 0) {
            $errors['stock'] = 'Stock cannot be negative.';
        }

        return [$name, $description, $price, $stock, $errors];
    }

    /**
     * Move an uploaded image to the products upload directory and
     * return the relative path stored in the database.
     *
     * @param  array  $file  $_FILES['image'] element.
     * @return string        Relative path, e.g. 'assets/images/products/a1b2c3.png'.
     * @throws InvalidArgumentException
     */
    private function processImageUpload(array $file): string
    {
        if ($file['size'] > self::MAX_IMAGE_BYTES) {
            throw new InvalidArgumentException('Image must be 2 MB or smaller.');
        }

        $ext      = Product::validateImageUpload($file);
        $filename = bin2hex(random_bytes(12)) . '.' . $ext;
        $destDir  = __DIR__ . '/../' . Product::UPLOAD_DIR;

        if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
            throw new RuntimeException('Could not create upload directory.');
        }

        $destPath = $destDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return Product::UPLOAD_DIR . $filename;
    }

    private function resolveIntParam(string $key): int
    {
        $value = (int) ($_GET[$key] ?? 0);
        if ($value <= 0) {
            $this->abort404('Missing or invalid parameter.');
        }
        return $value;
    }

    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
    }

    private function verifyCsrf(): void
    {
        $token  = $_POST['csrf_token'] ?? '';
        $stored = $_SESSION['csrf_token'] ?? '';

        if ($stored === '' || !hash_equals($stored, $token)) {
            http_response_code(403);
            exit('Invalid CSRF token.');
        }
    }

    private function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function flashSuccess(string $message): void
    {
        $_SESSION['flash_success'] = $message;
    }

    private function flashError(string $message): void
    {
        $_SESSION['flash_error'] = $message;
    }

    private function redirect(string $path): never
    {
        header('Location: index.php?page=' . urlencode(ltrim($path, '/')));
        exit();
    }

    private function renderView(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        // Strip traversal characters but preserve allowed sub-directory structure
        // e.g. 'admin/dashboard' → views/admin/dashboard.php
        $safe     = str_replace(['..', '\\', "\0"], '', $view);
        $viewPath = __DIR__ . '/../views/' . $safe . '.php';

        if (!file_exists($viewPath)) {
            $this->abort404("View '{$view}' not found.");
        }

        require $viewPath;
    }

    private function abort404(string $message = 'Not Found'): never
    {
        http_response_code(404);
        echo "<p>{$message}</p>";
        exit();
    }
}
