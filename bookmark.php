<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

$body   = json_decode(file_get_contents('php://input'), true);
$action = $body['action'] ?? 'toggle'; // toggle | list
$id     = intval($body['id'] ?? 0);

if ($action === 'list') {
    $saved = $_SESSION['bookmarks'] ?? [];
    if (empty($saved)) { echo json_encode(['success' => true, 'rows' => []]); exit; }

    $ids  = implode(',', array_map('intval', $saved));
    $rows = $conn->query("SELECT * FROM shayaris WHERE id IN ($ids) ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
    foreach ($rows as &$r) $r['liked'] = isset($_SESSION['liked_' . $r['id']]);
    echo json_encode(['success' => true, 'rows' => $rows], JSON_UNESCAPED_UNICODE);
    exit;
}

// toggle
if ($id <= 0) { echo json_encode(['error' => 'Invalid id']); exit; }

$bookmarks = $_SESSION['bookmarks'] ?? [];
if (in_array($id, $bookmarks)) {
    $bookmarks = array_values(array_diff($bookmarks, [$id]));
    $saved = false;
} else {
    array_unshift($bookmarks, $id);
    $saved = true;
}
$_SESSION['bookmarks'] = $bookmarks;

echo json_encode(['success' => true, 'saved' => $saved, 'count' => count($bookmarks)]);
