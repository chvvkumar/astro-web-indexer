<?php
require_once __DIR__ . '/../includes/init.php';

// Only accept POST and GET requests
$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['POST', 'GET'])) {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$conn = connectDB();

// Handle GET request - Retrieve settings for a folder
if ($method === 'GET') {
    $folder_path = isset($_GET['folder_path']) ? trim($_GET['folder_path']) : '/';
    
    // Make sure folder path starts with a /
    if (substr($folder_path, 0, 1) !== '/') {
        $folder_path = '/' . $folder_path;
    }
    
    try {
        // Find the most specific settings for this folder path
        $path_parts = explode('/', trim($folder_path, '/'));
        $paths_to_check = ['/'];  // Root path is always checked
        $current_path = '';
        
        foreach ($path_parts as $part) {
            if ($part === '') continue;
            
            if ($current_path) {
                $current_path .= '/' . $part;
            } else {
                $current_path = $part;
            }
            
            $paths_to_check[] = '/' . $current_path;
        }
        
        // Order from most specific to least specific
        $paths_to_check = array_reverse($paths_to_check);
        
        // Create placeholders for SQL query
        $placeholders = implode(',', array_fill(0, count($paths_to_check), '?'));
        
        // Query the database for the most specific matching settings
        $stmt = $conn->prepare("
            SELECT * FROM folder_stretch_settings 
            WHERE folder_path IN ($placeholders) 
            AND (apply_to_subfolders = 1 OR folder_path = ?)
            ORDER BY LENGTH(folder_path) DESC
            LIMIT 1
        ");
        
        // Bind parameters
        $i = 1;
        foreach ($paths_to_check as $path) {
            $stmt->bindValue($i++, $path);
        }
        $stmt->bindValue($i, $folder_path);
        
        $stmt->execute();
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($settings) {
            // Return settings along with the exact folder they're defined for
            echo json_encode([
                'success' => true,
                'settings' => $settings,
                'effective_folder' => $settings['folder_path'],
                'requested_folder' => $folder_path
            ]);
        } else {
            // Return default settings if none found
            echo json_encode([
                'success' => true,
                'settings' => [
                    'folder_path' => '/',
                    'stretch_type' => 'linear',
                    'linear_low_percent' => 0.5,
                    'linear_high_percent' => 99.5,
                    'stf_shadow_clip' => 0.0,
                    'stf_highlight_clip' => 0.0,
                    'stf_midtones_balance' => 0.5,
                    'stf_strength' => 1.0,
                    'apply_to_subfolders' => 1
                ],
                'effective_folder' => '/',
                'requested_folder' => $folder_path,
                'message' => 'Using default settings'
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle POST request - Save settings for a folder
if ($method === 'POST') {
    // Get JSON data from request body
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    if (!$data || !isset($data['folder_path'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data. folder_path is required.']);
        exit;
    }
    
    $folder_path = trim($data['folder_path']);
    
    // Make sure folder path starts with a /
    if (substr($folder_path, 0, 1) !== '/') {
        $folder_path = '/' . $folder_path;
    }
    
    // Default values
    $default_values = [
        'stretch_type' => 'linear',
        'linear_low_percent' => 0.5,
        'linear_high_percent' => 99.5,
        'stf_shadow_clip' => 0.0,
        'stf_highlight_clip' => 0.0,
        'stf_midtones_balance' => 0.5,
        'stf_strength' => 1.0,
        'apply_to_subfolders' => 1
    ];
    
    // Extract values from request, using defaults if not provided
    $settings = [];
    foreach ($default_values as $key => $default) {
        $settings[$key] = isset($data[$key]) ? $data[$key] : $default;
    }
    
    // Validate stretch_type
    $valid_stretch_types = ['linear', 'pixinsight_stf', 'custom'];
    if (!in_array($settings['stretch_type'], $valid_stretch_types)) {
        $settings['stretch_type'] = 'linear';
    }
    
    // Validate numeric fields
    $numeric_fields = [
        'linear_low_percent', 'linear_high_percent', 
        'stf_shadow_clip', 'stf_highlight_clip', 
        'stf_midtones_balance', 'stf_strength'
    ];
    
    foreach ($numeric_fields as $field) {
        $settings[$field] = is_numeric($settings[$field]) 
            ? (float)$settings[$field] 
            : (float)$default_values[$field];
    }
    
    // Validate boolean fields
    $settings['apply_to_subfolders'] = $settings['apply_to_subfolders'] ? 1 : 0;
    
    // Check if a setting already exists for this folder
    $stmt = $conn->prepare("SELECT id FROM folder_stretch_settings WHERE folder_path = ?");
    $stmt->execute([$folder_path]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    try {
        if ($existing) {
            // Update existing settings
            $stmt = $conn->prepare("
                UPDATE folder_stretch_settings SET
                    stretch_type = ?,
                    linear_low_percent = ?,
                    linear_high_percent = ?,
                    stf_shadow_clip = ?,
                    stf_highlight_clip = ?,
                    stf_midtones_balance = ?,
                    stf_strength = ?,
                    apply_to_subfolders = ?
                WHERE folder_path = ?
            ");
            
            $stmt->execute([
                $settings['stretch_type'],
                $settings['linear_low_percent'],
                $settings['linear_high_percent'],
                $settings['stf_shadow_clip'],
                $settings['stf_highlight_clip'],
                $settings['stf_midtones_balance'],
                $settings['stf_strength'],
                $settings['apply_to_subfolders'],
                $folder_path
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Settings updated successfully',
                'settings' => array_merge(['folder_path' => $folder_path], $settings)
            ]);
        } else {
            // Insert new settings
            $stmt = $conn->prepare("
                INSERT INTO folder_stretch_settings (
                    folder_path, stretch_type,
                    linear_low_percent, linear_high_percent,
                    stf_shadow_clip, stf_highlight_clip,
                    stf_midtones_balance, stf_strength,
                    apply_to_subfolders
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $folder_path,
                $settings['stretch_type'],
                $settings['linear_low_percent'],
                $settings['linear_high_percent'],
                $settings['stf_shadow_clip'],
                $settings['stf_highlight_clip'],
                $settings['stf_midtones_balance'],
                $settings['stf_strength'],
                $settings['apply_to_subfolders']
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Settings created successfully',
                'settings' => array_merge(['folder_path' => $folder_path], $settings)
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
