<?php

/**
 * ARC NEBU-PEN  |  Model: Product
 * ─────────────────────────────────────────────────────────────
 * Encapsulates all SQL against the `products` table.
 * ProductController is the only caller.
 * ─────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Product
{
    private PDO $db;

    /** Allowed image extensions (validated before storing path) */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /** Upload directory relative to breathflow root */
    public const UPLOAD_DIR = 'assets/images/products/';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ──────────────────────────────────────────────────────────
    //  READ
    // ──────────────────────────────────────────────────────────

    /**
     * Return all products ordered by name (public catalogue).
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT product_id, name, description, price, image, stock, created_at
               FROM products
              ORDER BY name ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Return products that are in stock (stock > 0).
     * Used by the public-facing Sensory Profiles and Bundle Builder pages.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAvailable(): array
    {
        $stmt = $this->db->query(
            'SELECT product_id, name, description, price, image, stock
               FROM products
              WHERE stock > 0
              ORDER BY name ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Fetch a single product by PK.
     *
     * @param  int         $productId
     * @return array|false
     */
    public function getById(int $productId): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT product_id, name, description, price, image, stock, updated_at
               FROM products
              WHERE product_id = ?
              LIMIT 1'
        );
        $stmt->execute([$productId]);

        return $stmt->fetch();
    }

    // ──────────────────────────────────────────────────────────
    //  WRITE  (admin-only operations)
    // ──────────────────────────────────────────────────────────

    /**
     * Insert a new product.
     *
     * @param  string $name
     * @param  string $description
     * @param  float  $price
     * @param  string $imagePath   Relative path stored in DB, e.g. 'assets/images/products/mint.png'
     * @param  int    $stock
     * @return int                 New product_id.
     */
    public function create(
        string $name,
        string $description,
        float  $price,
        string $imagePath,
        int    $stock
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO products (name, description, price, image, stock)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $description, $price, $imagePath, $stock]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing product.
     * Pass null for $imagePath to keep the current image.
     *
     * @param  int         $productId
     * @param  string      $name
     * @param  string      $description
     * @param  float       $price
     * @param  string|null $imagePath   New path, or null to retain existing.
     * @param  int         $stock
     * @return bool                     True if a row was actually changed.
     */
    public function update(
        int    $productId,
        string $name,
        string $description,
        float  $price,
        ?string $imagePath,
        int    $stock
    ): bool {
        if ($imagePath !== null) {
            $stmt = $this->db->prepare(
                'UPDATE products
                    SET name = ?, description = ?, price = ?, image = ?, stock = ?
                  WHERE product_id = ?'
            );
            $stmt->execute([$name, $description, $price, $imagePath, $stock, $productId]);
        } else {
            $stmt = $this->db->prepare(
                'UPDATE products
                    SET name = ?, description = ?, price = ?, stock = ?
                  WHERE product_id = ?'
            );
            $stmt->execute([$name, $description, $price, $stock, $productId]);
        }

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a product by PK.
     * RESTRICT FK on bundles prevents deletion if the product is
     * referenced in any saved bundle — the controller handles that
     * exception and surfaces a user-friendly message.
     *
     * @param  int  $productId
     * @return bool
     */
    public function delete(int $productId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM products WHERE product_id = ?'
        );
        $stmt->execute([$productId]);

        return $stmt->rowCount() > 0;
    }

    // ──────────────────────────────────────────────────────────
    //  HELPER
    // ──────────────────────────────────────────────────────────

    /**
     * Validate an uploaded image file and return the extension,
     * or throw InvalidArgumentException on failure.
     *
     * @param  array  $file  $_FILES['image'] element.
     * @return string        Lowercase extension, e.g. 'png'.
     */
    public static function validateImageUpload(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('File upload error code: ' . $file['error']);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            throw new InvalidArgumentException(
                'Invalid file type. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS)
            );
        }

        // MIME sniff (more reliable than relying on extension alone).
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mimeType, $allowedMimes, true)) {
            throw new InvalidArgumentException('File content does not match an allowed image type.');
        }

        return $ext;
    }
}
