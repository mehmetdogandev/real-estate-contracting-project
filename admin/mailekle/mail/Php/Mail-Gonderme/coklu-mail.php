<?php
session_start();
if ($_SESSION["loginkey"] == "") {
    // oturum açılmamışsa login.php sayfasına git
    header("Location: /proje/admin/login.php");
}

// Header dosyasını dahil et
 include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/header.php'; 
 ?>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer();


$mail->isSMTP();
$mail->SMTPKeepAlive = true;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls'; //ssl

$mail->Port = 587; //25 , 465 , 587
$mail->Host = "smtp.gmail.com";

$mail->Username = "mehmetdogan.dev@gmail.com";
$mail->Password = "icnx rcgc nkfb ypee";


$mail->setFrom("mehmetdogan.dev@gmail.com");

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


foreach ($data as $d) {
    $mail->addAddress($d["email"]);

    $body = file_get_contents('./mail-template.html');

    $gelen = ["username", "userID"];
    $giden = [$d["name"], $d["id"]];

    $body = str_replace($gelen, $giden, $body);

    $mail->isHTML(true);
    $mail->Subject = "Hosgeldin " . $d["name"];
    $mail->Body = $body;

    if ($mail->send())
        echo "Mail gonderimi basarili.";
    else
        echo "Malesef olmadi. HATA : " . $mail->ErrorInfo;

    $mail->clearAddresses();
    $mail->clearAttachments();
}


?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/footer.php';  ?>