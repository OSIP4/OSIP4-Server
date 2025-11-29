<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Hanya POST yang diizinkan.']);
    exit;
}

include "./config.php";

$input = json_decode(file_get_contents('php://input'), true);

$tanggal = trim($input['Tanggal'] ?? '');
$kelas = trim($input['Kelas'] ?? '');

if (!$tanggal || !$kelas) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Tanggal dan Kelas wajib diisi.']);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Format tanggal harus YYYY-MM-DD.']);
    exit;
}

$dt = DateTime::createFromFormat('Y-m-d', $tanggal);
if (!$dt || $dt->format('Y-m-d') !== $tanggal) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Tanggal tidak valid.']);
    exit;
}

// 🔥 Hanya simpan Tanggal dan Kelas (tanpa Hari)
$stmt = $conn->prepare("INSERT INTO apel (Tanggal, Kelas) VALUES (?, ?)");
$stmt->bind_param("ss", $tanggal, $kelas);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Jadwal apel berhasil ditambahkan',
        'id_jadwal' => $conn->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan ke database: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>