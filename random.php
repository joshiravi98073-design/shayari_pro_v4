<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

$cat  = trim($_GET['cat'] ?? '');
$excl = intval($_GET['exclude'] ?? 0); // exclude current id

$where  = $excl > 0 ? "WHERE id != $excl" : "WHERE 1=1";
$catSQL = '';
if ($cat && $cat !== 'ALL') {
    $safe   = $conn->real_escape_string($cat);
    $where .= " AND category = '$safe'";
}

$row = $conn->query("SELECT * FROM shayaris $where ORDER BY RAND() LIMIT 1")->fetch_assoc();

if (!$row) {
    echo json_encode(['error' => 'Koi shayari nahi mili']); exit;
}

$row['liked'] = isset($_SESSION['liked_' . $row['id']]);
echo json_encode(['success' => true, 'shayari' => $row], JSON_UNESCAPED_UNICODE);
