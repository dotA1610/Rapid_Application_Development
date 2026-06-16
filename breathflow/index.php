<?php

/**
 * ARC NEBU-PEN  |  Front Controller — index.php
 * ─────────────────────────────────────────────────────────────
 * All HTTP requests enter here. The ?page= query-string parameter
 * selects the controller action to dispatch.
 *
 * URL scheme
 *   /breathflow/index.php?page=home              → HomeController (or direct view)
 *   /breathflow/index.php?page=login             → AuthController::login()
 *   /breathflow/index.php?page=register          → AuthController::register()
 *   /breathflow/index.php?page=logout            → AuthController::logout()
 *   /breathflow/index.php?page=dashboard         → DashboardController::index()
 *   /breathflow/index.php?page=products          → ProductController::index()
 *   /breathflow/index.php?page=science           → static view
 *   /breathflow/index.php?page=profiles          → static view (sensory profiles)
 *   /breathflow/index.php?page=bundle            → BundleController::show()
 *   /breathflow/index.php?page=bundle%2Fsave     → BundleController::store()
 *   /breathflow/index.php?page=subscription      → SubscriptionController::show()
 *   /breathflow/index.php?page=subscription%2Fjoin   → SubscriptionController::store()
 *   /breathflow/index.php?page=subscription%2Fstatus → SubscriptionController::updateStatus()
 *   /breathflow/index.php?page=admin%2Fdashboard → AdminController::index()
 *   /breathflow/index.php?page=admin%2Fproducts  → ProductController::adminIndex()
 *   … etc.
 *
 * Security
 *   • Only whitelisted page keys are dispatched — unknown keys → 404.
 *   • All controller methods enforce their own auth guards internally.
 *   • No user-supplied value is ever eval'd or require()'d directly.
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

// ── Autoload controllers & models ─────────────────────────────
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/BundleController.php';
require_once __DIR__ . '/controllers/SubscriptionController.php';

// ── Determine current page ─────────────────────────────────────
$page = trim($_GET['page'] ?? 'home');

// ── Route table ────────────────────────────────────────────────
//    key   => [ControllerClass|null, method|null]
//    null  => render a static view file directly
// ──────────────────────────────────────────────────────────────
$routes = [
    // ── Public pages ──────────────────────────────────────────
    'home'                   => [null, 'home'],
    'science'                => [null, 'science'],
    'profiles'               => [null, 'profiles'],
    'device'                 => [null, 'device'],
    'starter_kit'            => [null, 'starter_kit'],

    // ── Auth ──────────────────────────────────────────────────
    'login'                  => ['AuthController',         'login'],
    'register'               => ['AuthController',         'register'],
    'logout'                 => ['AuthController',         'logout'],

    // ── Products ──────────────────────────────────────────────
    'products'               => ['ProductController',      'index'],
    'products/show'          => ['ProductController',      'show'],

    // ── Bundle Builder ────────────────────────────────────────
    'bundle_builder'         => ['BundleController',       'show'],
    'bundle_builder/save'    => ['BundleController',       'store'],

    // ── Subscription ──────────────────────────────────────────
    'subscription'           => ['SubscriptionController', 'show'],
    'subscription/join'      => ['SubscriptionController', 'store'],
    'subscription/status'    => ['SubscriptionController', 'updateStatus'],

    // ── Customer Dashboard ────────────────────────────────────
    'dashboard'              => [null, 'dashboard'],    // handled below with auth check

    // ── Admin ─────────────────────────────────────────────────
    'admin/dashboard'        => ['ProductController',      'adminDashboard'],   // placeholder
    'admin/products'         => ['ProductController',      'adminIndex'],
    'admin/products/create'  => ['ProductController',      'create'],
    'admin/products/store'   => ['ProductController',      'store'],
    'admin/products/edit'    => ['ProductController',      'edit'],
    'admin/products/update'  => ['ProductController',      'update'],
    'admin/products/destroy' => ['ProductController',      'destroy'],
    'admin/subscriptions'    => ['SubscriptionController', 'adminIndex'],
    'admin/subscriptions/update' => ['SubscriptionController', 'adminUpdate'],
];

// ── Dispatch ───────────────────────────────────────────────────
if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    renderStaticView('404');
    exit();
}

[$controllerClass, $method] = $routes[$page];

// ── Static views (no controller, just include the PHP view file) ──
if ($controllerClass === null) {
    // Protected static views.
    if ($method === 'dashboard') {
        $auth = new AuthController();
        $auth->requireLogin();
    }
    renderStaticView($method);
    exit();
}

// ── Controller dispatch ───────────────────────────────────────
$controller = new $controllerClass();

if (!method_exists($controller, $method)) {
    http_response_code(404);
    renderStaticView('404');
    exit();
}

$controller->$method();


// ── Helpers ───────────────────────────────────────────────────

/**
 * Include a view file by logical name.
 * The path is resolved from views/ — never from user input.
 *
 * @param string $view  Logical name, e.g. 'home', 'admin/products'.
 */
function renderStaticView(string $view): void
{
    // Normalise: remove any path traversal characters.
    $safe = str_replace(['..', '\\', "\0"], '', $view);
    $path = __DIR__ . '/views/' . $safe . '.php';

    if (!file_exists($path)) {
        http_response_code(404);
        echo '<!DOCTYPE html><html><body><h1>404 — Page Not Found</h1></body></html>';
        return;
    }

    require $path;
}
