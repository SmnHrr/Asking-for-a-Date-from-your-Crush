<?php
// ─────────────────────────────────────────────────────────
//  save_response.php  —  receives availability answer
//  and saves it to MySQL with a timestamp
// ─────────────────────────────────────────────────────────

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');          // allow same-origin fetch
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once 'db_config.php';

// ── Connect ─────────────────────────────────────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset(DB_CHARSET);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

// ── Read input (supports both POST JSON and POST form-data) ──
$input = json_decode(file_get_contents('php://input'), true);
$answer = trim($input['answer'] ?? $_POST['answer'] ?? '');

if ($answer === '') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Answer is empty']);
    $conn->close();
    exit;
}

// ── Insert ──────────────────────────────────────────────
$stmt = $conn->prepare("INSERT INTO responses (answer, submitted_at) VALUES (?, NOW())");
$stmt->bind_param('s', $answer);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
