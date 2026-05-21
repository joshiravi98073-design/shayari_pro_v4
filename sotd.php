<?php
// Shayari of the Day — auto-rotates daily using date seed
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

$today    = date('Y-m-d');
$dayNum   = (int) date('z'); // 0–365

// Pick a featured shayari based on day of year
$total = $conn->query("SELECT COUNT(*) as c FROM shayaris WHERE is_featured = 1")->fetch_assoc()['c'];

if ($total === 0) {
    echo json_encode(['error' => 'No featured shayaris']); exit;
}

$offset = $dayNum % $total;
$stmt   = $conn->prepare("SELECT * FROM shayaris WHERE is_featured = 1 ORDER BY id ASC LIMIT 1 OFFSET ?");
$stmt->bind_param("i", $offset);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    echo json_encode(['error' => 'Not found']); exit;
}

$row['liked'] = isset($_SESSION['liked_' . $row['id']]);
echo json_encode(['success' => true, 'shayari' => $row, 'date' => $today], JSON_UNESCAPED_UNICODE);
