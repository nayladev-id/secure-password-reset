<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=db_auth_otp", "root", "");

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$new_password = $data['password'] ?? '';

if ($email && $new_password) {
    // Kriptografi: Mengacak sandi baru sebelum disimpan (Bcrypt)
    $pass_hash = password_hash($new_password, PASSWORD_BCRYPT);
    
    $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    if ($update->execute([$pass_hash, $email])) {
        echo json_encode(["status" => "success", "pesan" => "Sandi berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => "error", "pesan" => "Gagal memperbarui sandi."]);
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "Data tidak lengkap."]);
}
?>