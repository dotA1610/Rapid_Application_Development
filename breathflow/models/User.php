<?php

/**
 * ARC NEBU-PEN  |  Model: User
 * ─────────────────────────────────────────────────────────────
 * Responsible for ALL database interactions on the `users` table.
 * Controllers must NEVER write raw SQL — they call these methods.
 *
 * Password policy
 *   • Hashing  : password_hash()  with PASSWORD_BCRYPT (cost 12)
 *   • Verifying: password_verify() — constant-time comparison
 *   • Rehashing: automatically detected via password_needs_rehash()
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class User
{
    private PDO $db;

    /** Bcrypt work-factor — increase as hardware improves */
    private const BCRYPT_COST = 12;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ──────────────────────────────────────────────────────────
    //  READ OPERATIONS
    // ──────────────────────────────────────────────────────────

    /**
     * Fetch a single user row by e-mail address.
     * Used by AuthController::login().
     *
     * @param  string      $email  Raw e-mail from form input.
     * @return array|false         Associative row or false when not found.
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT user_id, fullname, email, password, role, created_at
               FROM users
              WHERE email = ?
              LIMIT 1'
        );
        $stmt->execute([strtolower(trim($email))]);

        return $stmt->fetch();
    }

    /**
     * Fetch a single user row by primary key.
     * Used by the dashboard and profile controllers.
     *
     * @param  int         $userId
     * @return array|false
     */
    public function findById(int $userId): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT user_id, fullname, email, role, created_at
               FROM users
              WHERE user_id = ?
              LIMIT 1'
        );
        $stmt->execute([$userId]);

        return $stmt->fetch();
    }

    /**
     * Check whether an e-mail address already exists in the table.
     * Used for registration duplicate-detection.
     *
     * @param  string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM users WHERE email = ? LIMIT 1'
        );
        $stmt->execute([strtolower(trim($email))]);

        return (bool) $stmt->fetchColumn();
    }

    // ──────────────────────────────────────────────────────────
    //  WRITE OPERATIONS
    // ──────────────────────────────────────────────────────────

    /**
     * Insert a new customer account.
     * The password is hashed here — the controller must pass the
     * plaintext value; it must NOT pre-hash it.
     *
     * @param  string   $fullname   Display name.
     * @param  string   $email      Must be unique (enforced by DB UNIQUE KEY).
     * @param  string   $plaintext  Raw password from the registration form.
     * @return int                  New user_id on success.
     * @throws RuntimeException     On duplicate e-mail or DB failure.
     */
    public function create(string $fullname, string $email, string $plaintext): int
    {
        $hash = $this->hashPassword($plaintext);

        $stmt = $this->db->prepare(
            'INSERT INTO users (fullname, email, password, role)
             VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            trim($fullname),
            strtolower(trim($email)),
            $hash,
            'customer',                 // new sign-ups are always customers
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Verify a plaintext password against the stored bcrypt hash.
     * Also triggers a transparent rehash if the cost factor was
     * raised since the account was created.
     *
     * @param  string $plaintext   Raw value from the login form.
     * @param  array  $userRow     Full row returned by findByEmail().
     * @return bool
     */
    public function verifyPassword(string $plaintext, array $userRow): bool
    {
        if (!password_verify($plaintext, $userRow['password'])) {
            return false;
        }

        // Silently upgrade hash if cost or algorithm has changed.
        if (password_needs_rehash($userRow['password'], PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST])) {
            $this->rehashPassword((int) $userRow['user_id'], $plaintext);
        }

        return true;
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Produce a bcrypt hash with the configured cost factor.
     *
     * @param  string $plaintext
     * @return string            60-char bcrypt string.
     * @throws RuntimeException  If hashing fails (should never occur).
     */
    private function hashPassword(string $plaintext): string
    {
        $hash = password_hash($plaintext, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);

        if ($hash === false) {
            throw new RuntimeException('Password hashing failed unexpectedly.');
        }

        return $hash;
    }

    /**
     * Update the stored hash for an existing user.
     * Called automatically by verifyPassword() when rehash is needed.
     *
     * @param  int    $userId
     * @param  string $plaintext
     */
    private function rehashPassword(int $userId, string $plaintext): void
    {
        $newHash = $this->hashPassword($plaintext);

        $stmt = $this->db->prepare(
            'UPDATE users SET password = ? WHERE user_id = ?'
        );
        $stmt->execute([$newHash, $userId]);
    }
}
