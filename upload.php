<?php
// This file handles AJAX uploads if needed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    require_once 'includes/functions.php';
    
    $result = uploadImage($_FILES['image']);
    if ($result) {
        echo json_encode(['success' => true, 'filename' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
}
?>
