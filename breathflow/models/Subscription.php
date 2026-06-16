<?php

/**
 * ARC NEBU-PEN  |  Model: Subscription
 * ─────────────────────────────────────────────────────────────
 * Manages the `subscriptions` table — Core Club membership data.
 *
 * Business rules encoded here
 *   • A user may hold only ONE subscription (UNIQUE KEY in schema).
 *   • Discount is always 20 % for Core Club (set by DB default;
 *     model never accepts a caller-supplied discount value).
 *   • next_delivery_date is computed from the plan on create/update.
 *     monthly   → today + 30 days
 *     bimonthly → today + 60 days
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Subscription
{
    private PDO $db;

    /** Core Club fixed discount — stored in DB but sourced only here */
    public const CORE_CLUB_DISCOUNT = 20;

    /** Valid plan slugs */
    public const PLANS = ['monthly', 'bimonthly'];

    /** Valid status values */
    public const STATUSES = ['active', 'paused', 'cancelled'];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ──────────────────────────────────────────────────────────
    //  READ
    // ──────────────────────────────────────────────────────────

    /**
     * Fetch the subscription for a specific user.
     *
     * @param  int         $userId
     * @return array|false
     */
    public function getByUser(int $userId): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT subscription_id, user_id, plan, status, discount,
                    next_delivery_date, created_at, updated_at
               FROM subscriptions
              WHERE user_id = ?
              LIMIT 1'
        );
        $stmt->execute([$userId]);

        return $stmt->fetch();
    }

    /**
     * Fetch all subscriptions — used by the Admin Dashboard grid.
     * Joined with users so the admin sees the subscriber's name.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT s.subscription_id,
                    u.fullname,
                    u.email,
                    s.plan,
                    s.status,
                    s.discount,
                    s.next_delivery_date,
                    s.created_at
               FROM subscriptions s
               JOIN users         u ON s.user_id = u.user_id
              ORDER BY s.created_at DESC'
        );

        return $stmt->fetchAll();
    }

    // ──────────────────────────────────────────────────────────
    //  WRITE
    // ──────────────────────────────────────────────────────────

    /**
     * Create a new Core Club subscription for a user.
     * Uses INSERT … ON DUPLICATE KEY UPDATE so repeated submissions
     * from the same user simply refresh their plan/status rather than
     * erroring on the UNIQUE KEY.
     *
     * @param  int    $userId
     * @param  string $plan   'monthly' | 'bimonthly'
     * @return int            subscription_id of the created/updated row.
     * @throws InvalidArgumentException  On invalid plan slug.
     */
    public function subscribe(int $userId, string $plan): int
    {
        $this->assertValidPlan($plan);

        $nextDelivery = $this->computeNextDelivery($plan);

        $stmt = $this->db->prepare(
            'INSERT INTO subscriptions
                 (user_id, plan, status, discount, next_delivery_date)
             VALUES (?, ?, \'active\', ?, ?)
             ON DUPLICATE KEY UPDATE
                 plan               = VALUES(plan),
                 status             = \'active\',
                 next_delivery_date = VALUES(next_delivery_date),
                 updated_at         = CURRENT_TIMESTAMP'
        );
        $stmt->execute([$userId, $plan, self::CORE_CLUB_DISCOUNT, $nextDelivery]);

        // lastInsertId() returns 0 on UPDATE — fall back to a SELECT.
        $newId = (int) $this->db->lastInsertId();
        if ($newId === 0) {
            $row   = $this->getByUser($userId);
            $newId = $row ? (int) $row['subscription_id'] : 0;
        }

        return $newId;
    }

    /**
     * Change the status of an existing subscription.
     * Only valid transitions are allowed to prevent bad data.
     *
     * @param  int    $subscriptionId
     * @param  string $status         'active' | 'paused' | 'cancelled'
     * @return bool                   True if the row was changed.
     * @throws InvalidArgumentException  On invalid status slug.
     */
    public function updateStatus(int $subscriptionId, string $status): bool
    {
        $this->assertValidStatus($status);

        $stmt = $this->db->prepare(
            'UPDATE subscriptions
                SET status     = ?,
                    updated_at = CURRENT_TIMESTAMP
              WHERE subscription_id = ?'
        );
        $stmt->execute([$status, $subscriptionId]);

        return $stmt->rowCount() > 0;
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Compute the next delivery date from today based on the plan.
     *
     * @param  string $plan
     * @return string  Y-m-d formatted date string.
     */
    private function computeNextDelivery(string $plan): string
    {
        $days = ($plan === 'bimonthly') ? 60 : 30;

        return (new DateTimeImmutable())->modify("+{$days} days")->format('Y-m-d');
    }

    /** @throws InvalidArgumentException */
    private function assertValidPlan(string $plan): void
    {
        if (!in_array($plan, self::PLANS, true)) {
            throw new InvalidArgumentException(
                "Invalid plan '{$plan}'. Allowed: " . implode(', ', self::PLANS)
            );
        }
    }

    /** @throws InvalidArgumentException */
    private function assertValidStatus(string $status): void
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException(
                "Invalid status '{$status}'. Allowed: " . implode(', ', self::STATUSES)
            );
        }
    }
}
