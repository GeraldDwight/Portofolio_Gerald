<?php
// Menggunakan library PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Memuat file autoloader dari PHPMailer
// Sesuaikan path ini jika Anda meletakkan folder PHPMailer di tempat lain
require '../assets/vendor/PHPMailer/src/Exception.php';
require '../assets/vendor/PHPMailer/src/PHPMailer.php';
require '../assets/vendor/PHPMailer/src/SMTP.php';

// Cek jika form disubmit menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form dan bersihkan
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validasi dasar
    if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Jika ada data yang kosong atau email tidak valid, kirim pesan error.
        http_response_code(400);
        echo 'Please fill all fields and provide a valid email address.';
        exit;
    }

    // Buat instance baru dari PHPMailer
    $mail = new PHPMailer(true);

    try {
        //======================================================================
        // PENGATURAN SERVER (SMTP DITARO DI SINI)
        //======================================================================
        $mail->isSMTP();                                      // Mengaktifkan pengiriman via SMTP
        $mail->Host       = 'smtp.gmail.com';                 // Set server SMTP untuk Gmail
        $mail->SMTPAuth   = true;                               // Mengaktifkan otentikasi SMTP
        $mail->Username   = 'geralddwight2003@gmail.com';     // <== ALAMAT EMAIL GMAIL ANDA
        $mail->Password   = 'abcd efgh ijkl mnop';              // <== GUNAKAN "APP PASSWORD" DARI AKUN GOOGLE ANDA
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Mengaktifkan enkripsi TLS
        $mail->Port       = 587;                                // Port TCP untuk koneksi (587 untuk TLS)

        //======================================================================
        // PENERIMA EMAIL
        //======================================================================
        $mail->setFrom($email, $name); // Email pengirim akan muncul dari orang yang mengisi form
        $mail->addAddress('geralddwight2003@gmail.com', 'Gerald Dwight'); // <== EMAIL TUJUAN ANDA (EMAIL ANDA SENDIRI)
        $mail->addReplyTo($email, $name); // Saat Anda membalas, email akan terkirim ke pengisi form

        //======================================================================
        // KONTEN EMAIL
        //======================================================================
        $mail->isHTML(true); // Set format email ke HTML
        $mail->Subject = 'Pesan Baru dari Portfolio: ' . $subject;
        
        // Body email dalam bentuk HTML yang lebih rapi
        $mail->Body    = "Anda telah menerima pesan baru dari formulir kontak di website Anda.<br><br>" .
                         "<strong>Nama:</strong> " . $name . "<br>" .
                         "<strong>Email:</strong> " . $email . "<br>" .
                         "<strong>Subjek:</strong> " . $subject . "<br><br>" .
                         "<strong>Pesan:</strong><br>" . nl2br($message); // nl2br untuk mengubah baris baru menjadi <br>

        // Body alternatif untuk email client yang tidak mendukung HTML
        $mail->AltBody = "Anda telah menerima pesan baru.\n\n" .
                         "Nama: " . $name . "\n" .
                         "Email: " . $email . "\n" .
                         "Subjek: " . $subject . "\n\n" .
                         "Pesan:\n" . $message;

        // Kirim email
        $mail->send();
        echo 'Your message has been sent. Thank you!';

    } catch (Exception $e) {
        // Jika terjadi error, kirim pesan error yang lebih detail
        http_response_code(500);
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    // Jika file diakses langsung bukan melalui POST
    http_response_code(403);
    echo 'There was a problem with your submission, please try again.';
}
?>