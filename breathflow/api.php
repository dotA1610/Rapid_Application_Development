<?php
/**
 * ARC NEBU-PEN | Mobile API Gateway
 * ─────────────────────────────────────────────────────────────
 */

// Configure Global API Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");

// Includes
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Bundle.php';
require_once __DIR__ . '/models/Subscription.php';

// Route action
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_products':
        $productModel = new Product();
        $products = $productModel->getAll();
        echo json_encode($products);
        break;

    case 'get_user_dashboard':
        $userId = (int)($_GET['user_id'] ?? 0);
        if ($userId <= 0) {
            echo json_encode(['error' => 'Valid user_id parameter required.']);
            break;
        }

        $bundleModel = new Bundle();
        $subscriptionModel = new Subscription();

        $bundle = $bundleModel->getByUser($userId);
        $subscription = $subscriptionModel->getByUser($userId);

        echo json_encode([
            'status' => 'success',
            'dashboard_data' => [
                'bundle' => $bundle ?: null,
                'subscription' => $subscription ?: null
            ]
        ]);
        break;

    case 'mobile_login':
        // Parse raw JSON or POST data
        $input = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
            break;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && $userModel->verifyPassword($password, $user['password'])) {
            echo json_encode([
                'status' => 'success',
                'user_id' => $user['id'],
                'role' => $user['role'],
                'fullname' => $user['fullname']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid backend endpoint action requested']);
        break;
}
