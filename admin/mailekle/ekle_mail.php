<?php include "../header.php" ?>

<div class="container">
    <div class="page-header">
        <h1>Kullanıcı Ekle</h1>
    </div>
    <!-- PHP kayıt ekleme kodları burada yer alacak -->
    <?php
    if ($_POST) {
        // veritabanı yapılandırma dosyasını dahil et
        // veritabanı bağlantısı için gerekli parametreler
        $host = "localhost";
        $vt_adi = "emlak";
        $kullanici_adi = "root";
        $sifre = "";
        try {
         $con = new PDO("mysql:host={$host};dbname={$vt_adi}", $kullanici_adi, $sifre,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        // hatayı göster
        catch(PDOException $exception){
         echo "Bağlantı hatası: " . $exception->getMessage();
        }
        
        try {
            // kayıt ekleme sorgusu
            $sorgu = "INSERT INTO kisiler SET ad=:ad, soyad=:soyad, email=:email, son_gonderilen_email_tarih=:son_gonderilen_email_tarih";
            // sorguyu hazırla
            $stmt = $con->prepare($sorgu);
            // post edilen değerler
            $ad = htmlspecialchars(strip_tags($_POST['ad']));
            $soyad = htmlspecialchars(strip_tags($_POST['soyad']));
            $email = htmlspecialchars(strip_tags($_POST['email']));
            // anlık tarih ve saat
            $son_gonderilen_email_tarih = date('Y-m-d H:i:s');
            // parametreleri bağla
            $stmt->bindParam(':ad', $ad);
            $stmt->bindParam(':soyad', $soyad);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':son_gonderilen_email_tarih', $son_gonderilen_email_tarih);
            // sorguyu çalıştır
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Kullanıcı kaydedildi.</div>";
            } else {
                echo "<div class='alert alert-danger'>Kullanıcı kaydedilemedi.</div>";
            }
        }
        // hatayı göster
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }
    ?>

    <!-- Kullanıcı eklemek için kullanılacak html formu burada yer alacak -->
    <!-- Kullanıcı bilgilerini girmek için kullanılacak html formu -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Ad</td>
                <td><input type='text' name='ad' class='form-control' /></td>
            </tr>
            <tr>
                <td>Soyad</td>
                <td><input type='text' name='soyad' class='form-control' /></td>
            </tr>
            <tr>
                <td>E-Posta</td>
                <td><input type='text' name='email' class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Kaydet' class='btn btn-primary' />
                    <a href='islem_sec.php' class='btn btn-danger'>Kullanıcı listesi</a>
                </td>
            </tr>
        </table>
    </form>
</div> <!-- container -->

<?php include "../footer.php" ?>
