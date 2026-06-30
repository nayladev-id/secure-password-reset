<?php
date_default_timezone_set('Asia/Jakarta');
header('Content-Type: application/json');

// Memanggil library PHPMailer
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = new PDO("mysql:host=localhost;dbname=db_auth_otp", "root", "");

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if ($email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $otp = random_int(100000, 999999);
        $otp_hash = password_hash($otp, PASSWORD_BCRYPT);
        $expired_at = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        
        $update = $pdo->prepare("UPDATE users SET otp_hash = ?, otp_expired_at = ? WHERE email = ?");
        $update->execute([$otp_hash, $expired_at, $email]);
        
        // --- BLOK PENGIRIMAN EMAIL DENGAN PHPMAILER ---
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi Server SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // UBAH DUA BARIS INI DENGAN EMAIL DAN SANDI APLIKASI MILIKMU
            $mail->Username   = 'email_pengirim@gmail.com'; 
            $mail->Password   = '16_digit_sandi_aplikasi_disini'; 
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Pengaturan Pengirim dan Penerima
            $mail->setFrom('email_pengirimmu@gmail.com', 'Sistem Keamanan Kampus');
            $mail->addAddress($email);

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = 'Kode Rahasia Pemulihan Sandi Anda';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 500px;'>
                    <h2 style='color: #2c3e50;'>Permintaan Pemulihan Sandi</h2>
                    <p>Seseorang telah meminta untuk mereset sandi akun Anda. Berikut adalah kode OTP 6 digit Anda:</p>
                    <div style='background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 28px; font-weight: bold; letter-spacing: 5px; border-radius: 8px; color: #0d6efd;'>
                        {$otp}
                    </div>
                    <p style='color: #e74c3c; font-size: 12px; margin-top: 20px;'>*Kode ini bersifat rahasia dan akan kedaluwarsa dalam 5 menit.</p>
                </div>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Jika email gagal terkirim (hanya diam, abaikan agar tidak membocorkan error ke user)
        }
    }

    // Generic Response: Tidak ada lagi bocoran "debug_otp"
    echo json_encode([
        "status" => "success", 
        "pesan" => "Jika email terdaftar, instruksi dan kode OTP telah dikirim."
    ]);
} else {
    echo json_encode(["status" => "error", "pesan" => "Email tidak valid."]);
}
?>