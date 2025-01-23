<?php
// Koneksi ke database
$servername = "sql111.infinityfree.com"; 
$username = "if0_37889262"; 
$password = "fNs2ejU14pZ3KD"; 
$dbname = "if0_37889262_simulasiskbcat"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $uploadDir = 'upload/'; // Ganti dengan direktori yang sesuai

        // Membuat direktori jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadFile = $uploadDir . basename($file['name']);

        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf']; // Tipe file yang diizinkan
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Tipe file tidak diizinkan.']);
            exit;
        }

        // Validasi ukuran file
        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($file['size'] > $maxFileSize) {
            echo json_encode(['success' => false, 'message' => 'Ukuran file melebihi batas maksimum (2 MB).']);
            exit;
        }

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Simpan informasi file ke database
            $fileName = $file['name'];
            $fileUrl = $uploadFile; // URL file yang diunggah
            $status = 'sudah diunggah'; // Atur status sesuai kebutuhan

            $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_url, status) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fileName, $fileUrl, $status);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'fileUrl' => $fileUrl]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan informasi file ke database.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengunggah file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File tidak ditemukan.']);
    }
}

$conn->close();
?>