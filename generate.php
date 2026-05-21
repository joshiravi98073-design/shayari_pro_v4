<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request']); exit;
}

$body  = json_decode(file_get_contents('php://input'), true);
$topic = trim($body['topic']   ?? '');
$mood  = trim($body['mood']    ?? '');
$tone  = intval($body['tone']  ?? 50);
$count = intval($body['count'] ?? 1);

if ($topic === '' || $mood === '') {
    echo json_encode(['error' => 'Topic aur mood dono required hain']); exit;
}

// ── Rate Limiting: 10 calls/hour per IP ───────────────────────────────────
$ip     = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$window = 3600;
$conn->query("DELETE FROM ai_rate_limit WHERE TIMESTAMPDIFF(SECOND, window_start, NOW()) > $window");

$rc = $conn->prepare("SELECT id, hits FROM ai_rate_limit WHERE ip = ?");
$rc->bind_param("s", $ip); $rc->execute();
$rateRow = $rc->get_result()->fetch_assoc();

if ($rateRow) {
    if ($rateRow['hits'] >= 10) {
        echo json_encode(['error' => '1 ghante mein sirf 10 AI calls allowed hain. Thodi der baad try karo!', 'rate_limited' => true]); exit;
    }
    $u = $conn->prepare("UPDATE ai_rate_limit SET hits = hits + 1 WHERE id = ?");
    $u->bind_param("i", $rateRow['id']); $u->execute();
} else {
    $i = $conn->prepare("INSERT INTO ai_rate_limit (ip) VALUES (?)");
    $i->bind_param("s", $ip); $i->execute();
}

// ── Groq API Key ──────────────────────────────────────────────────────────
$config = require __DIR__ . '/.env.php';
$apiKey = $config['GROQ_API_KEY'] ?? '';
if (!$apiKey || $apiKey === 'YOUR_NEW_GROQ_KEY_HERE') {
    echo json_encode(['error' => 'Server pe Groq API key set nahi hai — .env.php check karo']); exit;
}

// ── Tone ──────────────────────────────────────────────────────────────────
$toneDesc = match(true) {
    $tone <= 20  => 'very soft, emotional, gentle, soothing',
    $tone <= 40  => 'mild, thoughtful, introspective',
    $tone <= 60  => 'balanced, Instagram style, relatable',
    $tone <= 80  => 'bold, confident, powerful',
    default      => 'very aggressive, fierce, attitude-heavy',
};

$countWord = $count >= 3 ? 'Generate exactly 3 different shayari variations, separated by ---' : 'Generate 1 shayari';

// ── Prompt ────────────────────────────────────────────────────────────────
$prompt = "$countWord of Hindi shayari in Devanagari script only.
Topic: \"$topic\"
Category: \"$mood\"
Tone: $toneDesc (intensity $tone/100)
Rules:
- ONLY shayari lines — no explanation, no English, no translation.
- 2 to 4 lines per shayari.
- Powerful, Instagram-worthy, emotionally resonant." .
($count >= 3 ? "\n- Separate each variation with exactly: ---" : "");

// ── Groq API Call ─────────────────────────────────────────────────────────
$payload = [
    'model'       => 'llama-3.3-70b-versatile',
    'messages'    => [
        ['role' => 'system', 'content' => 'Tum ek expert Hindi shayar ho. Sirf Devanagari mein shayari likho. Koi explanation, translation ya English nahi.'],
        ['role' => 'user',   'content' => $prompt],
    ],
    'temperature' => 0.92,
    'max_tokens'  => $count >= 3 ? 400 : 130,
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT        => 30,
]);
$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['error' => 'Network error: ' . curl_error($ch)]); curl_close($ch); exit;
}
curl_close($ch);

$res = json_decode($response, true);
if (isset($res['error'])) {
    echo json_encode(['error' => 'Groq API error: ' . $res['error']['message']]); exit;
}
if (!isset($res['choices'][0]['message']['content'])) {
    echo json_encode(['error' => 'Invalid API response']); exit;
}

$raw      = trim($res['choices'][0]['message']['content']);
$category = $mood;
$tags     = $topic;

// ── Save & respond ────────────────────────────────────────────────────────
if ($count >= 3) {
    $parts = array_filter(array_map('trim', explode('---', $raw)));
    $variations = [];
    foreach ($parts as $part) {
        if (empty($part)) continue;
        $s = $conn->prepare("INSERT INTO shayaris (text, category, tags) VALUES (?, ?, ?)");
        $s->bind_param("sss", $part, $category, $tags); $s->execute();
        $variations[] = ['id' => $s->insert_id, 'text' => $part, 'category' => $category];
    }
    echo json_encode(['success' => true, 'variations' => $variations, 'category' => $category], JSON_UNESCAPED_UNICODE);
} else {
    $s = $conn->prepare("INSERT INTO shayaris (text, category, tags) VALUES (?, ?, ?)");
    $s->bind_param("sss", $raw, $category, $tags); $s->execute();
    echo json_encode(['success' => true, 'id' => $s->insert_id, 'text' => $raw, 'category' => $category], JSON_UNESCAPED_UNICODE);
}
