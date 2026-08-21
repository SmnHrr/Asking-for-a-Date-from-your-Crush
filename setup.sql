-- ─────────────────────────────────────────────────────────
--  setup.sql  —  Run this ONCE to create the database
--  and the responses table.
--
--  In phpMyAdmin: click SQL tab → paste → Go
--  In MySQL CLI:  mysql -u root -p < setup.sql
-- ─────────────────────────────────────────────────────────

CREATE DATABASE IF NOT EXISTS date_me
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE date_me;

CREATE TABLE IF NOT EXISTS responses (
    id           INT          NOT NULL AUTO_INCREMENT,
    answer       TEXT         NOT NULL,
    submitted_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
