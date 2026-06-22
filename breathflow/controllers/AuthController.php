<?php

/**
 * ARC NEBU-PEN  |  Controller: Auth
 * ─────────────────────────────────────────────────────────────
 * Handles the full authentication lifecycle:
 *   • register()      — validate input, create account, redirect
 *   • login()         — verify credentials, start session, redirect
 *   • logout()        — destroy session cleanly
 *
 * Session security measures applied
 *   • session_regenerate_id(true)  on every successful login
 *     → prevents session-fixation attacks
 *   • CSRF token generated per-session and verified on every POST
 *   • Error messages are deliberately vague for login failures
 *     → prevents username enumeration
 *   • All redirects are internal — no open-redirect risk
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private User $userModel;

    // ── Validation limits ─────────────────────────────────────
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_NAME_LENGTH     = 120;
    private const MAX_EMAIL_LENGTH    = 180;

    public function __construct()
    {
        $this->userModel = new User();
        $this->startSession();
    }

    // ──────────────────────────────────────────────────────────
    //  PUBLIC ACTIONS
    // ──────────────────────────────────────────────────────────

    /**
     * Handle GET (show form) and POST (process registration).
     * Route: GET/POST /register
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
        } else {
            $this->renderView('login', [
                'csrf_token' => $this->getCsrfToken(),
            ]);
        }
    }

    /**
     * Handle GET (show form) and POST (process login).
     * Route: GET/POST /login
     */
    public function login(): void
    {
        // Already logged in — bounce to the correct workspace for their role.
        if ($this->isLoggedIn()) {
            $this->redirect($this->destinationForRole($_SESSION['role'] ?? 'customer'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->renderView('login', [
                'csrf_token' => $this->getCsrfToken(),
            ]);
        }
    }

    /**
     * Destroy the session and redirect to home.
     * Route: POST /logout   (GET is also accepted for simplicity)
     */
    public function logout(): void
    {
        // Unset all session variables.
        $_SESSION = [];

        // Expire the session cookie.
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('home');
    }

    // ──────────────────────────────────────────────────────────
    //  ACCESS-CONTROL GUARDS
    //  Call these at the top of any controller that needs auth.
    // ──────────────────────────────────────────────────────────

    /**
     * Returns true when a valid user session exists.
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id'], $_SESSION['role']);
    }

    /**
     * Enforce authentication.
     * Redirects to /login if the visitor has no active session.
     */
    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }

    /**
     * Enforce admin role.
     * Redirects non-admins to the user dashboard.
     */
    public function requireAdmin(): void
    {
        $this->requireRoles(['admin']);
    }

    /**
     * Enforce specific roles.
     * Redirects users without the allowed roles to the user dashboard.
     */
    public function requireRoles(array $allowedRoles): void
    {
        $this->requireLogin();

        if (!in_array($_SESSION['role'] ?? '', $allowedRoles, true)) {
            // Redirect to appropriate workspace, not always customer dashboard.
            $this->redirect($this->destinationForRole($_SESSION['role'] ?? 'customer'));
        }
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE — REGISTRATION LOGIC
    // ──────────────────────────────────────────────────────────

    private function handleRegister(): void
    {
        // ── 1. CSRF check ──────────────────────────────────────
        if (!$this->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->renderView('login', [
                'error'      => 'Invalid request. Please try again.',
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 2. Sanitise & collect raw inputs ──────────────────
        $fullname  = trim(strip_tags($_POST['fullname'] ?? ''));
        $email     = trim(strtolower($_POST['email'] ?? ''));
        $password  = $_POST['password'] ?? '';
        $confirm   = $_POST['confirm_password'] ?? '';

        // ── 3. Validate ────────────────────────────────────────
        $errors = $this->validateRegistration($fullname, $email, $password, $confirm);

        if (!empty($errors)) {
            $this->renderView('login', [
                'errors'     => $errors,
                'old'        => ['fullname' => htmlspecialchars($fullname), 'email' => htmlspecialchars($email)],
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 4. Duplicate e-mail check ─────────────────────────
        if ($this->userModel->emailExists($email)) {
            $this->renderView('login', [
                'errors'     => ['email' => 'This e-mail address is already registered.'],
                'old'        => ['fullname' => htmlspecialchars($fullname), 'email' => htmlspecialchars($email)],
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 5. Create the account ─────────────────────────────
        try {
            $newId = $this->userModel->create($fullname, $email, $password);
        } catch (RuntimeException $e) {
            error_log('[AuthController::register] ' . $e->getMessage());
            $this->renderView('login', [
                'error'      => 'Registration failed. Please try again later.',
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 6. Auto-login & redirect ──────────────────────────
        $this->startAuthenticatedSession($newId, $fullname, $email, 'customer');
        $this->redirect('dashboard');
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE — LOGIN LOGIC
    // ──────────────────────────────────────────────────────────

    private function handleLogin(): void
    {
        // ── 1. CSRF check ──────────────────────────────────────
        if (!$this->verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->renderView('login', [
                'error'      => 'Invalid request. Please try again.',
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 2. Collect inputs ──────────────────────────────────
        $email     = trim(strtolower($_POST['email'] ?? ''));
        $password  = $_POST['password'] ?? '';

        // ── 3. Basic presence check ────────────────────────────
        if ($email === '' || $password === '') {
            $this->renderView('login', [
                'error'      => 'Please enter your e-mail and password.',
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 4. Look up account ────────────────────────────────
        $user = $this->userModel->findByEmail($email);

        // ── 5. Verify password — same generic message on failure
        //       (prevents enumeration: attacker cannot tell whether
        //        the e-mail exists or the password was wrong)
        if ($user === false || !$this->userModel->verifyPassword($password, $user)) {
            // Add a tiny constant-time delay to blunt brute-force.
            usleep(random_int(200_000, 400_000));

            $this->renderView('login', [
                'error'      => 'Incorrect e-mail or password.',
                'csrf_token' => $this->getCsrfToken(),
            ]);
            return;
        }

        // ── 6. Start authenticated session ────────────────────
        $this->startAuthenticatedSession(
            (int)   $user['user_id'],
            (string)$user['fullname'],
            (string)$user['email'],
            (string)$user['role']
        );

        // ── 7. Role-based redirect (FR-01) ─────────────────────────
        $this->redirect($this->destinationForRole($user['role']));
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE — VALIDATION
    // ──────────────────────────────────────────────────────────

    /**
     * @return array<string, string>  Field-keyed error messages.
     */
    private function validateRegistration(
        string $fullname,
        string $email,
        string $password,
        string $confirm
    ): array {
        $errors = [];

        if ($fullname === '') {
            $errors['fullname'] = 'Full name is required.';
        } elseif (mb_strlen($fullname) > self::MAX_NAME_LENGTH) {
            $errors['fullname'] = 'Name must be ' . self::MAX_NAME_LENGTH . ' characters or fewer.';
        }

        if ($email === '') {
            $errors['email'] = 'E-mail address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid e-mail address.';
        } elseif (mb_strlen($email) > self::MAX_EMAIL_LENGTH) {
            $errors['email'] = 'E-mail address is too long.';
        }

        if (mb_strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $errors['password'] = 'Password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters.';
        } elseif ($password !== $confirm) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        return $errors;
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE — SESSION HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Start PHP session with secure cookie parameters if not already running.
     */
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,             // session cookie (expires on browser close)
                'path'     => '/',
                'domain'   => '',
                'secure'   => isset($_SERVER['HTTPS']),  // HTTPS-only in production
                'httponly' => true,          // no JS access to the cookie
                'samesite' => 'Strict',      // CSRF mitigation
            ]);
            session_start();
        }
    }

    /**
     * Populate the session after a successful login or registration.
     * session_regenerate_id(true) defends against session fixation.
     */
    private function startAuthenticatedSession(
        int    $userId,
        string $fullname,
        string $email,
        string $role
    ): void {
        session_regenerate_id(true);        // ← key: old session ID is invalidated

        $_SESSION['user_id']  = $userId;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email']    = $email;
        $_SESSION['role']     = $role;
        $_SESSION['logged_in_at'] = time();
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE — CSRF HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Return the per-session CSRF token, generating it if absent.
     */
    private function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Constant-time comparison to prevent timing attacks on the token.
     */
    private function verifyCsrfToken(string $submitted): bool
    {
        $stored = $_SESSION['csrf_token'] ?? '';

        return $stored !== '' && hash_equals($stored, $submitted);
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE — RENDER / REDIRECT
    // ──────────────────────────────────────────────────────────

    /**
     * Include a view file, injecting the provided data as local variables.
     *
     * @param string               $view   Filename without extension, e.g. 'login'.
     * @param array<string, mixed> $data   Variables extracted into the view scope.
     */
    private function renderView(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);          // EXTR_SKIP — never overwrite existing vars

        $viewPath = __DIR__ . '/../views/' . basename($view) . '.php';

        if (!file_exists($viewPath)) {
            error_log('[AuthController] View not found: ' . $viewPath);
            http_response_code(404);
            echo '<p>Page not found.</p>';
            return;
        }

        require $viewPath;
    }

    /**
     * Send an internal redirect and halt execution.
     * Preserves subdirectory paths (e.g. 'admin/dashboard') while
     * stripping traversal characters so users cannot escape views/.
     *
     * @param string $path  Relative path, e.g. 'dashboard' or 'admin/dashboard'.
     */
    private function redirect(string $path): never
    {
        // Strip traversal characters but keep subdirectory structure intact.
        $safe = str_replace(['..', '\\', "\0"], '', ltrim($path, '/'));
        header('Location: index.php?page=' . urlencode($safe));
        exit();
    }

    /**
     * Return the correct post-login destination for a given role.
     *
     * @param string $role  The authenticated user's role string.
     * @return string       Route key matching a registered index.php route.
     */
    private function destinationForRole(string $role): string
    {
        return match ($role) {
            'admin', 'manager' => 'admin/dashboard',
            'staff'            => 'admin/products',
            default            => 'dashboard',
        };
    }
}
