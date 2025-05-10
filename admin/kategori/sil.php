<?php
session_start();
if ($_SESSION["loginkey"] == "") {
    // oturum açılmamışsa login.php sayfasına git
    header("Location: /proje/admin/login.php");
}
?>

<?php
// veritabanı ayar dosyasını dahil et
include $_SERVER['DOCUMENT_ROOT'] . '/proje/config/vtabani.php'; 
try {
    // kaydın id bilgisini al
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Id bilgisi bulunamadı.');
    // silme sorgusu
    $sorgu = "DELETE FROM projeler_kategoriler WHERE id = ?";
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
    //die('<b>Bu kategoriyi silmek için öncelikle projeler_kategorilere bağlı ürünleri silmeniz gerekir...</b><br/><br/> HATA: ' . $exception->getMessage());
    header('Location: liste.php?islem=hata');
}
?>