<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
    exit;
}

include "./config.php";

// Ambil JSON body
$input = json_decode(file_get_contents("php://input"), true);
$id = intval($input['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID berita tidak valid']);
    exit;
}

// Opsional: hapus file foto
$stmt = $conn->prepare("SELECT foto FROM berita WHERE id_berita = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();
$stmt->close();

if ($berita && $berita['foto']) {
    $fullPath = __DIR__ . "/../../" . $berita['foto'];
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}

// Hapus dari database
$stmt = $conn->prepare("DELETE FROM berita WHERE id_berita = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Berita dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Berita tidak ditemukan']);
}

$stmt->close();
$conn->close();
?>