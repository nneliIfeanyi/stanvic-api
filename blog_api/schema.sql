-- =============================================================================
-- Inkwell Blog Reader — Database Schema
-- Run this once against your MySQL / MariaDB database to create the table
-- that backs the blog reader's post content.
-- =============================================================================

CREATE DATABASE IF NOT EXISTS inkwell_blog
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE inkwell_blog;

CREATE TABLE IF NOT EXISTS posts (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title         VARCHAR(255)     NOT NULL,
  author        VARCHAR(150)     NOT NULL,
  date_posted   DATE             NOT NULL,
  last_edited   DATE             NOT NULL,
  reading_time  SMALLINT UNSIGNED NOT NULL DEFAULT 5,   -- minutes
  category      VARCHAR(100)     NOT NULL,
  thumbnail     VARCHAR(500)     DEFAULT NULL,           -- image URL
  excerpt       VARCHAR(500)     DEFAULT NULL,           -- short teaser shown in the list card
  content       LONGTEXT         NOT NULL,               -- full article body (HTML)
  created_at    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                  ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_category (category),
  INDEX idx_date_posted (date_posted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
