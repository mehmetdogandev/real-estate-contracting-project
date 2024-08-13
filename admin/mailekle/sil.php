<?php
// Veritabanı ayar dosyasını dahil et
include '../../config/vtabani.php';

try {
    // Kaydın id bilgisini al
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: ID bilgisi bulunamadı.');

    // Önce gonderilenler tablosundan ilgili kayıtları sil
    $sorgu_gonderilenler = "DELETE FROM gonderilenler WHERE kisi_id = ?";
    $stmt_gonderilenler = $con->prepare($sorgu_gonderilenler);
    $stmt_gonderilenler->bindParam(1, $id);

    // Gonderilenler tablosundaki kayıtları sil
    $stmt_gonderilenler->execute();

    // Ardından kisiler tablosundan ilgili kaydı sil
    $sorgu_kisiler = "DELETE FROM kisiler WHERE id = ?";
    $stmt_kisiler = $con->prepare($sorgu_kisiler);
    $stmt_kisiler->bindParam(1, $id);

    // Sorguyu çalıştır
    if ($stmt_kisiler->execute()) {
        // Kayıt listeleme sayfasına yönlendir ve kullanıcıya kaydın silindiğini bildir
        header('Location: islem_sec.php?islem=silindi');
    } else {
        // Silinemediğini bildir
        header('Location: islem_sec.php?islem=silinemedi');
    }
} catch (PDOException $exception) {
    // Hata varsa göster
    die('HATA: ' . $exception->getMessage());
}
?>
