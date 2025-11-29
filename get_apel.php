<?php
// Tangani CORS
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Respons sukses untuk preflight CORS (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// Hanya izinkan metode GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Hanya GET yang diizinkan untuk endpoint ini.'
    ]);
    exit;
}

// Koneksi ke database
include "./config.php";

// Ambil data dari tabel apel
$sql = "SELECT id_apel, Tanggal, Kelas FROM apel ORDER BY Tanggal ASC";
$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Pastikan nilai dikonversi ke string (aman untuk JSON & React)
        $data[] = [
            'id_apel' => (string) $row['id_apel'],
            'Tanggal'   => (string) $row['Tanggal'],
            'Kelas'     => (string) $row['Kelas']
        ];
    }
}

// 🔥 PENTING: Kembalikan langsung array, bukan { data: [...] }
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$conn->close();
?>