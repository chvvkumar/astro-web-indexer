-- Create table for folder-specific stretch settings
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
