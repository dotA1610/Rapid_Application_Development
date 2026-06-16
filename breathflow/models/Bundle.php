<?php

/**
 * ARC NEBU-PEN  |  Model: Bundle
 * ─────────────────────────────────────────────────────────────
 * Manages the `bundles` table — each user may hold exactly ONE
 * bundle (enforced by the UNIQUE KEY on user_id in the schema).
 *
 * Pricing logic
 *   Base bundle price = RM 57.00  (3 × RM 19.00 cartridges)
 *   The JavaScript frontend calculates the display total; PHP
 *   recalculates server-side from live product prices before save
 *   to prevent price tampering via form manipulation.
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Bundle
{
    private PDO $db;

    /** Canonical base price when all three cartridges are standard SKUs */
    public const BASE_PRICE = 57.00;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ──────────────────────────────────────────────────────────
    //  READ
    // ──────────────────────────────────────────────────────────

    /**
     * Return the user's saved bundle, joined with product names and images
     * so the dashboard and views don't need a second query.
     *
     * @param  int         $userId
     * @return array|false
     */
    public function getByUser(int $userId): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT
                b.bundle_id,
                b.user_id,
                b.total_price,
                b.updated_at,

                p1.product_id  AS flavor1_id,
                p1.name        AS flavor1_name,
                p1.image       AS flavor1_image,
                p1.price       AS flavor1_price,

                p2.product_id  AS flavor2_id,
                p2.name        AS flavor2_name,
                p2.image       AS flavor2_image,
                p2.price       AS flavor2_price,

                p3.product_id  AS flavor3_id,
                p3.name        AS flavor3_name,
                p3.image       AS flavor3_image,
                p3.price       AS flavor3_price

               FROM bundles      b
               JOIN products     p1 ON b.flavor1 = p1.product_id
               JOIN products     p2 ON b.flavor2 = p2.product_id
               JOIN products     p3 ON b.flavor3 = p3.product_id
              WHERE b.user_id = ?
              LIMIT 1'
        );
        $stmt->execute([$userId]);

        return $stmt->fetch();
    }

    // ──────────────────────────────────────────────────────────
    //  WRITE
    // ──────────────────────────────────────────────────────────

    /**
     * Create or update the user's bundle (INSERT … ON DUPLICATE KEY UPDATE).
     * Uses MySQL's upsert so a single call handles both first-save and edits.
     *
     * The total_price is calculated here from the database's live product
     * prices — not from anything the client submitted.
     *
     * @param  int   $userId
     * @param  int   $flavor1Id
     * @param  int   $flavor2Id
     * @param  int   $flavor3Id
     * @return float             The validated total that was stored.
     * @throws InvalidArgumentException  If any product_id is invalid.
     */
    public function save(int $userId, int $flavor1Id, int $flavor2Id, int $flavor3Id): float
    {
        $total = $this->calculateTotal($flavor1Id, $flavor2Id, $flavor3Id);

        $stmt = $this->db->prepare(
            'INSERT INTO bundles (user_id, flavor1, flavor2, flavor3, total_price)
                  VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                  flavor1     = VALUES(flavor1),
                  flavor2     = VALUES(flavor2),
                  flavor3     = VALUES(flavor3),
                  total_price = VALUES(total_price),
                  updated_at  = CURRENT_TIMESTAMP'
        );
        $stmt->execute([$userId, $flavor1Id, $flavor2Id, $flavor3Id, $total]);

        return $total;
    }

    // ──────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Sum the live prices of three products from the database.
     * Throws if any ID does not exist in the products table.
     *
     * @param  int   $id1
     * @param  int   $id2
     * @param  int   $id3
     * @return float
     * @throws InvalidArgumentException
     */
    private function calculateTotal(int $id1, int $id2, int $id3): float
    {
        $stmt = $this->db->prepare(
            'SELECT product_id, price
               FROM products
              WHERE product_id IN (?, ?, ?)'
        );
        $stmt->execute([$id1, $id2, $id3]);
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);   // [product_id => price]

        // Verify all three IDs were found.
        foreach ([$id1, $id2, $id3] as $id) {
            if (!array_key_exists($id, $rows)) {
                throw new InvalidArgumentException("Product ID {$id} does not exist.");
            }
        }

        return (float) ($rows[$id1] + $rows[$id2] + $rows[$id3]);
    }
}
