<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Hanya izinkan GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Metode tidak diizinkan']);
    exit;
}

// Ambil ID dari query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID berita tidak valid']);
    exit;
}

// Koneksi database
include "./config.php";

// Query berita berdasarkan id_berita
$stmt = $conn->prepare("
    SELECT 
        id_berita,
        id_user,
        pembuat,
        judul,
        deskripsi,
        isi,
        tanggal_post,
        foto
    FROM berita 
    WHERE id_berita = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $berita = $result->fetch_assoc();
    // Pastikan 'foto' null jika tidak ada
    $berita['foto'] = $berita['foto'] ? $berita['foto'] : null;
    echo json_encode($berita);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Berita tidak ditemukan']);
}

$stmt->close();
$conn->close();
?>