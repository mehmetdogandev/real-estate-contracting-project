<?php
session_start();
if ($_SESSION["loginkey"] == "") {
    // oturum açılmamışsa login.php sayfasına git
    header("Location: /proje/admin/login.php");
}

// Header dosyasını dahil et
include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/header.php'; 

// Mail ayarlarını içe aktar
include_once $_SERVER['DOCUMENT_ROOT'] . '/proje/config/mail-info.php';
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// PHPMailer nesnesi oluştur
$mail = new PHPMailer();

// Veritabanından alınan SMTP ayarlarını kullan
$mail->isSMTP();
$mail->SMTPKeepAlive = true;
$mail->SMTPAuth = true;
$mail->SMTPSecure = $mail_secure;
$mail->Port = $mail_port;
$mail->Host = $mail_host;
$mail->Username = $mail_username;
$mail->Password = $mail_password;

// Gönderici adresini ayarla
$mail->setFrom($mail_username);

// Gönderilecek kişilerin listesi
$data = [
    [
        "id" => 1,
        "name" => "Kadir",
        "email" => "dgn0236memet@gmail.com"
    ],
    [
        "id" => 2,
        "name" => "ahmet",
        "email" => "dgn8402memet@gmail.com"
    ],
    [
        "id" => 4,
        "name" => "mehmet",
        "email" => "mehmetdogan.dev@gmail.com"
    ]
];

// Her kişi için mail gönder
foreach ($data as $d) {
    $mail->addAddress($d["email"]);

    // Mail şablonunu oku
    $body = file_get_contents('./mail-template.html');

    // Şablondaki değişkenleri değiştir
    $gelen = ["username", "userID"];
    $giden = [$d["name"], $d["id"]];
    $body = str_replace($gelen, $giden, $body);

    // Mail içeriğini ayarla
    $mail->isHTML(true);
    $mail->Subject = "Hosgeldin " . $d["name"];
    $mail->Body = $body;

    // Maili gönder ve sonucu kontrol et
    if ($mail->send()) {
        echo "Mail gonderimi basarili.";
    } else {
        echo "Malesef olmadi. HATA : " . $mail->ErrorInfo;
    }

    // Bir sonraki gönderim için adres ve ekleri temizle
    $mail->clearAddresses();
    $mail->clearAttachments();
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/footer.php'; ?>