<?php
// Mail ayarlarını içe aktar
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/mail-info.php';

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

// Gönderici ve alıcı ayarla
$mail->setFrom($mail_username);
$mail->addAddress("kdrksm@gmail.com");

// HTML şablonunu oku ve değiştir
$body = file_get_contents('./mail-template.html');

$gelen = ["username", "userID"];
$giden = ["Mehmet", 8];

$body = str_replace($gelen, $giden, $body);

// Mail içeriğini ayarla
$mail->isHTML(true);
$mail->Subject = "Mail Template Ornegi";
$mail->Body = $body;

// Maili gönder ve sonucu kontrol et
if ($mail->send()) {
    echo "Mail gonderimi basarili.";
} else {
    echo "Malesef olmadi.";
}