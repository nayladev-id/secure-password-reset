<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=db_auth_otp", "root", "");

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if ($email && $password) {
    // Cek apakah email sudah ada
    $cek = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $cek->execute([$email]);
    
    if ($cek->fetch()) {
        echo json_encode(["status" => "error", "pesan" => "Email sudah terdaftar!"]);
    } else {
        // Hash password utama (bukan OTP)
        $pass_hash = password_hash($password, PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $insert->execute([$email, $pass_hash]);
        
        echo json_encode(["status" => "success", "pesan" => "Akun berhasil didaftarkan!"]);
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "Email dan sandi wajib diisi."]);
}
?>