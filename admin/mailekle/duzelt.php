<?php  include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';  ?>

<div class="container">
    <div class="page-header">
        <h1>Kullanıcı Güncelleme</h1>
    </div>

    <?php
    // Gelen parametre değerini oku, ID bilgisi...
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: ID bilgisi bulunamadı.');

    // Veritabanı bağlantı dosyasını dahil et

    // Aktif kayıt bilgilerini oku
    try {
        // Seçme sorgusunu hazırla
        $sorgu = "SELECT id, ad, soyad, email, son_gonderilen_email_tarih
        FROM kisiler
        WHERE id = ?";
        $stmt = $con->prepare($sorgu);

        // ID parametresini bağla
        $stmt->bindParam(1, $id);

        // Sorguyu çalıştır
        $stmt->execute();

        // Okunan kayıt bilgilerini bir değişkene kaydet
        $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

        // Formu dolduracak değişken bilgileri
        $ad = $kayit['ad'];
        $soyad = $kayit['soyad'];
        $email = $kayit['email'];
    } catch (PDOException $exception) {
        die('HATA: ' . $exception->getMessage());
    }
    ?>

    <?php
    // Kaydet butonu tıklanmışsa
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        try {
            // Güncelleme sorgusu
            $sorgu = "UPDATE kisiler SET ad=:ad, soyad=:soyad, email=:email
                      WHERE id=:id";

            // Sorguyu hazırla
            $stmt = $con->prepare($sorgu);

            // Gelen bilgileri değişkenlere kaydet
            $ad = htmlspecialchars(strip_tags($_POST['ad']));
            $soyad = htmlspecialchars(strip_tags($_POST['soyad']));
            $email = htmlspecialchars(strip_tags($_POST['email']));

            // Parametreleri bağla
            $stmt->bindParam(':ad', $ad);
            $stmt->bindParam(':soyad', $soyad);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id);

            // Sorguyu çalıştır
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Kayıt güncellendi.</div>";
            } else {
                echo "<div class='alert alert-danger'>Kayıt güncellenemedi.</div>";
            }
        } catch (PDOException $exception) {
            die('HATA: ' . $exception->getMessage());
        }
    }
    ?>

    <!-- Kayıt bilgilerini güncelleyebileceğiniz HTML formu -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Ad</td>
                <td><input type='text' name='ad' value="<?php echo htmlspecialchars($ad, ENT_QUOTES); ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>Soyad</td>
                <td><input type='text' name='soyad' value="<?php echo htmlspecialchars($soyad, ENT_QUOTES); ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>E-Posta</td>
                <td><input type='text' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" class='btn btn-primary'><span class="glyphicon glyphicon-ok"></span> Kaydet</button>
                    <a href='islem_sec.php' class='btn btn-danger'><span class='glyphicon glyphicon glyphicon-list'></span> Kullanıcı Listesi</a>
                </td>
            </tr>
        </table>
    </form>
</div> <!-- container -->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php';   ?>

