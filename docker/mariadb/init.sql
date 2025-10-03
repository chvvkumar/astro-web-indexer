-- Folder-specific stretch settings
CREATE TABLE IF NOT EXISTS folder_stretch_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folder_path VARCHAR(1024) NOT NULL,
    stretch_type ENUM('linear', 'pixinsight_stf', 'custom') NOT NULL DEFAULT 'linear',
    -- Linear stretch parameters
    linear_low_percent FLOAT NOT NULL DEFAULT 0.5,
    linear_high_percent FLOAT NOT NULL DEFAULT 99.5,
    -- PixInsight STF parameters
    stf_shadow_clip FLOAT NOT NULL DEFAULT 0.0, 
    stf_highlight_clip FLOAT NOT NULL DEFAULT 0.0,
    stf_midtones_balance FLOAT NOT NULL DEFAULT 0.5,
    stf_strength FLOAT NOT NULL DEFAULT 1.0,
    -- Common settings
    apply_to_subfolders TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE INDEX idx_folder_path (folder_path)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default values for root folder
INSERT INTO folder_stretch_settings 
    (folder_path, stretch_type, linear_low_percent, linear_high_percent, 
     stf_shadow_clip, stf_highlight_clip, stf_midtones_balance, stf_strength, 
     apply_to_subfolders)
VALUES
    ('/', 'linear', 0.5, 99.5, 0.0, 0.0, 0.5, 1.0, 1)
ON DUPLICATE KEY UPDATE
    updated_at = CURRENT_TIMESTAMP;

CREATE TABLE IF NOT EXISTS files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
        path VARCHAR(1024) NOT NULL,
        file_hash VARCHAR(64) NOT NULL,
    mtime DECIMAL(16, 6) NOT NULL,
    file_size BIGINT NOT NULL,
    object VARCHAR(255),
    date_obs DATETIME,
    exptime FLOAT,
    filter VARCHAR(50),
    imgtype VARCHAR(50),
    -- Extended metadata
    xbinning INT,
    ybinning INT,
    egain FLOAT,
    `offset` FLOAT,
    xpixsz FLOAT,
    ypixsz FLOAT,
    instrume VARCHAR(255),
    set_temp FLOAT,
    ccd_temp FLOAT,
    telescop VARCHAR(255),
    focallen FLOAT,
    focratio FLOAT,
    ra FLOAT,
    `dec` FLOAT,
    centalt FLOAT,
    centaz FLOAT,
    airmass FLOAT,
    pierside VARCHAR(50),
    siteelev FLOAT,
    sitelat FLOAT,
    sitelong FLOAT,
        focpos INT,
    -- Housekeeping
            thumb MEDIUMBLOB,
    total_duplicate_count INT NOT NULL DEFAULT 1,
    visible_duplicate_count INT NOT NULL DEFAULT 1,
    is_hidden TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
                -- Indexes
    UNIQUE INDEX idx_path (path),
    INDEX idx_file_hash (file_hash),
    INDEX idx_deleted_at (deleted_at),
    INDEX idx_object (object),
    INDEX idx_filter (filter),
    INDEX idx_imgtype (imgtype),
    INDEX idx_instrume (instrume),
                INDEX idx_telescop (telescop),
    INDEX idx_total_duplicate_count (total_duplicate_count),
    INDEX idx_visible_duplicate_count (visible_duplicate_count),
    INDEX idx_is_hidden (is_hidden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
