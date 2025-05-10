<?php
// mail-info.php - Mail bilgilerini veritabanından çeker ve değişkenlerde saklar

// Eğer veritabanı bağlantısı yoksa, bağlantıyı kur
if (!isset($con)) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/proje/config/vtabani.php';
}

// Aktif mail ayarlarını veritabanından çek
$mailSorgu = $con->prepare("SELECT * FROM gonderen_mail WHERE aktif = 1 LIMIT 1");
$mailSorgu->execute();

// Sonuç var mı kontrol et
if ($mailSorgu->rowCount() > 0) {
    $mailBilgileri = $mailSorgu->fetch(PDO::FETCH_ASSOC);
    
    // Mail bilgilerini değişkenlere ata
    $mail_host = $mailBilgileri['smtp_host'];
    $mail_port = $mailBilgileri['smtp_port'];
    $mail_secure = $mailBilgileri['smtp_secure'];
    $mail_username = $mailBilgileri['email'];
    $mail_password = $mailBilgileri['password'];
} else {
    // Eğer veritabanında kayıt yoksa, varsayılan SMTP ayarları kullanılacak
    echo "HATA: Aktif mail gönderici hesabı bulunamadı!";
    exit;
}
?>