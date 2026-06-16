<?php

/**
 * ARC NEBU-PEN  |  Controller: Bundle
 * ─────────────────────────────────────────────────────────────
 * Routes
 *   show()  GET  — Display the current user's saved bundle
 *                  (shown on the dashboard and bundle builder page)
 *   store() POST — Save / update the user's 3-cartridge bundle
 *
 * Security
 *   • requireLogin() guard — anonymous users cannot save bundles
 *   • CSRF token verified on every POST
 *   • Server-side price recalculated from live DB product prices
 *     (JavaScript frontend total is UX only; never trusted)
 *   • All three product IDs validated to exist before INSERT
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../models/Bundle.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/AuthController.php';

class BundleController
{
    private Bundle         $bundleModel;
    private Product        $productModel;
    private AuthController $auth;

    public function __construct()
    {
        $this->bundleModel  = new Bundle();
        $this->productModel = new Product();
        $this->auth         = new AuthController();
    }

    // ──────────────────────────────────────────────────────────
    //  ROUTES
    // ──────────────────────────────────────────────────────────

    /**
     * GET /bundle
     * Show the Bundle Builder page pre-populated with available
     * products and the user's current saved bundle (if any).
     */
    public function show(): void
    {
        $this->auth->requireLogin();

        $userId   = (int) $_SESSION['user_id'];
        $products = $this->productModel->getAvailable();
        $bundle   = $this->bundleModel->getByUser($userId);

        $this->renderView('bundle_builder', [
            'products'   => $products,
            'bundle'     => $bundle,
            'csrf_token' => $this->getCsrfToken(),
        ]);
    }

    /**
     * POST /bundle/save
     * Validate the three selected flavor IDs, recalculate the
     * server-side total, and upsert the bundle row.
     */
    public function store(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $userId  = (int) $_SESSION['user_id'];
        $flavor1 = (int) ($_POST['flavor1'] ?? 0);
        $flavor2 = (int) ($_POST['flavor2'] ?? 0);
        $flavor3 = (int) ($_POST['flavor3'] ?? 0);

        // ── Validate: all three must be non-zero ───────────────
        $errors = [];

        if ($flavor1 <= 0) { $errors['flavor1'] = 'Please select Cartridge 1.'; }
        if ($flavor2 <= 0) { $errors['flavor2'] = 'Please select Cartridge 2.'; }
        if ($flavor3 <= 0) { $errors['flavor3'] = 'Please select Cartridge 3.'; }

        if (!empty($errors)) {
            $products = $this->productModel->getAvailable();
            $bundle   = $this->bundleModel->getByUser($userId);

            $this->renderView('bundle_builder', [
                'products'   => $products,
                'bundle'     => $bundle,
                'errors'     => $errors,
                'old'        => ['flavor1' => $flavor1, 'flavor2' => $flavor2, 'flavor3' => $flavor3],
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── Save (model validates product IDs exist) ───────────
        try {
            $total = $this->bundleModel->save($userId, $flavor1, $flavor2, $flavor3);
        } catch (InvalidArgumentException $e) {
            // One or more product IDs were tampered with in the POST body.
            $products = $this->productModel->getAvailable();
            $bundle   = $this->bundleModel->getByUser($userId);

            $this->renderView('bundle_builder', [
                'products'   => $products,
                'bundle'     => $bundle,
                'error'      => 'One or more selected products are invalid. Please try again.',
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        } catch (RuntimeException $e) {
            error_log('[BundleController::store] ' . $e->getMessage());
            $this->redirect('bundle');
            return;
        }

        $_SESSION['flash_success'] = sprintf(
            'Bundle saved! Your total is RM%.2f.',
            $total
        );

        $this->redirect('dashboard');
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

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

    private function redirect(string $path): never
    {
        header('Location: index.php?page=' . urlencode(ltrim($path, '/')));
        exit();
    }

    private function renderView(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = __DIR__ . '/../views/' . basename($view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "<p>View '{$view}' not found.</p>";
            exit();
        }

        require $viewPath;
    }
}
