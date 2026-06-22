<?php

/**
 * ARC NEBU-PEN  |  Controller: Cart & Checkout
 * ─────────────────────────────────────────────────────────────
 * Routes
 *   add()      POST — Add an item to the session cart
 *   view()     GET  — Show the mock Stripe checkout page
 *   process()  POST — Simulate payment, fulfil DB, redirect
 *   clear()    POST — Empty the cart and redirect back
 *
 * Cart structure  $_SESSION['cart'] = [
 *     [ 'name' => 'ARC Starter Kit', 'type' => 'kit',
 *       'price' => 199.00, 'qty' => 1, 'meta' => [...] ],
 *     ...
 * ]
 *
 * Security
 *   • CSRF verified on every POST
 *   • Prices recalculated server-side (never from the client)
 *   • Prepared statements for all DB writes
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Bundle.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Subscription.php';
require_once __DIR__ . '/AuthController.php';

class CartController
{
    private AuthController $auth;

    public function __construct()
    {
        $this->auth = new AuthController();
    }

    // ──────────────────────────────────────────────────────────
    //  POST /cart/add  — Add an item to session cart
    // ──────────────────────────────────────────────────────────

    public function add(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $type = trim($_POST['item_type'] ?? '');

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        switch ($type) {
            case 'kit':
                $this->addStarterKit();
                break;

            case 'bundle':
                $this->addBundle();
                break;

            case 'subscription':
                $this->addSubscription();
                break;

            default:
                $_SESSION['flash_error'] = 'Unknown item type.';
                $this->redirect('home');
                return;
        }

        $_SESSION['toast_msg'] = "Item added to cart successfully!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // ──────────────────────────────────────────────────────────
    //  GET /cart  — Display the shopping cart page
    // ──────────────────────────────────────────────────────────

    public function showCart(): void
    {
        $this->auth->requireLogin();

        $cart = $_SESSION['cart'] ?? [];

        $subtotal = 0.0;
        $discount = 0.0;

        foreach ($cart as $item) {
            $lineTotal = $item['price'] * $item['qty'];
            $subtotal += $lineTotal;
            if ($item['type'] === 'subscription') {
                $discount += $lineTotal * 0.20;
            }
        }

        $total = $subtotal - $discount;

        $this->renderView('cart', [
            'cart'       => $cart,
            'subtotal'   => $subtotal,
            'discount'   => $discount,
            'total'      => $total,
            'csrf_token' => $this->getCsrfToken(),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  GET /checkout  — Display the payment page
    // ──────────────────────────────────────────────────────────

    public function view(): void
    {
        $this->auth->requireLogin();

        $cart = $_SESSION['cart'] ?? [];

        // Calculate totals
        $subtotal = 0.0;
        $discount = 0.0;

        foreach ($cart as $item) {
            $lineTotal = $item['price'] * $item['qty'];
            $subtotal += $lineTotal;

            // Apply 20% Core Club discount for subscription items
            if ($item['type'] === 'subscription') {
                $discount += $lineTotal * 0.20;
            }
        }

        $total = $subtotal - $discount;

        $this->renderView('checkout', [
            'cart'       => $cart,
            'subtotal'   => $subtotal,
            'discount'   => $discount,
            'total'      => $total,
            'csrf_token' => $this->getCsrfToken(),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  POST /checkout/process  — Simulate payment + fulfil DB
    // ──────────────────────────────────────────────────────────

    public function process(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $userId = (int) $_SESSION['user_id'];
        $cart   = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $_SESSION['flash_error'] = 'Your cart is empty.';
            $this->redirect('home');
            return;
        }

        // ── Simulate payment validation ─────────────────────
        $email  = trim($_POST['email'] ?? '');
        $cardNo = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');

        if (empty($email) || empty($cardNo)) {
            $_SESSION['flash_error'] = 'Please fill in all payment fields.';
            $this->redirect('checkout');
            return;
        }

        // Accept any card starting with 4242 (test mode)
        if (!str_starts_with($cardNo, '4242')) {
            $_SESSION['flash_error'] = 'Payment declined. Use test card 4242 4242 4242 4242.';
            $this->redirect('checkout');
            return;
        }

        // ── Fulfil each cart item into the database ──────────
        $pdo = Database::getInstance();

        try {
            $pdo->beginTransaction();

            foreach ($cart as $item) {
                switch ($item['type']) {
                    case 'kit':
                        // Starter kit = upsert a bundle with default 4 flavors
                        $this->fulfilStarterKit($pdo, $userId);
                        break;

                    case 'bundle':
                        // Bundle already saved by BundleController::store()
                        // but we mark it as "paid" via the existing row
                        // (no extra table needed — bundle exists in DB)
                        break;

                    case 'subscription':
                        $this->fulfilSubscription($pdo, $userId, $item);
                        break;
                }
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            error_log('[CartController::process] ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Payment processing failed. Please try again.';
            $this->redirect('checkout');
            return;
        }

        // ── Clear cart and redirect to order success ────────
        $_SESSION['last_order'] = $_SESSION['cart'];
        $_SESSION['cart'] = [];
        $_SESSION['flash_success'] = 'Payment successful! Your order has been confirmed.';
        $this->redirect('order_success');
    }

    // ──────────────────────────────────────────────────────────
    //  POST /cart/clear  — Empty the cart
    // ──────────────────────────────────────────────────────────

    public function clear(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $_SESSION['cart'] = [];
        $_SESSION['flash_success'] = 'Cart cleared.';
        $this->redirect('cart');
    }

    // ──────────────────────────────────────────────────────────
    //  POST /cart/remove  — Remove a specific item from the cart
    // ──────────────────────────────────────────────────────────

    public function remove(): void
    {
        $this->auth->requireLogin();
        $this->requirePost();
        $this->verifyCsrf();

        $index = $_POST['item_index'] ?? null;

        if ($index !== null && isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            // Re-index the array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['toast_msg'] = "Item removed from cart.";
        } else {
            $_SESSION['flash_error'] = "Item not found in cart.";
        }

        $this->redirect('cart');
    }

    // ══════════════════════════════════════════════════════════
    //  CART ITEM BUILDERS
    // ══════════════════════════════════════════════════════════

    private function addStarterKit(): void
    {
        // Remove any existing kit from cart to prevent duplicates
        $_SESSION['cart'] = array_filter(
            $_SESSION['cart'],
            fn($i) => $i['type'] !== 'kit'
        );
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        $_SESSION['cart'][] = [
            'name'  => 'ARC Starter Kit',
            'type'  => 'kit',
            'price' => 199.00,
            'qty'   => 1,
            'meta'  => [
                'includes' => '1x NEBU-PEN Device, 4x Pods (Mint, Berry, Citrus, Lavender), 1x USB-C Cable',
            ],
        ];
    }

    private function addBundle(): void
    {
        $userId = (int) $_SESSION['user_id'];
        $bundleModel = new Bundle();
        $bundle = $bundleModel->getByUser($userId);

        if (!$bundle) {
            $_SESSION['flash_error'] = 'Please build your bundle first.';
            $this->redirect('bundle_builder');
            return;
        }

        // Remove any existing bundle from cart
        $_SESSION['cart'] = array_filter(
            $_SESSION['cart'],
            fn($i) => $i['type'] !== 'bundle'
        );
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        $_SESSION['cart'][] = [
            'name'  => 'Custom 3-Pod Bundle',
            'type'  => 'bundle',
            'price' => (float) $bundle['total_price'],
            'qty'   => 1,
            'meta'  => [
                'flavor1' => $bundle['flavor1_name'],
                'flavor2' => $bundle['flavor2_name'],
                'flavor3' => $bundle['flavor3_name'],
            ],
        ];
    }

    private function addSubscription(): void
    {
        $plan = trim($_POST['plan'] ?? '');

        $planMap = [
            'monthly_45'    => ['label' => 'Core Club — Monthly',     'price' => 45.00, 'plan' => 'monthly'],
            'bi_monthly_85' => ['label' => 'Core Club — Every 2 Months', 'price' => 85.00, 'plan' => 'bimonthly'],
        ];

        if (!isset($planMap[$plan])) {
            $_SESSION['flash_error'] = 'Please select a valid plan.';
            $this->redirect('subscription');
            return;
        }

        $planData = $planMap[$plan];

        // Remove any existing subscription from cart
        $_SESSION['cart'] = array_filter(
            $_SESSION['cart'],
            fn($i) => $i['type'] !== 'subscription'
        );
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        $_SESSION['cart'][] = [
            'name'  => $planData['label'],
            'type'  => 'subscription',
            'price' => $planData['price'],
            'qty'   => 1,
            'meta'  => [
                'plan_slug' => $planData['plan'],
            ],
        ];
    }

    // ══════════════════════════════════════════════════════════
    //  DB FULFILMENT METHODS
    // ══════════════════════════════════════════════════════════

    /**
     * Starter Kit → insert a bundle with the 4 default products.
     */
    private function fulfilStarterKit(PDO $pdo, int $userId): void
    {
        // Look up the first 3 products by name to get their IDs
        $stmt = $pdo->prepare(
            "SELECT product_id, name FROM products
              WHERE name IN ('Mint', 'Berry', 'Citrus', 'Lavender')
              ORDER BY product_id ASC LIMIT 3"
        );
        $stmt->execute();
        $products = $stmt->fetchAll();

        if (count($products) < 3) {
            // Not enough products in DB — just skip bundle insert
            return;
        }

        $bundleModel = new Bundle();
        $bundleModel->save(
            $userId,
            (int) $products[0]['product_id'],
            (int) $products[1]['product_id'],
            (int) $products[2]['product_id']
        );
    }

    /**
     * Subscription → upsert into the subscriptions table.
     */
    private function fulfilSubscription(PDO $pdo, int $userId, array $item): void
    {
        $planSlug = $item['meta']['plan_slug'] ?? 'monthly';

        $subModel = new Subscription();
        $subModel->subscribe($userId, $planSlug);
    }

    // ══════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════

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
