<?php
session_start();
if ($_SESSION["loginkey"] == "") {
    // oturum açılmamışsa login.php sayfasına git
    header("Location: /admin/login.php");
}
?>

<?php
// veritabanı ayar dosyasını dahil et
include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';
try {
    // kaydın id bilgisini al
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Id bilgisi bulunamadı.');

    // silinecek kayıt bilgilerini oku
    // silme sorguları...
    $sorgu = "DELETE FROM admin_mesajlar WHERE msj_id = ?";
    $stmt = $con->prepare($sorgu);
    $stmt->bindParam(1, $id);

    // sorguyu çalıştır
    if ($stmt->execute()) {
        // kayıt listeleme sayfasına yönlendir
        // ve kullanıcıya kaydın silindiğini
        header('Location: liste.php?islem=silindi');
    } // veya silinemediğini bildir
    else {
        header('Location: liste.php?islem=silinemedi');
    }
}
// hata varsa göster
catch (PDOException $exception) {
    die('HATA: ' . $exception->getMessage());
}
?>
