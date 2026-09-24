-- =============================================================================
-- Alzikrayat — Photo Sharing Web Application
-- Database Schema
--
-- Source of truth: Project 1 Specification PDF, Section 5 (Database Schema &
-- Data Constraints). Three normalized tables are required: Users, Photos,
-- Comments. Primary keys are numeric AUTO_INCREMENT integers. Foreign keys
-- enforce referential integrity with CASCADE deletes as specified:
--   - Deleting a User cascades to that user's Photos (Sec 5.2) and Comments (Sec 5.3).
--   - Deleting a Photo cascades to that photo's Comments (Sec 5.3).
-- =============================================================================

CREATE DATABASE IF NOT EXISTS alzikrayat
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE alzikrayat;

-- -----------------------------------------------------------------------------
-- Users
-- Required fields (PDF Sec 5.1): id, first_name, last_name, email, password,
-- location, description, occupation.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    first_name    VARCHAR(50)  NOT NULL,
    last_name     VARCHAR(50)  NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    location      VARCHAR(100) NULL,
    description   TEXT         NULL,
    occupation    VARCHAR(100) NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Photos
-- Required fields (PDF Sec 5.2): id, user_id, file_name, title, description,
-- date_time. user_id cascades on User delete.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS photos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    file_name     VARCHAR(255) NOT NULL,
    title         VARCHAR(200) NOT NULL,
    description   TEXT NULL,
    date_time     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_photos_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Comments
-- Required fields (PDF Sec 5.3): id, photo_id, user_id, comment, date_time.
-- Cascades on both Photo delete and User delete.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS comments (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    photo_id      INT NOT NULL,
    user_id       INT NOT NULL,
    comment       TEXT NOT NULL,
    date_time     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_photo
        FOREIGN KEY (photo_id) REFERENCES photos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Indexes to support common lookups (gallery listing, photo detail comments).
CREATE INDEX idx_photos_user_id ON photos(user_id);
CREATE INDEX idx_comments_photo_id ON comments(photo_id);
CREATE INDEX idx_comments_user_id ON comments(user_id);
