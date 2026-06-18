<?php

/**
 * ARC NEBU-PEN  |  Controller: Subscription
 * ─────────────────────────────────────────────────────────────
 * Customer routes
 *   show()         GET  — Core Club page (plans + current status)
 *   store()        POST — Join / switch plan
 *   updateStatus() POST — Pause or cancel own subscription
 *
 * Admin routes
 *   adminIndex()   GET  — All subscriptions grid
 *   adminUpdate()  POST — Change any subscription status
 *
 * No payment gateway is integrated (per spec).
 * The form simply persists the subscription data to the DB.
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../models/Subscription.php';
require_once __DIR__ . '/AuthController.php';

class SubscriptionController
{
    private Subscription   $subModel;
    private AuthController $auth;

    public function __construct()
    {
        $this->subModel = new Subscription();
        $this->auth     = new AuthController();
    }

    // ──────────────────────────────────────────────────────────
    //  CUSTOMER ROUTES
    // ──────────────────────────────────────────────────────────

    /**
     * GET /subscription
     * Show the Core Club page with plan options and the user's
     * current subscription status (if they already have one).
     */
    public function show(): void
    {
        $this->auth->requireLogin();

        $userId       = (int) $_SESSION['user_id'];
        $subscription = $this->subModel->getByUser($userId);

        $this->renderView('subscription', [
            'subscription' => $subscription,
            'plans'        => Subscription::PLANS,
            'csrf_token'   => $this->getCsrfToken(),
        ]);
    }

    /**
     * POST /subscription/join
     * Create or switch the current user's Core Club subscription.
     */
    public function store(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $userId = (int) $_SESSION['user_id'];
        $plan   = trim($_POST['plan'] ?? '');

        // Validate plan slug.
        if (!in_array($plan, Subscription::PLANS, true)) {
            $this->renderView('subscription', [
                'subscription' => $this->subModel->getByUser($userId),
                'plans'        => Subscription::PLANS,
                'error'        => 'Please select a valid subscription plan.',
                'csrf_token'   => $this->getCsrfToken(),
            ]);
            return;
        }

        try {
            $this->subModel->subscribe($userId, $plan);
        } catch (RuntimeException $e) {
            error_log('[SubscriptionController::store] ' . $e->getMessage());
            $this->renderView('subscription', [
                'subscription' => $this->subModel->getByUser($userId),
                'plans'        => Subscription::PLANS,
                'error'        => 'Could not save your subscription. Please try again later.',
                'csrf_token'   => $this->getCsrfToken(),
            ]);
            return;
        }

        $planLabel = ucfirst($plan);
        $_SESSION['flash_success'] = "Welcome to Core Club! Your {$planLabel} plan is now active.";

        $this->redirect('dashboard');
    }

    /**
     * POST /subscription/status
     * Allow a logged-in customer to pause or cancel their own subscription.
     * They cannot set status to 'active' here — that goes through store().
     */
    public function updateStatus(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $userId = (int) $_SESSION['user_id'];
        $status = trim($_POST['status'] ?? '');

        // Customers may only pause or cancel — never directly reactivate.
        $allowedCustomerStatuses = ['paused', 'cancelled'];

        if (!in_array($status, $allowedCustomerStatuses, true)) {
            $_SESSION['flash_error'] = 'Invalid action.';
            $this->redirect('subscription');
            return;
        }

        $sub = $this->subModel->getByUser($userId);

        if ($sub === false) {
            $_SESSION['flash_error'] = 'No active subscription found.';
            $this->redirect('subscription');
            return;
        }

        try {
            $this->subModel->updateStatus((int) $sub['subscription_id'], $status);
        } catch (RuntimeException $e) {
            error_log('[SubscriptionController::updateStatus] ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Could not update your subscription.';
            $this->redirect('subscription');
            return;
        }

        $label = ucfirst($status);
        $_SESSION['flash_success'] = "Your subscription has been {$label}.";

        $this->redirect('dashboard');
    }

    // ──────────────────────────────────────────────────────────
    //  ADMIN ROUTES
    // ──────────────────────────────────────────────────────────

    /**
     * GET /admin/subscriptions
     * Full subscriptions grid with user details — admin only.
     */
    public function adminIndex(): void
    {
        $this->auth->requireAdmin();

        $subscriptions = $this->subModel->getAll();

        $this->renderView('admin/subscriptions', [
            'subscriptions' => $subscriptions,
            'csrf_token'    => $this->getCsrfToken(),
        ]);
    }

    /**
     * POST /admin/subscriptions/update
     * Allow an admin to change any subscription's status.
     * Admin has access to all three status values (active/paused/cancelled).
     */
    public function adminUpdate(): void
    {
        $this->auth->requireAdmin();
        $this->requirePost();
        $this->verifyCsrf();

        $subscriptionId = (int) ($_POST['subscription_id'] ?? 0);
        $status         = trim($_POST['status'] ?? '');

        if ($subscriptionId <= 0 || !in_array($status, Subscription::STATUSES, true)) {
            $_SESSION['flash_error'] = 'Invalid subscription or status.';
            $this->redirect('admin/subscriptions');
            return;
        }

        try {
            $updated = $this->subModel->updateStatus($subscriptionId, $status);
        } catch (RuntimeException $e) {
            error_log('[SubscriptionController::adminUpdate] ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Could not update the subscription.';
            $this->redirect('admin/subscriptions');
            return;
        }

        if ($updated) {
            $_SESSION['flash_success'] = "Subscription #{$subscriptionId} set to '{$status}'.";
        } else {
            $_SESSION['flash_error'] = 'Subscription not found or status unchanged.';
        }

        $this->redirect('admin/subscriptions');
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

        // Preserve sub-directory structure (e.g. 'admin/subscriptions')
        // while stripping traversal characters for safety.
        $safe     = str_replace(['..', '\\', "\0"], '', $view);
        $viewPath = __DIR__ . '/../views/' . $safe . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "<p>View '{$view}' not found.</p>";
            exit();
        }

        require $viewPath;
    }
}
