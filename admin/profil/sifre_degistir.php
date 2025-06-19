<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-key"></i> Şifre Değiştirme</h1>
    </div>

    <?php
    // Mevcut kullanıcı bilgilerini almak için sorgu
    try {
        $sorgu = "SELECT * FROM kullanicilar WHERE kadi = ? LIMIT 0,1";
        $stmt = $con->prepare($sorgu);
        $stmt->bindParam(1, $_SESSION["loginkey"]);
        $stmt->execute();
        $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$kullanici) {
            die('<div class="alert alert-danger">HATA: Kullanıcı bilgileri alınamadı.</div>');
        }
        
        $kullanici_id = $kullanici['id'];
        $mevcut_sifre = $kullanici['sifre'];
    }
    catch (PDOException $exception) {
        die('<div class="alert alert-danger">HATA: ' . $exception->getMessage() . '</div>');
    }
    
    // Şifre değiştirme işlemi
    if($_POST) {
        // Formdan gelen verileri al
        $eski_sifre = htmlspecialchars(strip_tags($_POST['eski_sifre']));
        $yeni_sifre = htmlspecialchars(strip_tags($_POST['yeni_sifre']));
        $yeni_sifre_tekrar = htmlspecialchars(strip_tags($_POST['yeni_sifre_tekrar']));
        
        // Hata kontrolü
        $hatalar = array();
        
        // Eski şifre kontrolü
        if($eski_sifre != $mevcut_sifre) {
            $hatalar[] = "Mevcut şifrenizi yanlış girdiniz.";
        }
        
        // Yeni şifre kontrolü
        if(strlen($yeni_sifre) < 6) {
            $hatalar[] = "Yeni şifreniz en az 6 karakter olmalıdır.";
        }
        
        // Yeni şifre tekrar kontrolü
        if($yeni_sifre != $yeni_sifre_tekrar) {
            $hatalar[] = "Girdiğiniz yeni şifreler eşleşmiyor.";
        }
        
        // Hata yoksa şifreyi güncelle
        if(empty($hatalar)) {
            try {
                $sorgu = "UPDATE kullanicilar SET sifre = ? WHERE id = ?";
                $stmt = $con->prepare($sorgu);
                $stmt->bindParam(1, $yeni_sifre);
                $stmt->bindParam(2, $kullanici_id);
                
                if($stmt->execute()) {
                    echo '<div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Şifreniz başarıyla değiştirildi.
                          </div>';
                } else {
                    echo '<div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> Şifre değiştirme işlemi sırasında bir hata oluştu.
                          </div>';
                }
            }
            catch(PDOException $exception) {
                echo '<div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> HATA: ' . $exception->getMessage() . '
                      </div>';
            }
        } else {
            // Hataları göster
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Lütfen aşağıdaki hataları düzeltin:';
            echo '<ul>';
            foreach($hatalar as $hata) {
                echo '<li>' . $hata . '</li>';
            }
            echo '</ul></div>';
        }
    }
    ?>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-lock"></i> Şifre Değiştirme Formu</h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="eski_sifre">Mevcut Şifre:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="eski_sifre" name="eski_sifre" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="yeni_sifre">Yeni Şifre:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="yeni_sifre" name="yeni_sifre" required>
                                <span class="help-block">Şifreniz en az 6 karakter olmalıdır.</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="yeni_sifre_tekrar">Yeni Şifre (Tekrar):</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="yeni_sifre_tekrar" name="yeni_sifre_tekrar" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Şifreyi Değiştir
                                </button>
                                <a href="/admin/admin_profil.php?kadi=<?php echo $_SESSION["loginkey"]; ?>" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Profile Dön
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-info-circle"></i> Güvenli Şifre Oluşturma İpuçları</h3>
                </div>
                <div class="panel-body">
                    <ul>
                        <li>En az 8 karakter uzunluğunda olmalıdır</li>
                        <li>Büyük ve küçük harfler içermelidir</li>
                        <li>Rakam ve özel karakterler içermelidir (örn. @, #, $, %)</li>
                        <li>Tahmin edilebilir bilgiler (doğum tarihi, isim vb.) içermemelidir</li>
                        <li>Önceki şifrelerinizle aynı olmamalıdır</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>