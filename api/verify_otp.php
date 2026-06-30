<?php
date_default_timezone_set('Asia/Jakarta');
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=db_auth_otp", "root", "");

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$input_otp = $data['otp'] ?? '';

if ($email && $input_otp) {
    $stmt = $pdo->prepare("SELECT otp_hash, otp_expired_at FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && $user['otp_hash']) {
        if (strtotime($user['otp_expired_at']) < time()) {
            echo json_encode(["status" => "error", "pesan" => "OTP kedaluwarsa."]);
            exit;
        }
        
        if (password_verify($input_otp, $user['otp_hash'])) {
            $hapus = $pdo->prepare("UPDATE users SET otp_hash = NULL, otp_expired_at = NULL WHERE email = ?");
            $hapus->execute([$email]);
            echo json_encode(["status" => "success", "pesan" => "Verifikasi Berhasil! Akses dibuka."]);
        } else {
            echo json_encode(["status" => "error", "pesan" => "Kode OTP Salah."]);
        }
    } else {
         echo json_encode(["status" => "error", "pesan" => "Sesi tidak valid."]);
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "Data tidak lengkap."]);
}
?>