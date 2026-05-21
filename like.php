<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$id   = intval($body['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['error' => 'Invalid id']);
    exit;
}

// Simple IP-based like tracking using session
$likeKey = 'liked_' . $id;
if (isset($_SESSION[$likeKey])) {
    // Unlike
    $stmt = $conn->prepare("UPDATE shayaris SET likes = GREATEST(0, likes - 1) WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    unset($_SESSION[$likeKey]);
    $liked = false;
} else {
    // Like
    $stmt = $conn->prepare("UPDATE shayaris SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION[$likeKey] = true;
    $liked = true;
}

$row = $conn->query("SELECT likes FROM shayaris WHERE id = $id")->fetch_assoc();
echo json_encode(['success' => true, 'likes' => $row['likes'], 'liked' => $liked]);
