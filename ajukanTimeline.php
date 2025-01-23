<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
$servername = "sql111.infinityfree.com";
$username = "if0_37889262";
$password = "fNs2ejU14pZ3KD";
$dbname = "if0_37889262_simulasiskbcat";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari formulir
$nama = $_POST['nama'];
$instansi = $_POST['instansi'];
$jabatan = $_POST['jabatan'];
$lokasi = $_POST['lokasi'];
$jenis = $_POST['jenis'];
$kode = $_POST['kode'];

// Ambil data dari tabel
$documentItems = json_decode($_POST['documentItems'], true); // Mengambil data JSON dari tabel

// Debugging: lihat data yang diterima
var_dump($_POST); // Cek data yang diterima

$success = true; // Inisialisasi variabel untuk menandakan apakah penyimpanan berhasil

foreach ($documentItems as $item) {
    $document_item = $item['item'];
    $start_date = $item['startDate'];
    $end_date = $item['endDate'];
    $status = $item['status'];

    // Siapkan dan jalankan query
    $stmt = $conn->prepare("INSERT INTO peserta (nama, instansi, jabatan, lokasi, jenis, kode, document_item, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nama, $instansi, $jabatan, $lokasi, $jenis, $kode, $document_item, $start_date, $end_date, $status);
    
    if (!$stmt->execute()) {
        $success = false; // Jika ada kesalahan, set success ke false
        echo "Error: " . $stmt->error; // Tampilkan kesalahan jika ada
    }
}

$stmt->close();
$conn->close();

// Jika berhasil, redirect kembali ke halaman formulir dengan pesan sukses
if ($success) {
    header("Location: index.html?message=success");
    exit();
} else {
    header("Location: index.html?message=failed");
    exit();
}