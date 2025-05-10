<?php include "header.php"?>

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
$mail->addAddress("MEHMET DOĞAN");


$mail->isHTML(true);
$mail->Subject = "Gmail SMTP Ornegi";
$mail->Body = "<h1>Merhaba Mehmet</h1><p>Bu bir denemedir.</p>";

$mail->addAttachment("dosya.txt");

if ($mail->send())
    echo "Mail gonderimi basarili.";
else
    echo "Malesef olmadi.";


    $host = "localhost";
$vt_adi = "emlak";
$kullanici_adi = "root";
$sifre = "*";
try {
    $con = new PDO(
        "mysql:host={$host};dbname={$vt_adi}",
        $kullanici_adi,
        $sifre,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
}
// hatayı göster
catch (PDOException $exception) {
    echo "Bağlantı hatası: " . $exception->getMessage();
}
if ($_POST) {

    require_once 'mail/class.phpmailer.php';

    $baslik = trim($_POST['baslik']);
    $konu = trim($_POST['konu']);
    $icerik = trim($_POST['icerik']);

    if (!$baslik || !$konu || !$icerik) {
        echo "Lütfen boş alan bırakmayınız";
    } else {
        $mail = new PHPMailer();
        $mail->Host = 'smtp.gmail.com'; //kendi smtp sunucunuzu kullanın
        $mail->Port = 587; //SSL var ise 465
        $mail->SMTPSecure = 'tls'; //ssl varsayılan = tls
        $mail->SMTPAuth = true; //smtp doğrulama ve aktifleştirme
        $mail->Username = "mehmetdogan.dev@gmail.com";
        $mail->Password = "icnx rcgc nkfb ypee";
        $mail->IsSMTP();

        $mail->From = "mehmetdogan.dev@gmail.com";
        $mail->FromName = $baslik;
        $mail->CharSet = "utf8";
        $mail->Subject = $konu;
        $mailicerigi = $icerik;

        $aboneler = $con->prepare("SELECT * FROM tb_mail_gonder");
        $aboneler->execute();

        if ($aboneler->rowCount()) {
            foreach ($aboneler as $row) {
                $mail->AddBCC($row['mail']);
            }
        }

        $mail->MsgHTML($mailicerigi);

        if ($mail->send()) {
            echo "Toplu Mail Gönderildi";
        } else {
            echo "Hata Oluştu";
        }
    }
}


?>


<!doctype html>
<html lang="en">

<head>

    <title>PHP Mail Fonksiyonu</title>
    <style>
        *:focus {
            outline: 0;
        }

        h1 {
            text-align: center;
            color: #666;
        }

        form {
            border: 1px solid #777;
            margin: 50px auto;
            width: 400px;
            padding: 30px 50px;
            border-radius: 10px;
        }

        input {
            width: 100%;
            padding-left: 10px;
            margin-top: 10px;
            line-height: 35px;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            width: 100%;
            height: 200px;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border-color: #888;
            box-sizing: border-box;
        }

        button {
            background-color: mediumseagreen;
            color: #fff;
            width: 100%;
            line-height: 35px;
            font-size: 17px;
            border-radius: 5px;
            margin-top: 10px;
        }

        button:hover {
            background-color: limegreen;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h1>Iletisim</h1>
        <input type="text" name="baslik" placeholder="Başklık.."><br>
        <input type="text" name="konu" placeholder="Konu.."><br>
        <textarea name="icerik"></textarea><br>
        <button type="submit">Toplu Mail Gönder</button>
    </form>




<?php include "footer.php"?>
