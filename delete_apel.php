<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Izinkan GET atau POST (Anda menggunakan GET di React)
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    exit;
}

include "./config.php";

// Ambil id_jadwal dari query string atau POST
$id = $_GET['id_apel'] ?? $_POST['id_apel'] ?? null;

// Validasi: pastikan id ada dan berupa angka
if (!$id || !is_numeric($id) || (int)$id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID apel tidak valid.']);
    exit;
}

$id = (int)$id;

// Siapkan prepared statement untuk hapus
$stmt = $conn->prepare("DELETE FROM apel WHERE id_apel = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => ' apel berhasil dihapus.'
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'apel tidak ditemukan.'
        ]);
    }
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menghapus jadwal: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>