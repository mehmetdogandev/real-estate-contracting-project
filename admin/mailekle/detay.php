<?php  include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'; ?>
<div class="container">
    <div class="page-header">
        <h1>Kişi Bilgisi</h1>
    </div>
    <?php
    // Gelen id parametresini al
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Kayıt bulunamadı.');

    // Veritabanı bağlantı dosyasını çağır

    try {
        // Seçme sorgusunu hazırla
        $sorgu = "SELECT id, ad, soyad, email, son_gonderilen_email_tarih
                  FROM kisiler
                  WHERE id = ?";
        
        $stmt = $con->prepare($sorgu);

        // Id parametresini bağla
        $stmt->bindParam(1, $id);

        // Sorguyu çalıştır
        $stmt->execute();

        // Gelen kaydı bir değişkende sakla
        $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

        // Tabloya yazılacak bilgileri değişkenlere doldur
        $ad = $kayit['ad'];
        $soyad = $kayit['soyad'];
        $email = $kayit['email'];
        $son_gonderilen_email_tarih = $kayit['son_gonderilen_email_tarih'];
    } catch (PDOException $exception) {
        // Hata varsa göster
        die('HATA: ' . $exception->getMessage());
    }
    ?>

    <!-- Kişi bilgilerini görüntüleyen HTML tablosu -->
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ID</td>
            <td><?php echo htmlspecialchars($id, ENT_QUOTES); ?></td>
        </tr>
        <tr>
            <td>Ad</td>
            <td><?php echo htmlspecialchars($ad, ENT_QUOTES); ?></td>
        </tr>
        <tr>
            <td>Soyad</td>
            <td><?php echo htmlspecialchars($soyad, ENT_QUOTES); ?></td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td><?php echo htmlspecialchars($email, ENT_QUOTES); ?></td>
        </tr>
        <tr>
            <td>Son Gönderilen E-mail Tarihi</td>
            <td><?php echo htmlspecialchars($son_gonderilen_email_tarih, ENT_QUOTES); ?></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='islem_sec.php' class='btn btn-danger'> <span class='glyphicon glyphicon glyphicon glyphicon-list'></span> Kişi Listesi</a>
            </td>
        </tr>
    </table>
</div> <!-- container -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php';   ?>

