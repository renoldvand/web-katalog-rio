<?php
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateStars($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;
    
    $stars = '';
    for($i = 0; $i < $fullStars; $i++) {
        $stars .= '<i class="fas fa-star"></i>';
    }
    if($halfStar) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    for($i = 0; $i < $emptyStars; $i++) {
        $stars .= '<i class="far fa-star"></i>';
    }
    return $stars;
}

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function uploadImage($file) {
    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        error_log("Upload error: " . ($file['error'] ?? 'No file uploaded'));
        return false;
    }
    
    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        error_log("Invalid file type: " . $file['type']);
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        error_log("File too large: " . $file['size'] . " bytes");
        return false;
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = __DIR__ . '/../assets/images/products/';
    
    // Create directory with proper permissions
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log("Failed to create upload directory: " . $uploadDir);
            return false;
        }
        // Set proper permissions
        chmod($uploadDir, 0755);
    }
    
    // Check if directory is writable
    if (!is_writable($uploadDir)) {
        error_log("Upload directory is not writable: " . $uploadDir);
        // Try to fix permissions
        chmod($uploadDir, 0755);
        if (!is_writable($uploadDir)) {
            error_log("Still cannot write to directory after chmod");
            return false;
        }
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Verify file was actually moved
        if (file_exists($filepath)) {
            error_log("Successfully uploaded: " . $filename);
            return $filename;
        } else {
            error_log("File move failed - file not found at destination");
            return false;
        }
    } else {
        error_log("Failed to move uploaded file from " . $file['tmp_name'] . " to " . $filepath);
        return false;
    }
}
?>
