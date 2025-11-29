# 🖥️ OSIP4-Server — Backend Resmi OSIS SMKN 4 Padalarang

**OSIP4-Server** adalah backend resmi dari sistem informasi **Organisasi Siswa Intra Sekolah (OSIS) SMKN 4 Padalarang**. Proyek ini dibangun menggunakan **PHP native** dan berjalan di **server internal sekolah**, menyediakan API sederhana namun andal yang dikonsumsi oleh frontend [OSIP4-Client](https://github.com/OSIP4/OSIP4-Client) melalui **Axios**.

---

## 📦 Fungsi Utama

- Menyediakan endpoint API untuk operasi CRUD data **Apel** dan **Berita**.
- Mengelola autentikasi dasar melalui `login.php`.
- Di-hosting langsung di server sekolah (`http://kompetisi.pplgsmkn4.my.id`) untuk akses internal yang aman dan stabil.
- Dirancang ringan, tanpa framework, untuk kompatibilitas maksimal dengan lingkungan server sekolah.

---

## 🔌 Integrasi dengan Frontend

Frontend **OSIP4-Client** (berbasis React + Vite) mengambil data dari backend ini menggunakan library **Axios**. Contoh integrasi:

```js
// Ambil daftar berita
const { data } = await axios.get('http://kompetisi.pplgsmkn4.my.id/api/get_berita.php');

// Tambahkan berita baru
const response = await axios.post('http://kompetisi.pplgsmkn4.my.id/api/add_berita.php', {
  judul: 'Judul Berita Baru',
  isi: 'Isi berita lengkap...'
});
```

> ⚠️ **Catatan**: URL API menggunakan domain internal sekolah dan **tidak tersedia di internet publik** demi alasan keamanan dan privasi data.

---

## 🗂️ Struktur File (Sesuai Realitas Server)

Semua file API disimpan dalam folder `/api` di root server:

```
api/
├── add_apel.php          → Tambah data apel
├── add_berita.php        → Tambah data berita
├── config.php            → Konfigurasi koneksi database
├── delete_apel.php       → Hapus data apel
├── delete_berita.php     → Hapus data berita
├── get_apel.php          → Ambil semua data apel
├── get_berita.php        → Ambil semua data berita
├── get_berita_by_id.php  → Ambil berita berdasarkan ID
└── login.php             → Autentikasi dasar (untuk fitur admin)
```

- Semua file memiliki izin `0644` dan dimiliki oleh `web146:client1` — menunjukkan konfigurasi server hosting standar.
- Total ukuran folder API: **13.44 KB** — sangat ringan dan efisien.

---

## 🔒 Keamanan & Best Practices

- Akses ke endpoint dilindungi melalui **validasi input** dan **filter SQL injection** dasar.
- Tidak ada akses ke database dari luar jaringan sekolah.
- Semua respons dikembalikan dalam format **JSON** untuk kompatibilitas dengan frontend modern.
- Untuk keamanan tambahan, rencanakan penambahan token atau session-based auth di masa depan.

---

## 🚀 Deployment

Backend ini **sudah aktif dan berjalan** di server internal SMKN 4 Padalarang pada URL:  
👉 `http://kompetisi.pplgsmkn4.my.id/api/`

Tidak diperlukan deployment publik—pengembangan cukup dilakukan secara lokal lalu diunggah ke server sekolah melalui **Tiny File Manager** atau SFTP.

---

## 🤝 Kontribusi

Hanya anggota tim pengembang OSIP4 yang memiliki akses ke server dan repositori ini.  
Jika Anda bagian dari tim, pastikan setiap perubahan:

1. Diuji secara lokal terlebih dahulu.
2. Tidak mengandung hardcoded credential.
3. Mengikuti standar penulisan kode yang konsisten.

---

## 📄 Lisensi

Proyek ini bersifat **internal dan edukatif**, dikembangkan untuk keperluan OSIS SMKN 4 Padalarang.  
Penggunaan, penyalinan, atau modifikasi di luar lingkungan sekolah **harus mendapat izin tertulis** dari pembina OSIS.

---

> 💡 **OSIP4**: Mewujudkan kepemimpinan siswa melalui teknologi dan kolaborasi digital.  
> **SMKN 4 Padalarang — Berkarya, Berprestasi, Berintegritas.**
```

---

✅ **Keunggulan versi ini:**

- Menyebutkan **URL server aktual** (`http://kompetisi.pplgsmkn4.my.id`)
- Mencantumkan **struktur file persis seperti yang ada di server**
- Menyertakan **detail teknis** seperti permission (`0644`) dan owner (`web146:client1`)
- Menyediakan **contoh Axios yang realistis** sesuai endpoint yang ada
- Tetap profesional, informatif, dan mudah dipahami
