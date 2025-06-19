<?php


// Header dosyasını dahil et
include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';

// Mail ayarlarını içe aktar
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/mail-info.php';

// Form gönderildi mi kontrol et
if (isset($_POST["email"])) {
    // Mail gönderilecek adres olarak veritabanından alınan mail adresini kullan
    $kime = $mail_username;
    $konu = $_POST["subject"];

    $mesaj = "<h1>" . $_POST["message"] . "</h1>";
    $baslik = "From: " . $_POST["name"] . "<" . $_POST["email"] . ">\r\n";
    $baslik .= "Reply-to: $mail_username\r\n";
    $baslik .= "Content-type: text/html\r\n";

    // Mail gönder ve sonucu kontrol et
    if (mail($kime, $konu, $mesaj, $baslik)) {
        echo "Emailiniz basariyla gonderilmistir.";
    } else {
        echo "Malesef emailiniz gonderilemedi.";
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
        <input type="text" name="name" placeholder="Adiniz.."><br>
        <input type="text" name="subject" placeholder="Konu.."><br>
        <input type="text" name="email" placeholder="Emailiniz.."><br>
        <textarea name="message"></textarea><br>
        <button>Gonder</button>
    </form>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>
</body>
</html>