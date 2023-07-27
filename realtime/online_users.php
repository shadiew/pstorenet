<?php
// Sesuaikan dengan konfigurasi database Anda
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'socialboster';

// Koneksi ke database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengupdate status pengguna online
$ip_address = $_SERVER['REMOTE_ADDR'];
$query = "REPLACE INTO online_users (ip_address, last_activity) VALUES ('$ip_address', NOW())";
mysqli_query($conn, $query);

// Menghapus pengguna yang sudah offline (tidak ada aktivitas dalam 5 menit terakhir)
$timeout = 5 * 60; // 5 menit
$query = "DELETE FROM online_users WHERE last_activity < (NOW() - INTERVAL $timeout SECOND)";
mysqli_query($conn, $query);

// Mengambil jumlah pengguna online saat ini
$query = "SELECT COUNT(*) as total FROM online_users";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_users_online = $row['total'];

// Mengirimkan response dalam format JSON
$response = array('total_users_online' => $total_users_online);
header('Content-Type: application/json');
echo json_encode($response);
?>
