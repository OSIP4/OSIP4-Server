<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

// Koneksi database
include "./config.php";

// Ambil data dari $_POST
$pembuat    = $_POST['pembuat'] ?? '';
$judul      = $_POST['judul'] ?? '';
$deskripsi  = $_POST['deskripsi'] ?? '';
$isi        = $_POST['isi'] ?? '';
$id_user    = isset($_POST['id_user']) ? (int)$_POST['id_user'] : 0;

// Validasi wajib
if (!$pembuat || !$judul || !$deskripsi || !$isi || !$id_user) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Pembuat, judul, deskripsi, isi, dan id_user wajib diisi'
    ]);
    exit;
}

$foto = null;

// --- UPLOAD FOTO ---
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = mime_content_type($_FILES['foto']['tmp_name']); // lebih aman

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Hanya file gambar (JPG/PNG/WebP) yang diizinkan'
        ]);
        exit;
    }

    // Tentukan folder upload (2 level ke atas dari api/)
    $uploadDir = __DIR__ . "/../../uploads/berita/";

    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal membuat folder upload: permission ditolak'
            ]);
            exit;
        }
    }

    // Generate nama file unik
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid('berita_') . '.' . strtolower($ext);
    $targetPath = $uploadDir . $fileName;

    // Pindahkan file
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
        $foto = "uploads/berita/" . $fileName; // path relatif dari root lomba_api
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menyimpan file ke server'
        ]);
        exit;
    }
}

// --- SIMPAN KE DATABASE ---
$stmt = $conn->prepare("
    INSERT INTO berita (id_user, pembuat, judul, deskripsi, isi, foto)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("isssss", $id_user, $pembuat, $judul, $deskripsi, $isi, $foto);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Berita berhasil ditambahkan',
        'id_berita' => $conn->insert_id,
        'foto' => $foto
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan ke database: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>