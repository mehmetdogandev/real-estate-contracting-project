<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/header.php'; ?>
<?php
// Mail ayarlarını içe aktar
include_once $_SERVER['DOCUMENT_ROOT'] . '/proje/config/mail-info.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// İlk örnek mail gönderimi
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
$mail->addAddress("MEHMET DOĞAN");

// Mail içeriğini ayarla
$mail->isHTML(true);
$mail->Subject = "Gmail SMTP Ornegi";
$mail->Body = "<h1>Merhaba Mehmet</h1><p>Bu bir denemedir.</p>";

// Dosya ekle
$mail->addAttachment("dosya.txt");

// Maili gönder ve sonucu kontrol et
if ($mail->send()) {
    echo "Mail gonderimi basarili.";
} else {
    echo "Malesef olmadi.";
}

// Formdan gelen verileri işle
if ($_POST) {
    $baslik = trim($_POST['baslik']);
    $konu = trim($_POST['konu']);
    $icerik = trim($_POST['icerik']);

    if (!$baslik || !$konu || !$icerik) {
        echo "Lütfen boş alan bırakmayınız";
    } else {
        // Yeni bir PHPMailer nesnesi oluştur
        $mail = new PHPMailer();
        
        // Veritabanından alınan SMTP ayarlarını kullan
        $mail->Host = $mail_host;
        $mail->Port = $mail_port;
        $mail->SMTPSecure = $mail_secure;
        $mail->SMTPAuth = true;
        $mail->Username = $mail_username;
        $mail->Password = $mail_password;
        $mail->IsSMTP();

        // Mail başlık ve içerik ayarları
        $mail->From = $mail_username;
        $mail->FromName = $baslik;
        $mail->CharSet = "utf8";
        $mail->Subject = $konu;
        $mailicerigi = $icerik;

        // Aboneleri ekle
        $aboneler = $con->prepare("SELECT * FROM tb_mail_gonder");
        $aboneler->execute();

        if ($aboneler->rowCount()) {
            foreach ($aboneler as $row) {
                $mail->AddBCC($row['mail']);
            }
        }

        $mail->MsgHTML($mailicerigi);

        // Maili gönder ve sonucu kontrol et
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

<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/footer.php'; ?>
</body>
</html>