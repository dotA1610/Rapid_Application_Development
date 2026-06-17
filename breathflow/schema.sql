-- =============================================================
--  ARC NEBU-PEN  |  Database Schema
--  Engine  : InnoDB
--  Charset : utf8mb4 / utf8mb4_unicode_ci
--  Version : 1.0.0
-- =============================================================

CREATE DATABASE IF NOT EXISTS `arc_nebupen`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `arc_nebupen`;

-- -------------------------------------------------------------
--  TABLE: users
--  Stores customer and admin accounts.
--  role ENUM enforces the two access levels; password stores
--  a bcrypt hash (never plaintext).
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `user_id`    INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `fullname`   VARCHAR(120)     NOT NULL,
    `email`      VARCHAR(180)     NOT NULL,
    `password`   VARCHAR(255)     NOT NULL COMMENT 'bcrypt hash',
    `role`       ENUM('admin','staff','manager','customer') NOT NULL DEFAULT 'customer',
    `created_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`user_id`),
    UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Customer and admin accounts';


-- -------------------------------------------------------------
--  TABLE: products
--  Individual sensory cartridge SKUs (Mint, Berry, Citrus, etc.)
--  price stored in DECIMAL to avoid floating-point drift.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `product_id`  INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100)      NOT NULL,
    `description` TEXT              NOT NULL,
    `price`       DECIMAL(8,2)      NOT NULL DEFAULT '0.00',
    `image`       VARCHAR(255)      NOT NULL DEFAULT '' COMMENT 'Relative path, e.g. assets/images/mint.png',
    `stock`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`product_id`),
    INDEX `idx_products_name` (`name`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Sensory cartridge product catalogue';


-- -------------------------------------------------------------
--  TABLE: bundles
--  Stores a user's saved 3-cartridge bundle selection.
--  flavor1/2/3 are FKs to products so we keep referential
--  integrity; total_price is denormalised for quick reads.
--  One active bundle per user (uq_bundles_user).
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bundles` (
    `bundle_id`   INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED  NOT NULL,
    `flavor1`     INT UNSIGNED  NOT NULL COMMENT 'FK -> products.product_id',
    `flavor2`     INT UNSIGNED  NOT NULL COMMENT 'FK -> products.product_id',
    `flavor3`     INT UNSIGNED  NOT NULL COMMENT 'FK -> products.product_id',
    `total_price` DECIMAL(8,2)  NOT NULL DEFAULT '57.00' COMMENT 'Base RM57.00',
    `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`bundle_id`),
    UNIQUE KEY `uq_bundles_user` (`user_id`),          -- one bundle per user
    INDEX `idx_bundles_flavor1` (`flavor1`),
    INDEX `idx_bundles_flavor2` (`flavor2`),
    INDEX `idx_bundles_flavor3` (`flavor3`),

    CONSTRAINT `fk_bundles_user`
        FOREIGN KEY (`user_id`)  REFERENCES `users`    (`user_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bundles_flavor1`
        FOREIGN KEY (`flavor1`)  REFERENCES `products`  (`product_id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_bundles_flavor2`
        FOREIGN KEY (`flavor2`)  REFERENCES `products`  (`product_id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_bundles_flavor3`
        FOREIGN KEY (`flavor3`)  REFERENCES `products`  (`product_id`) ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='User-saved 3-cartridge bundle selections';


-- -------------------------------------------------------------
--  TABLE: subscriptions
--  Core Club membership records.
--  plan   : monthly | bimonthly
--  status : active | paused | cancelled
--  discount: stored as a whole-number percentage (e.g. 20 = 20%)
--  next_delivery_date tracks the upcoming shipment.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscriptions` (
    `subscription_id`    INT UNSIGNED          NOT NULL AUTO_INCREMENT,
    `user_id`            INT UNSIGNED          NOT NULL,
    `plan`               ENUM('monthly','bimonthly') NOT NULL DEFAULT 'monthly',
    `status`             ENUM('active','paused','cancelled') NOT NULL DEFAULT 'active',
    `discount`           TINYINT UNSIGNED      NOT NULL DEFAULT 20 COMMENT 'Percentage, e.g. 20',
    `next_delivery_date` DATE                  NULL     DEFAULT NULL,
    `created_at`         TIMESTAMP             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         TIMESTAMP             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`subscription_id`),
    UNIQUE KEY `uq_subscriptions_user` (`user_id`),    -- one active sub per user
    INDEX `idx_subscriptions_status` (`status`),

    CONSTRAINT `fk_subscriptions_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Core Club subscription records';


-- =============================================================
--  SEED DATA
-- =============================================================

-- Admin account  (password = 'Admin@1234'  — bcrypt placeholder)
-- Replace the hash below with a real bcrypt hash before production.
INSERT INTO `users` (`fullname`, `email`, `password`, `role`) VALUES
('ARC Admin',   'admin@arcnebupen.com',  '$2y$12$PLACEHOLDER_HASH_ADMIN_______________', 'admin'),
('Aiman Razif', 'aiman@example.com',     '$2y$12$PLACEHOLDER_HASH_USER1_______________', 'customer'),
('Sarah Lim',   'sarah@example.com',     '$2y$12$PLACEHOLDER_HASH_USER2_______________', 'customer');

-- Sensory cartridge catalogue (RM19.00 each — 3 × RM19 = RM57 base bundle)
INSERT INTO `products` (`name`, `description`, `price`, `image`, `stock`) VALUES
('Lavender',
 'Calming & Relaxing. Helps you unwind and promotes better sleep.',
 19.00, 'assets/images/lavender.png', 50),

('Mint',
 'Cooling & Refreshing. Awakens your senses and improves focus.',
 19.00, 'assets/images/mint.png', 45),

('Citrus',
 'Bright & Uplifting. Boosts mood and energises your day.',
 19.00, 'assets/images/citrus.png', 40),

('Berry',
 'Smooth & Soothing. A gentle blend for everyday balance.',
 19.00, 'assets/images/berry.png', 35);
