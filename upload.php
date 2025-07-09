
<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $userId = $_SESSION['user_id'];
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    $fileName = basename($file['name']);
    $targetPath = $uploadDir . time() . '_' . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $stmt = $pdo->prepare("INSERT INTO files (user_id, file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $fileName, $targetPath, $file['type'], $file['size']]);
        header("Location: home.php?upload=success");
    } else {
        echo "Failed to upload file.";
    }
}
?>
