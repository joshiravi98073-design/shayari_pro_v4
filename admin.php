<?php
require_once 'config.php';

// Simple admin password (change this!)
define('ADMIN_PASS', 'admin@shayari123');

// Admin login check
if (!isset($_SESSION['admin_ok'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_pass'])) {
        if ($_POST['admin_pass'] === ADMIN_PASS) {
            $_SESSION['admin_ok'] = true;
        } else {
            $loginError = 'Wrong password!';
        }
    }
    if (!isset($_SESSION['admin_ok'])) {
        // Show login form
        echo '<!DOCTYPE html><html lang="hi"><head><meta charset="UTF-8"><title>Admin Login</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:system-ui,sans-serif}
        body{background:#050816;color:#f9fafb;min-height:100vh;display:flex;align-items:center;justify-content:center}
        .box{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:32px;width:320px}
        h2{margin-bottom:16px;font-size:1.2rem}
        input{width:100%;padding:8px 12px;border-radius:8px;border:1px solid #374151;background:#020617;color:#e5e7eb;font-size:0.9rem;margin-bottom:12px}
        button{width:100%;padding:10px;border-radius:999px;border:none;background:#22c55e;color:#020617;font-weight:600;cursor:pointer}
        .err{color:#f87171;font-size:0.8rem;margin-bottom:10px}
        </style></head><body>
        <div class="box">
        <h2>Admin Login</h2>';
        if (isset($loginError)) echo '<p class="err">' . htmlspecialchars($loginError) . '</p>';
        echo '<form method="POST">
        <input type="password" name="admin_pass" placeholder="Admin password" required autofocus>
        <button type="submit">Login</button>
        </form></div></body></html>';
        exit;
    }
}

// ── Admin actions ──────────────────────────────────────────────────────────
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $sid    = intval($_POST['sid'] ?? 0);

    if ($action === 'delete' && $sid > 0) {
        $conn->query("DELETE FROM shayaris WHERE id = $sid");
        $msg = 'Shayari deleted!';
    } elseif ($action === 'feature' && $sid > 0) {
        $conn->query("UPDATE shayaris SET is_featured = 1 WHERE id = $sid");
        $msg = 'Marked as Featured!';
    } elseif ($action === 'unfeature' && $sid > 0) {
        $conn->query("UPDATE shayaris SET is_featured = 0 WHERE id = $sid");
        $msg = 'Removed from Featured!';
    } elseif ($action === 'logout') {
        unset($_SESSION['admin_ok']);
        header('Location: admin.php');
        exit;
    }
}

// ── Stats ──────────────────────────────────────────────────────────────────
$total     = $conn->query("SELECT COUNT(*) as c FROM shayaris")->fetch_assoc()['c'];
$featured  = $conn->query("SELECT COUNT(*) as c FROM shayaris WHERE is_featured=1")->fetch_assoc()['c'];
$totalLikes= $conn->query("SELECT COALESCE(SUM(likes),0) as c FROM shayaris")->fetch_assoc()['c'];
$catFilter = $_GET['cat'] ?? '';
$q = $catFilter ? "WHERE category='$catFilter'" : '';
$shayaris  = $conn->query("SELECT * FROM shayaris $q ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<title>Admin Panel — AI Shayari</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',system-ui,sans-serif}
body{background:#050816;color:#f9fafb;min-height:100vh}
.top{background:#0d1117;border-bottom:1px solid #1f2937;padding:12px 20px;display:flex;justify-content:space-between;align-items:center}
.top h1{font-size:1.1rem;color:#22c55e}
.top a{color:#9ca3af;font-size:0.85rem;text-decoration:none}
.container{max-width:1100px;margin:0 auto;padding:20px}
.stats{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px}
.stat{background:#111827;border:1px solid #1f2937;border-radius:10px;padding:14px 20px;flex:1;min-width:120px}
.stat .num{font-size:2rem;font-weight:700;color:#22c55e}
.stat .lbl{font-size:0.75rem;color:#9ca3af;margin-top:2px}
.msg{background:#22c55e22;border:1px solid #22c55e44;color:#bbf7d0;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:0.85rem}
.filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
.filters a{padding:5px 12px;border-radius:999px;border:1px solid #374151;color:#e5e7eb;font-size:0.8rem;text-decoration:none}
.filters a.active,.filters a:hover{background:#22c55e;border-color:#22c55e;color:#020617}
table{width:100%;border-collapse:collapse;font-size:0.85rem}
th{text-align:left;padding:10px 12px;border-bottom:1px solid #1f2937;color:#9ca3af;font-weight:500}
td{padding:10px 12px;border-bottom:1px solid #111827;vertical-align:top}
.shayari-text{white-space:pre-line;max-width:400px;line-height:1.5}
.badge{display:inline-block;padding:2px 8px;border-radius:999px;font-size:0.7rem}
.badge.featured{background:#22c55e22;color:#bbf7d0;border:1px solid #22c55e44}
.badge.cat{background:#6366f122;color:#c7d2fe;border:1px solid #6366f144}
.btn-sm{padding:4px 10px;border-radius:6px;border:none;font-size:0.75rem;cursor:pointer;margin-right:4px}
.btn-del{background:#991b1b;color:#fca5a5}
.btn-del:hover{background:#7f1d1d}
.btn-feat{background:#14532d;color:#bbf7d0}
.btn-feat:hover{background:#166534}
.btn-unfeat{background:#78350f;color:#fde68a}
.btn-unfeat:hover{background:#92400e}
.likes{color:#f87171;font-size:0.85rem}
@media(max-width:700px){.shayari-text{max-width:200px}}
</style>
</head>
<body>
<div class="top">
    <h1>Admin Panel — AI Shayari Generator</h1>
    <form method="POST" style="display:inline">
        <input type="hidden" name="action" value="logout">
        <button type="submit" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:0.85rem">Logout</button>
    </form>
</div>

<div class="container">

    <!-- Stats -->
    <div class="stats">
        <div class="stat"><div class="num"><?= $total ?></div><div class="lbl">Total Shayaris</div></div>
        <div class="stat"><div class="num"><?= $featured ?></div><div class="lbl">Featured</div></div>
        <div class="stat"><div class="num"><?= $totalLikes ?></div><div class="lbl">Total Likes</div></div>
        <div class="stat"><div class="num"><?= count($shayaris) ?></div><div class="lbl">Showing Now</div></div>
    </div>

    <?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Category filter -->
    <div class="filters">
        <a href="admin.php" class="<?= !$catFilter ? 'active' : '' ?>">All</a>
        <?php foreach (['ATTITUDE','MOTIVATION','DOSTI_COLLEGE','DARD','SELF'] as $c): ?>
        <a href="admin.php?cat=<?= $c ?>" class="<?= $catFilter === $c ? 'active' : '' ?>"><?= $c ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Shayari table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Shayari</th>
                <th>Category</th>
                <th>Tags</th>
                <th class="likes">Likes</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($shayaris as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><div class="shayari-text"><?= htmlspecialchars($s['text'], ENT_QUOTES, 'UTF-8') ?></div></td>
            <td><span class="badge cat"><?= htmlspecialchars($s['category']) ?></span></td>
            <td style="color:#9ca3af;font-size:0.75rem"><?= htmlspecialchars($s['tags'] ?? '') ?></td>
            <td class="likes"><?= $s['likes'] ?></td>
            <td><?= $s['is_featured'] ? '<span class="badge featured">Featured</span>' : '' ?></td>
            <td>
                <form method="POST" style="display:inline" onsubmit="return confirm('Delete karna chahte ho?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="sid" value="<?= $s['id'] ?>">
                    <button type="submit" class="btn-sm btn-del">Delete</button>
                </form>
                <?php if ($s['is_featured']): ?>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="action" value="unfeature">
                    <input type="hidden" name="sid" value="<?= $s['id'] ?>">
                    <button type="submit" class="btn-sm btn-unfeat">Unfeature</button>
                </form>
                <?php else: ?>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="action" value="feature">
                    <input type="hidden" name="sid" value="<?= $s['id'] ?>">
                    <button type="submit" class="btn-sm btn-feat">Feature</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
