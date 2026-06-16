<?php

/**
 * ARC NEBU-PEN  |  Database Configuration
 * ─────────────────────────────────────────────────────────────
 * Provides a single, shared PDO instance for the entire app.
 *
 * SECURITY RULES
 *   • Credentials are read from environment variables first.
 *     Set them in your web-server config or a .env loader so
 *     they are NEVER committed to version control.
 *   • PDO::ERRMODE_EXCEPTION surfaces SQL errors as catchable
 *     exceptions — caught at the controller level, never echoed.
 *   • PDO::EMULATE_PREPARES = false forces real prepared
 *     statements, defeating all second-order SQL injection.
 *   • charset=utf8mb4 in the DSN prevents charset-based attacks.
 *
 * USAGE (in any controller / model)
 *   require_once __DIR__ . '/../config/database.php';
 *   $pdo = Database::getInstance();
 *   $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
 *   $stmt->execute([$id]);
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

class Database
{
    // ── Connection defaults (overridden by env vars) ──────────
    private const DEFAULT_HOST    = '127.0.0.1';
    private const DEFAULT_PORT    = '3306';
    private const DEFAULT_DBNAME  = 'arc_nebupen';
    private const DEFAULT_CHARSET = 'utf8mb4';

    /** @var PDO|null Singleton PDO instance */
    private static ?PDO $instance = null;

    /**
     * Private constructor — prevents direct instantiation.
     */
    private function __construct() {}

    /**
     * Returns the shared PDO connection, creating it on first call.
     *
     * @throws RuntimeException if the connection cannot be established.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }

        return self::$instance;
    }

    /**
     * Builds and returns a hardened PDO connection.
     */
    private static function createConnection(): PDO
    {
        // ── Read credentials from environment (preferred) or fallback ──
        $host    = getenv('DB_HOST')    ?: self::DEFAULT_HOST;
        $port    = getenv('DB_PORT')    ?: self::DEFAULT_PORT;
        $dbname  = getenv('DB_NAME')    ?: self::DEFAULT_DBNAME;
        $charset = self::DEFAULT_CHARSET;   // always utf8mb4 — not configurable
        $user    = getenv('DB_USER')    ?: 'root';      // change for production
        $pass    = getenv('DB_PASS')    ?: '';           // never leave blank in prod

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $host,
            $port,
            $dbname,
            $charset
        );

        $options = [
            // ── Error handling ──────────────────────────────────────
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

            // ── Return rows as associative arrays by default ─────────
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // ── Use REAL prepared statements (no emulation) ──────────
            PDO::ATTR_EMULATE_PREPARES   => false,

            // ── Persistent connections — off by default for safety ───
            PDO::ATTR_PERSISTENT         => false,

            // ── Strict types: no silent casting ─────────────────────
            PDO::ATTR_STRINGIFY_FETCHES  => false,

            // ── MySQL-specific: disconnect on close ──────────────────
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci,
                                             time_zone = '+08:00'",
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            /*
             * Log the real error server-side (never expose to the
             * browser). In production, swap error_log() for your
             * PSR-3 logger.
             */
            error_log('[ARC DB ERROR] ' . $e->getMessage());

            // Throw a clean, non-leaking exception upward.
            throw new RuntimeException(
                'Database connection failed. Please try again later.',
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * Prevent cloning of the singleton.
     */
    private function __clone() {}

    /**
     * Prevent unserializing of the singleton.
     *
     * @throws RuntimeException always.
     */
    public function __wakeup(): void
    {
        throw new RuntimeException('Deserialising a singleton is not allowed.');
    }
}
