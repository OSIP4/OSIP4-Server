<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Koneksi database
include "./config.php";

// Query: ambil semua berita, urutkan terbaru
$sql = "SELECT 
            id_berita,
            id_user,
            pembuat,
            judul,
            deskripsi,
            isi,
            tanggal_post,
            foto
        FROM berita 
        ORDER BY tanggal_post DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $beritaList = [];
    while ($row = $result->fetch_assoc()) {
        // Opsional: format tanggal agar lebih mudah di frontend
        // $row['tanggal_post'] = date("d M Y", strtotime($row['tanggal_post']));

        // Jika foto null, ubah jadi null (bukan string "null")
        $row['foto'] = $row['foto'] ? $row['foto'] : null;

        $beritaList[] = $row;
    }
    echo json_encode($beritaList);
} else {
    echo json_encode([]);
}

$conn->close();
?>