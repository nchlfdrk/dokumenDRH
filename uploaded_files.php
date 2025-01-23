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

// Mengambil data dari database
$sql = "SELECT file_name, file_url FROM uploaded_files";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menampilkan data
    while($row = $result->fetch_assoc()) {
        echo '<a href="' . $row['file_url'] . '" target="_blank">' . htmlspecialchars($row['file_name']) . '</a><br>';
    }
} else {
    echo "Tidak ada file yang diunggah.";
}

$conn->close();
?>