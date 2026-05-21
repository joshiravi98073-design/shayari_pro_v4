<?php
require_once 'config.php';

$categories = [
    'ALL'           => ['label'=>'सब',              'emoji'=>'✨'],
    'ATTITUDE'      => ['label'=>'Attitude',        'emoji'=>'🔥'],
    'MOTIVATION'    => ['label'=>'Motivation',      'emoji'=>'⚡'],
    'DOSTI_COLLEGE' => ['label'=>'Dosti / College', 'emoji'=>'🧡'],
    'DARD'          => ['label'=>'Dard / Breakup',  'emoji'=>'💔'],
    'SELF'          => ['label'=>'Self-Love',       'emoji'=>'💫'],
    'ZINDAGI'       => ['label'=>'Zindagi',         'emoji'=>'🌿'],
    'GHALIB'        => ['label'=>'Mirza Ghalib',    'emoji'=>'🖋️'],
    'JOHN_ELIA'     => ['label'=>'John Elia',       'emoji'=>'🌑'],
    'FAIZ'          => ['label'=>'Faiz Ahmed Faiz', 'emoji'=>'✊'],
    'RAHAT'         => ['label'=>'Rahat Indori',    'emoji'=>'🎙️'],
    'BASHIR_BADR'   => ['label'=>'Bashir Badr',     'emoji'=>'🌹'],
    'GULZAR'        => ['label'=>'Gulzar',          'emoji'=>'🎬'],
    'IQBAL'         => ['label'=>'Allama Iqbal',    'emoji'=>'🦅'],
    'SAHIR'         => ['label'=>'Sahir Ludhianvi', 'emoji'=>'🎵'],
];

$famousPoets  = ['GHALIB','JOHN_ELIA','FAIZ','RAHAT','BASHIR_BADR','GULZAR','IQBAL','SAHIR'];
$aiCategories = ['ALL','ATTITUDE','MOTIVATION','DOSTI_COLLEGE','DARD','SELF','ZINDAGI'];

$poetInfo = [
    'GHALIB'      => ['name'=>'Mirza Ghalib',      'era'=>'1797–1869','tag'=>'Urdu ka sartaj shayar'],
    'JOHN_ELIA'   => ['name'=>'John Elia',          'era'=>'1931–2002','tag'=>'Bebaak aur dard bhari awaaz'],
    'FAIZ'        => ['name'=>'Faiz Ahmed Faiz',    'era'=>'1911–1984','tag'=>'Inquilab aur ishq ka shayar'],
    'RAHAT'       => ['name'=>'Rahat Indori',        'era'=>'1950–2020','tag'=>'Awaam ka shayar'],
    'BASHIR_BADR' => ['name'=>'Bashir Badr',        'era'=>'1935–',    'tag'=>'Pyaar aur zindagi ka shayar'],
    'GULZAR'      => ['name'=>'Gulzar',             'era'=>'1934–',    'tag'=>'Films, kavita, zindagi'],
    'IQBAL'       => ['name'=>'Allama Iqbal',       'era'=>'1877–1938','tag'=>'Khudi aur qaum ka shayar'],
    'SAHIR'       => ['name'=>'Sahir Ludhianvi',    'era'=>'1921–1980','tag'=>'Films ka mahaan shayar'],
];

$currentCat = $_GET['cat']  ?? 'ALL';
$page       = max(1, intval($_GET['page'] ?? 1));
$perPage    = 10;
$offset     = ($page - 1) * $perPage;

if ($currentCat === 'ALL') {
    $cntS = $conn->prepare("SELECT COUNT(*) as c FROM shayaris");
    $cntS->execute();
    $total = $cntS->get_result()->fetch_assoc()['c'];
    $stmt  = $conn->prepare("SELECT * FROM shayaris ORDER BY is_featured DESC, created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $perPage, $offset);
} else {
    $cntS = $conn->prepare("SELECT COUNT(*) as c FROM shayaris WHERE category = ?");
    $cntS->bind_param("s", $currentCat); $cntS->execute();
    $total = $cntS->get_result()->fetch_assoc()['c'];
    $stmt  = $conn->prepare("SELECT * FROM shayaris WHERE category = ? ORDER BY is_featured DESC, created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $currentCat, $perPage, $offset);
}
$stmt->execute();
$result     = $stmt->get_result();
$totalPages = max(1, ceil($total / $perPage));
$bookmarks  = $_SESSION['bookmarks'] ?? [];
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<title>AI Shayari Generator — Hindi Shayari</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="AI se generate karo powerful Hindi shayari — Attitude, Dosti, Dard, Zindagi, Motivation. Famous shayars — Ghalib, John Elia, Faiz aur bahut kuch.">
<style>
:root{
  --bg:#050816;--surf:#0d1117;--surf2:#111827;
  --bdr:#1f2937;--bdr2:#374151;
  --text:#f9fafb;--muted:#9ca3af;--hint:#6b7280;
  --green:#22c55e;--gd:#16a34a;--gdim:#22c55e1a;
  --red:#f87171;--gold:#eab308;--purple:#818cf8;
  --radius:10px;
}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',system-ui,sans-serif}
body{background:var(--bg);color:var(--text);min-height:100vh}
a{text-decoration:none;color:inherit}

/* NAV */
nav{background:var(--surf);border-bottom:1px solid var(--bdr);padding:10px 16px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:200}
.nav-brand{font-size:1rem;font-weight:700;color:var(--green)}
.nav-right{display:flex;gap:8px;align-items:center}
.nav-right a{font-size:0.78rem;color:var(--muted);padding:4px 10px;border-radius:999px;border:1px solid transparent;transition:.2s}
.nav-right a:hover{border-color:var(--bdr2);color:var(--text)}
.nav-bm-btn{background:none;border:1px solid var(--bdr2);border-radius:999px;color:var(--text);padding:4px 10px;font-size:0.78rem;cursor:pointer;transition:.2s;position:relative}
.nav-bm-btn:hover{border-color:var(--gold);color:var(--gold)}
.bm-count{background:var(--green);color:#020617;border-radius:999px;padding:0 5px;font-size:0.65rem;font-weight:700;margin-left:4px;display:none}
.bm-count.show{display:inline}

/* TOAST */
#toast-wrap{position:fixed;top:14px;right:14px;z-index:9999;display:flex;flex-direction:column;gap:6px;pointer-events:none}
.toast{background:var(--surf2);border:1px solid var(--bdr2);border-radius:10px;padding:9px 14px;font-size:0.8rem;color:var(--text);min-width:180px;pointer-events:all;animation:slideIn .3s ease;transition:opacity .3s}
@keyframes slideIn{from{transform:translateX(110%);opacity:0}to{transform:none;opacity:1}}
.toast.success{border-color:#22c55e55;color:#bbf7d0}
.toast.error{border-color:#f8717155;color:#fca5a5}
.toast.info{border-color:#60a5fa55;color:#bfdbfe}

/* MODAL base */
.modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:500;align-items:center;justify-content:center;padding:16px}
.modal-bg.open{display:flex}
.modal-box{background:var(--surf);border:1px solid var(--bdr2);border-radius:14px;padding:20px;width:100%;max-width:440px;max-height:90vh;overflow-y:auto}
.modal-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.modal-head h3{font-size:1rem;font-weight:600}
.modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:1.2rem;line-height:1}

/* IMAGE MODAL */
#img-modal canvas{border-radius:10px;width:100%;margin-bottom:12px}
.img-templates{display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap}
.tpl-btn{padding:5px 12px;border-radius:999px;border:1px solid var(--bdr2);background:transparent;color:var(--text);font-size:0.75rem;cursor:pointer;transition:.2s}
.tpl-btn.active,.tpl-btn:hover{background:var(--green);border-color:var(--green);color:#020617}
.modal-actions{display:flex;gap:8px}
.btn-dl{background:var(--green);color:#020617;border:none;border-radius:999px;padding:8px 18px;font-size:0.85rem;font-weight:600;cursor:pointer}
.btn-dl:hover{background:var(--gd)}
.btn-outline{background:transparent;border:1px solid var(--bdr2);border-radius:999px;color:var(--text);padding:8px 14px;font-size:0.85rem;cursor:pointer}
.btn-outline:hover{border-color:var(--green);color:var(--green)}

/* BOOKMARKS MODAL */
#bm-modal .bm-list{display:flex;flex-direction:column;gap:10px;margin-top:8px}
.bm-empty{text-align:center;padding:30px;color:var(--muted);font-size:0.9rem}

/* CONTAINER */
.wrap{max-width:760px;margin:0 auto;padding:14px}

/* HERO */
header{text-align:center;padding:18px 0 8px}
header h1{font-size:1.6rem;font-weight:700;margin-bottom:4px}
header h1 span{color:var(--green)}
header p{font-size:0.87rem;color:var(--muted)}

/* SOTD */
.sotd-wrap{margin:14px 0;padding:16px;border-radius:var(--radius);background:linear-gradient(135deg,#0d1117,#1a1f2e);border:1px solid #22c55e22;position:relative;overflow:hidden}
.sotd-wrap::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--green),transparent)}
.sotd-label{font-size:0.7rem;color:var(--green);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px}
.sotd-text{font-size:1rem;line-height:1.7;white-space:pre-line;color:var(--text);margin-bottom:8px}
.sotd-meta{font-size:0.75rem;color:var(--muted);display:flex;justify-content:space-between;align-items:center}
.sotd-actions{display:flex;gap:6px}

/* RANDOM BTN */
.random-wrap{text-align:center;margin:8px 0}
.btn-random{background:linear-gradient(135deg,#1a1f2e,#111827);border:1px solid var(--bdr2);border-radius:999px;color:var(--text);padding:7px 18px;font-size:0.82rem;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;gap:6px}
.btn-random:hover{border-color:var(--purple);color:var(--purple)}

/* SEARCH */
.search-wrap{margin:12px 0;position:relative}
.search-wrap input{width:100%;padding:9px 14px 9px 38px;border-radius:999px;border:1px solid var(--bdr2);background:var(--surf2);color:var(--text);font-size:0.87rem;outline:none;transition:.2s}
.search-wrap input:focus{border-color:var(--green)}
.si{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none}

/* SECTION LABEL */
.sec-label{font-size:0.68rem;color:var(--muted);font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin:12px 0 5px}

/* CATEGORIES */
.cats{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:4px}
.cat-btn{padding:5px 12px;border-radius:999px;border:1px solid var(--bdr2);background:transparent;color:var(--text);font-size:0.8rem;cursor:pointer;transition:.2s;text-decoration:none;display:inline-block}
.cat-btn:hover,.cat-btn.active{background:var(--green);border-color:var(--green);color:#020617}
.cat-btn.zindagi-btn:hover,.cat-btn.zindagi-btn.active{background:#10b981;border-color:#10b981;color:#fff}
.cat-btn.poet:hover,.cat-btn.poet.active{background:var(--purple);border-color:var(--purple);color:#fff}

/* POET BANNER */
.poet-banner{display:flex;align-items:center;gap:12px;background:linear-gradient(135deg,#111827,#1e1b4b);border:1px solid #818cf833;border-radius:10px;padding:12px 16px;margin:8px 0}
.poet-av{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:#fff;flex-shrink:0}
.poet-name{font-size:0.97rem;font-weight:700;color:#e0e7ff}
.poet-era{font-size:0.76rem;color:#a5b4fc;margin-top:2px}

/* GENERATOR */
.gen-box{margin:14px 0;padding:14px;border-radius:var(--radius);border:1px dashed var(--bdr2);background:var(--surf)}
.gen-title{font-size:0.97rem;font-weight:600;margin-bottom:3px}
.gen-sub{font-size:0.78rem;color:var(--muted);margin-bottom:10px}
.gen-row{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:8px}
.gen-row input,.gen-row select{flex:1 1 130px;padding:7px 11px;border-radius:8px;border:1px solid var(--bdr2);background:var(--bg);color:var(--text);font-size:0.83rem;outline:none}
.gen-row input:focus,.gen-row select:focus{border-color:var(--green)}
.tone-row{display:flex;align-items:center;gap:8px;margin-bottom:9px;flex-wrap:wrap}
.tone-row label{font-size:0.77rem;color:var(--muted)}
.tone-row input[type=range]{flex:1;min-width:100px;accent-color:var(--green)}
.tone-val{font-size:0.77rem;color:var(--green);min-width:22px}
.gen-btns{display:flex;gap:7px;flex-wrap:wrap;align-items:center}
.btn-gen{padding:7px 16px;border-radius:999px;border:none;background:var(--green);color:#020617;font-size:0.83rem;font-weight:600;cursor:pointer;transition:.2s}
.btn-gen:hover{background:var(--gd)}
.btn-gen:disabled{opacity:.5;cursor:not-allowed}
.btn-var{padding:7px 14px;border-radius:999px;border:1px solid var(--bdr2);background:transparent;color:var(--text);font-size:0.83rem;cursor:pointer;transition:.2s}
.btn-var:hover{border-color:var(--green);color:var(--green)}
.rate-note{font-size:0.71rem;color:var(--hint)}

/* SKELETON */
@keyframes shimmer{0%{background-position:-500px 0}100%{background-position:500px 0}}
.skel{background:linear-gradient(90deg,var(--surf2) 25%,var(--surf) 50%,var(--surf2) 75%);background-size:500px 100%;animation:shimmer 1.3s infinite;border-radius:5px}
.skel-card{background:var(--surf2);border-radius:var(--radius);padding:14px;border:1px solid var(--bdr);margin-bottom:10px}
.skel-line{height:13px;margin-bottom:7px}
.skel-line.s{width:35%}.skel-line.m{width:65%}.skel-line.f{width:100%}

/* CARDS */
.list{margin-top:10px}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.card{background:linear-gradient(135deg,var(--surf),var(--surf2));border-radius:var(--radius);padding:14px;border:1px solid var(--bdr);margin-bottom:10px;animation:fadeUp .35s ease both;transition:border-color .2s}
.card:hover{border-color:var(--bdr2)}
.card.poet-card{border-color:#818cf822;background:linear-gradient(135deg,#0d0e1f,#111827)}
.card.poet-card:hover{border-color:#818cf855}
.card.zindagi-card{border-color:#10b98122;background:linear-gradient(135deg,#021b0f,#111827)}
.card.zindagi-card:hover{border-color:#10b98155}
.card-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px}
.cat-label{font-size:0.7rem;color:var(--muted)}
.badges{display:flex;gap:5px;flex-wrap:wrap}
.bdg{font-size:0.67rem;padding:2px 7px;border-radius:999px}
.bdg.featured{background:var(--gdim);color:#bbf7d0;border:1px solid #22c55e33}
.bdg.new{background:#6366f11a;color:#c7d2fe;border:1px solid #6366f133}
.bdg.classic{background:#818cf81a;color:#c7d2fe;border:1px solid #818cf833}
.bdg.zindagi{background:#10b9811a;color:#a7f3d0;border:1px solid #10b98133}
.shayari-text{white-space:pre-line;font-size:0.96rem;line-height:1.68;margin-bottom:8px}
.poet-credit{font-size:0.8rem;color:#a5b4fc;font-style:italic;margin:4px 0 8px}
.poet-credit .era{color:#6366f188;font-style:normal;font-size:0.72rem}
.card-foot{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px}
.card-meta{font-size:0.7rem;color:var(--hint)}
.actions{display:flex;gap:5px;flex-wrap:wrap}
.btn{border:1px solid var(--bdr2);border-radius:999px;padding:4px 9px;font-size:0.72rem;cursor:pointer;background:var(--surf2);color:var(--text);display:inline-flex;align-items:center;gap:3px;transition:.2s}
.btn:hover{background:var(--green);color:#020617;border-color:var(--green)}
.btn.liked{background:#f871711a;color:var(--red);border-color:#f8717133}
.btn.liked:hover{background:var(--red);color:#fff;border-color:var(--red)}
.btn.saved{background:#eab3081a;color:var(--gold);border-color:#eab30833}
.btn.saved:hover{background:var(--gold);color:#020617;border-color:var(--gold)}
.lc{font-weight:600}

/* TTS */
.btn.speaking{background:#6366f11a;color:var(--purple);border-color:#6366f155}

/* PAGINATION */
.pager{display:flex;gap:5px;justify-content:center;margin:18px 0;flex-wrap:wrap}
.pager a{padding:5px 13px;border-radius:999px;border:1px solid var(--bdr2);color:var(--text);font-size:0.8rem;transition:.2s}
.pager a.active,.pager a:hover{background:var(--green);border-color:var(--green);color:#020617}
.pager span{padding:5px 13px;color:var(--hint);font-size:0.8rem}

/* NO RESULTS */
.no-res{text-align:center;padding:36px;color:var(--muted)}

footer{text-align:center;margin:18px 0 10px;font-size:0.7rem;color:var(--hint)}
@media(max-width:560px){header h1{font-size:1.25rem}.modal-box{padding:14px}}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-brand">Shayari AI</div>
  <div class="nav-right">
    <button class="nav-bm-btn" id="nav-bm-btn" onclick="openBmModal()">
      🔖 Saved <span class="bm-count" id="bm-count"><?= count($bookmarks) ?></span>
    </button>
    <a href="admin.php">Admin</a>
  </div>
</nav>

<!-- TOAST -->
<div id="toast-wrap"></div>

<!-- IMAGE MODAL -->
<div class="modal-bg" id="img-modal">
  <div class="modal-box">
    <div class="modal-head">
      <h3>🖼️ Image banao</h3>
      <button class="modal-close" onclick="closeModal('img-modal')">✕</button>
    </div>
    <div class="img-templates">
      <button class="tpl-btn active" data-tpl="dark" onclick="setTpl(this)">Dark</button>
      <button class="tpl-btn" data-tpl="purple" onclick="setTpl(this)">Purple</button>
      <button class="tpl-btn" data-tpl="green" onclick="setTpl(this)">Green</button>
      <button class="tpl-btn" data-tpl="paper" onclick="setTpl(this)">Paper</button>
      <button class="tpl-btn" data-tpl="sunset" onclick="setTpl(this)">Sunset</button>
    </div>
    <canvas id="img-canvas" width="400" height="440"></canvas>
    <div class="modal-actions">
      <button class="btn-dl" id="btn-dl">Download PNG</button>
      <button class="btn-outline" onclick="closeModal('img-modal')">Close</button>
    </div>
  </div>
</div>

<!-- BOOKMARKS MODAL -->
<div class="modal-bg" id="bm-modal">
  <div class="modal-box">
    <div class="modal-head">
      <h3>🔖 Saved Shayaris</h3>
      <button class="modal-close" onclick="closeModal('bm-modal')">✕</button>
    </div>
    <div class="bm-list" id="bm-list"><div class="bm-empty">Loading...</div></div>
  </div>
</div>

<!-- RANDOM MODAL -->
<div class="modal-bg" id="rand-modal">
  <div class="modal-box">
    <div class="modal-head">
      <h3>🎲 Random Shayari</h3>
      <button class="modal-close" onclick="closeModal('rand-modal')">✕</button>
    </div>
    <div id="rand-content" style="font-size:.95rem;line-height:1.7;white-space:pre-line;padding:8px 0">Loading...</div>
    <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap">
      <button class="btn-dl" onclick="fetchRandom()">Another one 🎲</button>
      <button class="btn-outline" onclick="closeModal('rand-modal')">Close</button>
    </div>
  </div>
</div>

<div class="wrap">
  <header>
    <h1>AI <span>Shayari</span> Generator</h1>
    <p>Attitude · Dosti · Dard · Zindagi · Motivation — aur Mashoor Shayar</p>
  </header>

  <!-- SHAYARI OF THE DAY -->
  <div class="sotd-wrap" id="sotd-wrap">
    <div class="sotd-label">✨ Aaj ki shayari</div>
    <div class="sotd-text" id="sotd-text">Loading...</div>
    <div class="sotd-meta">
      <span id="sotd-meta"></span>
      <div class="sotd-actions" id="sotd-actions"></div>
    </div>
  </div>

  <!-- RANDOM BUTTON -->
  <div class="random-wrap">
    <button class="btn-random" onclick="openRandom()">🎲 Surprise me — Random shayari</button>
  </div>

  <!-- SEARCH -->
  <div class="search-wrap">
    <span class="si">🔍</span>
    <input type="text" id="search-inp" placeholder="Shayari search karo — topic, tags ya shayar ka naam..." autocomplete="off">
  </div>

  <!-- AI CATEGORIES -->
  <div class="sec-label">AI Shayari Categories</div>
  <div class="cats">
    <?php foreach ($aiCategories as $k):
      $c = $categories[$k];
      $isZ = $k === 'ZINDAGI';
    ?>
    <a href="?cat=<?= $k ?>" class="cat-btn <?= $isZ ? 'zindagi-btn' : '' ?> <?= $currentCat===$k ? 'active' : '' ?>">
      <?= $c['emoji'] ?> <?= htmlspecialchars($c['label']) ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- POET CATEGORIES -->
  <div class="sec-label" style="margin-top:10px">Mashoor Shayar</div>
  <?php if (in_array($currentCat, $famousPoets) && isset($poetInfo[$currentCat])): $pi = $poetInfo[$currentCat]; ?>
  <div class="poet-banner">
    <div class="poet-av"><?= mb_substr($pi['name'],0,1,'UTF-8') ?></div>
    <div>
      <div class="poet-name"><?= htmlspecialchars($pi['name']) ?></div>
      <div class="poet-era"><?= htmlspecialchars($pi['era']) ?> · <?= htmlspecialchars($pi['tag']) ?></div>
    </div>
  </div>
  <?php endif; ?>
  <div class="cats">
    <?php foreach ($famousPoets as $k):
      $c = $categories[$k];
    ?>
    <a href="?cat=<?= $k ?>" class="cat-btn poet <?= $currentCat===$k ? 'active' : '' ?>">
      <?= $c['emoji'] ?> <?= htmlspecialchars($c['label']) ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- GENERATOR -->
  <div class="gen-box">
    <div class="gen-title">AI se nayi shayari banvao (Groq AI — Ultra fast!)</div>
    <div class="gen-sub">Topic aur mood choose karo — Devanagari mein fresh shayari milegi</div>
    <div class="gen-row">
      <input type="text" id="topic" placeholder="Topic — pyaar, dosti, zindagi, dard...">
      <select id="mood">
        <option value="ATTITUDE">Attitude 🔥</option>
        <option value="MOTIVATION">Motivation ⚡</option>
        <option value="DOSTI_COLLEGE">Dosti / College 🧡</option>
        <option value="DARD">Dard / Breakup 💔</option>
        <option value="SELF">Self-Love 💫</option>
        <option value="ZINDAGI">Zindagi 🌿</option>
      </select>
    </div>
    <div class="tone-row">
      <label>Soft</label>
      <input type="range" id="tone" min="0" max="100" value="50">
      <span class="tone-val" id="tone-val">50</span>
      <label>Aggressive</label>
    </div>
    <div class="gen-btns">
      <button class="btn-gen" id="btn-gen">⚡ AI Generate</button>
      <button class="btn-var" id="btn-var">3 Variations</button>
      <span class="rate-note">Max 10 AI calls/hour</span>
    </div>
  </div>

  <!-- SHAYARI LIST -->
  <div class="list" id="shayari-list">
    <?php
    $poetEmojis = ['GHALIB'=>'🖋️','JOHN_ELIA'=>'🌑','FAIZ'=>'✊','RAHAT'=>'🎙️','BASHIR_BADR'=>'🌹','GULZAR'=>'🎬','IQBAL'=>'🦅','SAHIR'=>'🎵'];
    while ($row = $result->fetch_assoc()):
      $cat  = $row['category'];
      $isP  = in_array($cat, $famousPoets);
      $isZ  = $cat === 'ZINDAGI';
      $cd   = $categories[$cat] ?? ['label'=>$cat,'emoji'=>''];
      $clbl = $cd['emoji'].' '.$cd['label'];
      $liked= isset($_SESSION['liked_'.$row['id']]);
      $bmd  = in_array($row['id'], $bookmarks);
      $pn   = $row['poet'] ?? '';
      $pe   = $row['poet_era'] ?? '';
      $waText = urlencode($row['text'] . ($pn ? "\n\n— $pn" : '') . "\n\n(AI Shayari)");
      $twText = urlencode($row['text'] . ($pn ? " — $pn" : ''));
    ?>
    <div class="card <?= $isP?'poet-card':($isZ?'zindagi-card':'') ?>" id="card-<?= $row['id'] ?>">
      <div class="card-top">
        <div class="cat-label"><?= htmlspecialchars($clbl, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="badges">
          <?php if($row['is_featured']): ?><span class="bdg featured">Featured</span><?php endif; ?>
          <?php if($isP): ?><span class="bdg classic">Classic</span><?php endif; ?>
          <?php if($isZ): ?><span class="bdg zindagi">Zindagi</span><?php endif; ?>
        </div>
      </div>
      <div class="shayari-text"><?= nl2br(htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8')) ?></div>
      <?php if($pn): ?>
      <div class="poet-credit">— <?= htmlspecialchars($pn) ?> <?php if($pe): ?><span class="era">(<?= htmlspecialchars($pe) ?>)</span><?php endif; ?></div>
      <?php endif; ?>
      <div class="card-foot">
        <div class="card-meta">#<?= $row['id'] ?> · <?= htmlspecialchars($cat) ?></div>
        <div class="actions">
          <button class="btn like-btn <?= $liked?'liked':'' ?>" data-id="<?= $row['id'] ?>">
            <?= $liked?'❤️':'🤍' ?> <span class="lc"><?= $row['likes'] ?></span>
          </button>
          <button class="btn bm-btn <?= $bmd?'saved':'' ?>" data-id="<?= $row['id'] ?>"><?= $bmd?'🔖':'🏷️' ?></button>
          <button class="btn tts-btn" data-text="<?= htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') ?>">🔊</button>
          <button class="btn copy-btn" data-text="<?= htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') ?>">📋</button>
          <button class="btn img-btn" data-text="<?= htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') ?>" data-cat="<?= htmlspecialchars($clbl) ?>" data-poet="<?= htmlspecialchars($pn) ?>">🖼️</button>
          <a class="btn" href="https://api.whatsapp.com/send?text=<?= $waText ?>" target="_blank" rel="noopener">🟢</a>
          <a class="btn" href="https://twitter.com/intent/tweet?text=<?= $twText ?>" target="_blank" rel="noopener">🐦</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
    <?php if($total === 0): ?><div class="no-res">Koi shayari nahi mili. AI se nayi banvao!</div><?php endif; ?>
  </div>

  <!-- PAGINATION -->
  <?php if($totalPages > 1): ?>
  <div class="pager">
    <?php if($page>1): ?><a href="?cat=<?= $currentCat ?>&page=<?= $page-1 ?>">« Pehle</a><?php endif; ?>
    <?php for($i=max(1,$page-2);$i<=min($totalPages,$page+2);$i++): ?>
    <a href="?cat=<?= $currentCat ?>&page=<?= $i ?>" class="<?= $i===$page?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if($page<$totalPages): ?><a href="?cat=<?= $currentCat ?>&page=<?= $page+1 ?>">Aage »</a><?php endif; ?>
    <span><?= $page ?>/<?= $totalPages ?></span>
  </div>
  <?php endif; ?>

  <footer>© <?= date('Y') ?> AI Shayari Generator · Groq AI + PHP + MySQL</footer>
</div>

<script>
// ── helpers ────────────────────────────────────────────────────────────────
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;')}
function toast(msg,type='success',dur=2800){
  const tc=document.getElementById('toast-wrap');
  const t=document.createElement('div');t.className='toast '+type;t.textContent=msg;tc.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),320)},dur);
}
function openModal(id){document.getElementById(id).classList.add('open')}
function closeModal(id){document.getElementById(id).classList.remove('open')}
document.querySelectorAll('.modal-bg').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open')}));

// ── Bookmark count badge ───────────────────────────────────────────────────
function updateBmBadge(){
  const cnt=document.querySelectorAll('.bm-btn.saved').length;
  const el=document.getElementById('bm-count');
  el.textContent=cnt;el.classList.toggle('show',cnt>0);
}
updateBmBadge();

// ── SOTD ──────────────────────────────────────────────────────────────────
(async()=>{
  try{
    const r=await fetch('sotd.php');const d=await r.json();
    if(!d.success)return;
    const s=d.shayari;
    document.getElementById('sotd-text').textContent=s.text;
    document.getElementById('sotd-meta').textContent='#'+s.id+' · '+s.category+' · '+d.date;
    document.getElementById('sotd-actions').innerHTML=
      `<button class="btn copy-btn" style="font-size:.7rem;padding:3px 8px" data-text="${esc(s.text)}">📋 Copy</button>
       <button class="btn tts-btn" style="font-size:.7rem;padding:3px 8px" data-text="${esc(s.text)}">🔊</button>`;
  }catch(e){document.getElementById('sotd-text').textContent='Aaj ki shayari load nahi hui.';}
})();

// ── Random shayari ────────────────────────────────────────────────────────
let lastRandId=0;
async function fetchRandom(){
  document.getElementById('rand-content').textContent='Loading...';
  try{
    const r=await fetch(`random.php?exclude=${lastRandId}`);
    const d=await r.json();
    if(!d.success){toast('Random shayari nahi mili','error');return;}
    const s=d.shayari;lastRandId=s.id;
    document.getElementById('rand-content').innerHTML=
      `<div style="font-size:.72rem;color:var(--muted);margin-bottom:6px">${esc(s.category)}</div>`+
      esc(s.text).replace(/\n/g,'<br>')+
      (s.poet?`<div style="font-size:.8rem;color:#a5b4fc;font-style:italic;margin-top:8px">— ${esc(s.poet)}</div>`:'');
  }catch(e){toast('Network error','error');}
}
function openRandom(){openModal('rand-modal');fetchRandom();}

// ── Like ──────────────────────────────────────────────────────────────────
document.addEventListener('click',async e=>{
  const lb=e.target.closest('.like-btn');if(!lb)return;
  try{
    const r=await fetch('like.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id:lb.dataset.id})});
    const d=await r.json();
    if(d.success){
      lb.querySelector('.lc').textContent=d.likes;
      lb.innerHTML=(d.liked?'❤️':'🤍')+' <span class="lc">'+d.likes+'</span>';
      lb.classList.toggle('liked',d.liked);
    }
  }catch(e){}
});

// ── Bookmark ──────────────────────────────────────────────────────────────
document.addEventListener('click',async e=>{
  const bb=e.target.closest('.bm-btn');if(!bb)return;
  try{
    const r=await fetch('bookmark.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id:bb.dataset.id})});
    const d=await r.json();
    if(d.success){
      bb.textContent=d.saved?'🔖':'🏷️';
      bb.classList.toggle('saved',d.saved);
      toast(d.saved?'Shayari saved!':'Removed from saved','info');
      updateBmBadge();
    }
  }catch(e){}
});

// ── Bookmarks Modal ───────────────────────────────────────────────────────
async function openBmModal(){
  openModal('bm-modal');
  document.getElementById('bm-list').innerHTML='<div class="bm-empty">Loading...</div>';
  try{
    const r=await fetch('bookmark.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'list'})});
    const d=await r.json();
    const list=document.getElementById('bm-list');
    if(!d.rows||!d.rows.length){list.innerHTML='<div class="bm-empty">Abhi koi saved shayari nahi hai.<br>🏷️ button press karo kisi bhi shayari pe.</div>';return;}
    list.innerHTML=d.rows.map(s=>
      `<div style="border:1px solid var(--bdr);border-radius:8px;padding:10px">
        <div style="font-size:.7rem;color:var(--muted);margin-bottom:4px">${esc(s.category)}</div>
        <div style="white-space:pre-line;font-size:.9rem;line-height:1.6">${esc(s.text)}</div>
        ${s.poet?`<div style="font-size:.78rem;color:#a5b4fc;font-style:italic;margin-top:4px">— ${esc(s.poet)}</div>`:''}
        <div style="margin-top:8px;display:flex;gap:6px">
          <button class="btn copy-btn" data-text="${esc(s.text)}" style="font-size:.72rem">📋 Copy</button>
          <a class="btn" href="https://api.whatsapp.com/send?text=${encodeURIComponent(s.text)}" target="_blank" style="font-size:.72rem">🟢 WhatsApp</a>
        </div>
      </div>`
    ).join('');
  }catch(e){}
}

// ── Copy ──────────────────────────────────────────────────────────────────
document.addEventListener('click',e=>{
  const cb=e.target.closest('.copy-btn');if(!cb)return;
  navigator.clipboard.writeText(cb.dataset.text).then(()=>{toast('Copy ho gaya!');});
});

// ── TTS (Text to Speech) ──────────────────────────────────────────────────
let currentUtterance=null;
document.addEventListener('click',e=>{
  const tb=e.target.closest('.tts-btn');if(!tb)return;
  if(currentUtterance){speechSynthesis.cancel();currentUtterance=null;document.querySelectorAll('.tts-btn.speaking').forEach(b=>b.classList.remove('speaking'));if(tb.classList.contains('speaking')){return;}}
  const utt=new SpeechSynthesisUtterance(tb.dataset.text);
  utt.lang='hi-IN';utt.rate=0.88;utt.pitch=1.05;
  utt.onend=()=>{tb.classList.remove('speaking');currentUtterance=null;};
  speechSynthesis.speak(utt);currentUtterance=utt;tb.classList.add('speaking');
  toast('Shayari sun rahe ho... 🔊','info');
});

// ── Image canvas ──────────────────────────────────────────────────────────
const tpls={
  dark:  {bg1:'#050816',bg2:'#111827',accent:'#22c55e',text:'#f9fafb',sub:'#9ca3af',bar:'#22c55e'},
  purple:{bg1:'#0d0e1f',bg2:'#1e1b4b',accent:'#818cf8',text:'#e0e7ff',sub:'#a5b4fc',bar:'#6366f1'},
  green: {bg1:'#021b0f',bg2:'#052e16',accent:'#10b981',text:'#d1fae5',sub:'#6ee7b7',bar:'#10b981'},
  paper: {bg1:'#fdf6e3',bg2:'#f5f0dc',accent:'#92400e',text:'#1c1917',sub:'#57534e',bar:'#d97706'},
  sunset:{bg1:'#1c0a00',bg2:'#431407',accent:'#fb923c',text:'#fed7aa',sub:'#fdba74',bar:'#f97316'},
};
let curTpl='dark',curText='',curCat='',curPoet='';

function drawCanvas(){
  const canvas=document.getElementById('img-canvas');
  const ctx=canvas.getContext('2d');
  const T=tpls[curTpl];
  canvas.width=400;canvas.height=440;

  const g=ctx.createLinearGradient(0,0,400,440);
  g.addColorStop(0,T.bg1);g.addColorStop(1,T.bg2);
  ctx.fillStyle=g;
  if(ctx.roundRect)ctx.roundRect(0,0,400,440,16);else ctx.rect(0,0,400,440);
  ctx.fill();

  ctx.fillStyle=T.bar;ctx.fillRect(40,0,320,3);

  ctx.fillStyle=T.accent;ctx.font='500 12px "Segoe UI",sans-serif';ctx.textAlign='center';
  ctx.fillText(curCat,200,32);

  ctx.strokeStyle=T.accent+'33';ctx.lineWidth=1;
  ctx.beginPath();ctx.moveTo(60,44);ctx.lineTo(340,44);ctx.stroke();

  ctx.fillStyle=T.text;ctx.font='500 15px "Segoe UI",sans-serif';ctx.textAlign='center';
  const lines=curText.replace(/\r/g,'').split('\n').filter(l=>l.trim());
  const sh=lines.length*32;const sy=(curPoet?205:210)-sh/2;
  lines.forEach((l,i)=>ctx.fillText(l,200,sy+i*32));

  if(curPoet){
    ctx.fillStyle=T.accent;ctx.font='italic 13px "Segoe UI",sans-serif';
    ctx.fillText('— '+curPoet,200,sy+lines.length*32+22);
  }

  ctx.fillStyle=T.bg2;ctx.fillRect(0,418,400,22);
  ctx.fillStyle=T.sub;ctx.font='11px "Segoe UI",sans-serif';
  ctx.fillText('AI Shayari Generator',200,432);
}

function setTpl(btn){
  document.querySelectorAll('.tpl-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');curTpl=btn.dataset.tpl;drawCanvas();
}

document.addEventListener('click',e=>{
  const ib=e.target.closest('.img-btn');if(!ib)return;
  curText=ib.dataset.text||'';curCat=ib.dataset.cat||'';curPoet=ib.dataset.poet||'';
  drawCanvas();openModal('img-modal');
});
document.getElementById('btn-dl').onclick=()=>{
  const a=document.createElement('a');a.download='shayari.png';
  a.href=document.getElementById('img-canvas').toDataURL('image/png');a.click();
  toast('Image download ho gayi!');closeModal('img-modal');
};

// ── Tone slider ───────────────────────────────────────────────────────────
document.getElementById('tone').addEventListener('input',function(){document.getElementById('tone-val').textContent=this.value;});

// ── Category labels for JS ────────────────────────────────────────────────
const catMap={
  'ATTITUDE':'Attitude 🔥','MOTIVATION':'Motivation ⚡','DOSTI_COLLEGE':'Dosti / College 🧡',
  'DARD':'Dard / Breakup 💔','SELF':'Self-Love 💫','ZINDAGI':'Zindagi 🌿',
  'GHALIB':'🖋️ Mirza Ghalib','JOHN_ELIA':'🌑 John Elia','FAIZ':'✊ Faiz Ahmed Faiz',
  'RAHAT':'🎙️ Rahat Indori','BASHIR_BADR':'🌹 Bashir Badr','GULZAR':'🎬 Gulzar',
  'IQBAL':'🦅 Allama Iqbal','SAHIR':'🎵 Sahir Ludhianvi'
};

function buildCard(d){
  const cl=catMap[d.category]||d.category;
  const wa=encodeURIComponent(d.text+'\n\n— AI Shayari Generator');
  const tw=encodeURIComponent(d.text);
  const isZ=d.category==='ZINDAGI';
  const extraClass=isZ?'zindagi-card':'';
  return `<div class="card ${extraClass}" id="card-${d.id}">
    <div class="card-top">
      <div class="cat-label">${esc(cl)}</div>
      <div class="badges"><span class="bdg new">New ✨</span>${isZ?'<span class="bdg zindagi">Zindagi</span>':''}</div>
    </div>
    <div class="shayari-text">${esc(d.text).replace(/\n/g,'<br>')}</div>
    <div class="card-foot">
      <div class="card-meta">#${d.id} · ${esc(d.category)}</div>
      <div class="actions">
        <button class="btn like-btn" data-id="${d.id}">🤍 <span class="lc">0</span></button>
        <button class="btn bm-btn" data-id="${d.id}">🏷️</button>
        <button class="btn tts-btn" data-text="${esc(d.text)}">🔊</button>
        <button class="btn copy-btn" data-text="${esc(d.text)}">📋</button>
        <button class="btn img-btn" data-text="${esc(d.text)}" data-cat="${esc(cl)}" data-poet="">🖼️</button>
        <a class="btn" href="https://api.whatsapp.com/send?text=${wa}" target="_blank">🟢</a>
        <a class="btn" href="https://twitter.com/intent/tweet?text=${tw}" target="_blank">🐦</a>
      </div>
    </div>
  </div>`;
}

// ── AI Generate ───────────────────────────────────────────────────────────
async function callAI(count){
  const topic=document.getElementById('topic').value.trim();
  const mood=document.getElementById('mood').value;
  const tone=parseInt(document.getElementById('tone').value);
  if(!topic){toast('Pehle topic likho!','error');document.getElementById('topic').focus();return;}

  const bg=document.getElementById('btn-gen'),bv=document.getElementById('btn-var');
  bg.disabled=bv.disabled=true;bg.textContent=count>=3?'Generating 3...':'⚡ Generating...';

  const list=document.getElementById('shayari-list');
  list.insertAdjacentHTML('afterbegin',`<div class="skel-card" id="skel">
    <div class="skel-line s skel"></div><div class="skel-line f skel" style="height:12px;margin-top:6px"></div>
    <div class="skel-line m skel" style="height:12px;margin-top:5px"></div>
    <div class="skel-line f skel" style="height:12px;margin-top:5px"></div></div>`);

  try{
    const r=await fetch('generate.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({topic,mood,tone,count})});
    const d=await r.json();
    document.getElementById('skel')?.remove();
    if(d.rate_limited){toast(d.error,'error',5000);return;}
    if(!d.success){toast(d.error||'Kuch galat hua','error');return;}
    if(d.variations){d.variations.reverse().forEach(v=>list.insertAdjacentHTML('afterbegin',buildCard(v)));toast(d.variations.length+' variations aa gayi!');}
    else{list.insertAdjacentHTML('afterbegin',buildCard(d));toast('Nayi shayari aa gayi! ✨');}
    document.getElementById('topic').value='';
  }catch(e){document.getElementById('skel')?.remove();toast('Network error','error');}
  finally{bg.disabled=bv.disabled=false;bg.textContent='⚡ AI Generate';}
}

document.getElementById('btn-gen').onclick=()=>callAI(1);
document.getElementById('btn-var').onclick=()=>callAI(3);

// ── Live Search ───────────────────────────────────────────────────────────
let searchT;
document.getElementById('search-inp').addEventListener('input',function(){
  clearTimeout(searchT);const q=this.value.trim();
  if(q.length<2){if(q.length===0)location.href='?cat=<?= $currentCat ?>';return;}
  searchT=setTimeout(async()=>{
    try{
      const r=await fetch(`search.php?q=${encodeURIComponent(q)}`);
      const d=await r.json();
      const list=document.getElementById('shayari-list');
      if(!d.rows||!d.rows.length){list.innerHTML='<div class="no-res">Koi match nahi mila</div>';return;}
      list.innerHTML=d.rows.map(s=>{
        const cl=catMap[s.category]||s.category;
        const wa=encodeURIComponent(s.text+(s.poet?'\n\n— '+s.poet:'')+'\n\n(AI Shayari)');
        const isZ=s.category==='ZINDAGI';
        const isP=['GHALIB','JOHN_ELIA','FAIZ','RAHAT','BASHIR_BADR','GULZAR','IQBAL','SAHIR'].includes(s.category);
        return `<div class="card ${isP?'poet-card':isZ?'zindagi-card':''}" id="card-${s.id}">
          <div class="card-top"><div class="cat-label">${esc(cl)}</div><div class="badges">${s.is_featured?'<span class="bdg featured">Featured</span>':''}</div></div>
          <div class="shayari-text">${esc(s.text).replace(/\n/g,'<br>')}</div>
          ${s.poet?`<div class="poet-credit">— ${esc(s.poet)}</div>`:''}
          <div class="card-foot">
            <div class="card-meta">#${s.id} · ${esc(s.category)}</div>
            <div class="actions">
              <button class="btn like-btn ${s.liked?'liked':''}" data-id="${s.id}">${s.liked?'❤️':'🤍'} <span class="lc">${s.likes}</span></button>
              <button class="btn bm-btn" data-id="${s.id}">🏷️</button>
              <button class="btn tts-btn" data-text="${esc(s.text)}">🔊</button>
              <button class="btn copy-btn" data-text="${esc(s.text)}">📋</button>
              <button class="btn img-btn" data-text="${esc(s.text)}" data-cat="${esc(cl)}" data-poet="${esc(s.poet||'')}">🖼️</button>
              <a class="btn" href="https://api.whatsapp.com/send?text=${wa}" target="_blank">🟢</a>
            </div>
          </div>
        </div>`;
      }).join('');
    }catch(e){toast('Search error','error');}
  },380);
});
</script>
</body>
</html>

admin passworld admin@shayari123