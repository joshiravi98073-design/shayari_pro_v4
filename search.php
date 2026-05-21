<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

$q    = trim($_GET['q']   ?? '');
$cat  = trim($_GET['cat'] ?? '');
$tag  = trim($_GET['tag'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$per  = 10;
$off  = ($page - 1) * $per;

$where  = ["1=1"];
$params = [];
$types  = '';

if ($q !== '') {
    $like = "%$q%";
    $where[] = "(text LIKE ? OR tags LIKE ?)";
    $params[] = $like; $params[] = $like;
    $types .= 'ss';
}
if ($cat !== '' && $cat !== 'ALL') {
    $where[] = "category = ?";
    $params[] = $cat;
    $types .= 's';
}
if ($tag !== '') {
    $like = "%$tag%";
    $where[] = "tags LIKE ?";
    $params[] = $like;
    $types .= 's';
}

$whereSQL = implode(' AND ', $where);

// Count total
$countSQL = "SELECT COUNT(*) as cnt FROM shayaris WHERE $whereSQL";
$cStmt = $conn->prepare($countSQL);
if ($types) $cStmt->bind_param($types, ...$params);
$cStmt->execute();
$total = $cStmt->get_result()->fetch_assoc()['cnt'];

// Fetch page
$params[] = $per; $params[] = $off;
$types .= 'ii';
$dataSQL = "SELECT id, text, category, tags, is_featured, likes, created_at FROM shayaris WHERE $whereSQL ORDER BY is_featured DESC, created_at DESC LIMIT ? OFFSET ?";
$dStmt = $conn->prepare($dataSQL);
$dStmt->bind_param($types, ...$params);
$dStmt->execute();
$rows = $dStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Mark liked (session based)
foreach ($rows as &$r) {
    $r['liked'] = isset($_SESSION['liked_' . $r['id']]);
}

echo json_encode([
    'success' => true,
    'total'   => $total,
    'page'    => $page,
    'pages'   => ceil($total / $per),
    'rows'    => $rows
], JSON_UNESCAPED_UNICODE);
