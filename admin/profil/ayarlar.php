
<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Profil Ayarları</h1>
    </div>

    <?php
    // Kullanıcı bilgilerini almak için sorgu
    try {
        $sorgu = "SELECT * FROM kullanicilar WHERE kadi = ? LIMIT 0,1";
        $stmt = $con->prepare($sorgu);
        $stmt->bindParam(1, $_SESSION["loginkey"]);
        $stmt->execute();
        $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$kullanici) {
            die('<div class="alert alert-danger">HATA: Kullanıcı bilgileri alınamadı.</div>');
        }
        
        // Kullanıcı bilgilerini değişkenlere at
        $id = $kullanici['id'];
        $adsoyad = $kullanici['adsoyad'];
        $kadi = $kullanici['kadi'];
        $eposta = $kullanici['eposta'];
        $tel_no = $kullanici['tel_no'];
        
        // Veritabanında profil_resmi alanının olup olmadığını kontrol et
        $tablo_kontrol = $con->prepare("SHOW COLUMNS FROM kullanicilar LIKE 'profil_resmi'");
        $tablo_kontrol->execute();
        $profil_resmi_var = ($tablo_kontrol->rowCount() > 0);
        
        // Profil resmi için varsayılan değer - sizin belirttiğiniz yolu kullanıyoruz
        $profil_resmi = "/proje/admin/profil/profil-image/default-profile.jpg";
        
        // Eğer profil_resmi alanı varsa ve dolu ise
        if($profil_resmi_var && isset($kullanici['profil_resmi']) && !empty($kullanici['profil_resmi'])) {
            $profil_resmi = $kullanici['profil_resmi'];
        }
    }
    catch (PDOException $exception) {
        die('<div class="alert alert-danger">HATA: ' . $exception->getMessage() . '</div>');
    }
    
    // Profil bilgilerini güncelleme işlemi
    if($_POST) {
        try {
            // Güncelleme için değerleri al
            $adsoyad = htmlspecialchars(strip_tags($_POST['adsoyad']));
            $eposta = htmlspecialchars(strip_tags($_POST['eposta']));
            $tel_no = htmlspecialchars(strip_tags($_POST['tel_no']));
            
            // Profil resmi yükleme işlemi
            $resim_guncellendi = false;
            $profil_resmi_alani_eklendi = false;
            
            if(isset($_FILES["profil_resmi"]) && $_FILES["profil_resmi"]["error"] == 0) {
                $izin_verilen_uzantilar = array("jpg", "jpeg", "png", "gif");
                $dosya_adi = $_FILES["profil_resmi"]["name"];
                $dosya_uzantisi = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));
                
                // Dosya uzantısı kontrolü
                if(in_array($dosya_uzantisi, $izin_verilen_uzantilar)) {
                    // Dosya boyutu kontrolü (5MB)
                    if($_FILES["profil_resmi"]["size"] < 5 * 1024 * 1024) {
                        // Benzersiz dosya adı oluşturma
                        $yeni_dosya_adi = uniqid() . "." . $dosya_uzantisi;
                        $hedef_dizin = $_SERVER['DOCUMENT_ROOT'] . "/proje/content/images/profil/";
                        $hedef_dosya = $hedef_dizin . $yeni_dosya_adi;
                        
                        // Dizin kontrolü ve oluşturma
                        if(!file_exists($hedef_dizin)) {
                            mkdir($hedef_dizin, 0777, true);
                        }
                        
                        // Dosyayı taşıma
                        if(move_uploaded_file($_FILES["profil_resmi"]["tmp_name"], $hedef_dosya)) {
                            $profil_resmi = "/proje/content/images/profil/" . $yeni_dosya_adi;
                            $resim_guncellendi = true;
                            
                            // profil_resmi alanı yoksa, ekle
                            if(!$profil_resmi_var) {
                                try {
                                    $alan_ekleme_sorgu = $con->prepare("ALTER TABLE kullanicilar ADD profil_resmi VARCHAR(255) NULL");
                                    $alan_ekleme_sorgu->execute();
                                    $profil_resmi_var = true;
                                    $profil_resmi_alani_eklendi = true;
                                    
                                    echo '<div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Profil resmi özelliği tabloya eklendi.
                                          </div>';
                                } catch(PDOException $e) {
                                    echo '<div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Profil resmi özelliği eklenemedi: '.$e->getMessage().'
                                          </div>';
                                }
                            }
                        } else {
                            echo '<div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Profil resmi yüklenirken bir hata oluştu.
                                  </div>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> Dosya boyutu çok büyük. Maksimum 5MB olmalıdır.
                              </div>';
                    }
                } else {
                    echo '<div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Sadece JPG, JPEG, PNG ve GIF dosyaları yükleyebilirsiniz.
                          </div>';
                }
            }
            
            // Veritabanı güncelleme sorgusu
            $sorgu = "";
            $parametreler = array();
            
            if($resim_guncellendi && $profil_resmi_var) {
                // Profil resmi alanı varsa ve resim yüklendiyse
                $sorgu = "UPDATE kullanicilar SET adsoyad = ?, eposta = ?, tel_no = ?, profil_resmi = ? WHERE id = ?";
                $parametreler = array($adsoyad, $eposta, $tel_no, $profil_resmi, $id);
            } else {
                // Sadece profil bilgilerini güncelle
                $sorgu = "UPDATE kullanicilar SET adsoyad = ?, eposta = ?, tel_no = ? WHERE id = ?";
                $parametreler = array($adsoyad, $eposta, $tel_no, $id);
            }
            
            // Güncelleme sorgusunu çalıştır
            $stmt = $con->prepare($sorgu);
            
            if($stmt->execute($parametreler)) {
                echo '<div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Profil bilgileriniz başarıyla güncellendi.
                      </div>';
            } else {
                echo '<div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i> Güncelleme işlemi sırasında bir hata oluştu.
                      </div>';
            }
        }
        catch(PDOException $exception) {
            echo '<div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> HATA: ' . $exception->getMessage() . '
                  </div>';
        }
    }
    ?>

     <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-user-edit"></i> Profil Bilgilerini Düzenleme</h3>
                </div>
                <div class="panel-body">
                    <!-- Profil Resmi ve Bilgiler -->
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="<?php echo htmlspecialchars($profil_resmi); ?>" alt="Profil Resmi" class="img-circle img-thumbnail" style="width: 150px; height: 150px; margin-bottom: 10px;">
                            <h4><?php echo htmlspecialchars($adsoyad); ?></h4>
                            <p><small><i class="fas fa-user"></i> <?php echo htmlspecialchars($kadi); ?></small></p>
                        </div>
                        <div class="col-md-9">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="adsoyad">Ad ve Soyad:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="adsoyad" name="adsoyad" value="<?php echo htmlspecialchars($adsoyad, ENT_QUOTES); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="eposta">E-posta:</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="eposta" name="eposta" value="<?php echo htmlspecialchars($eposta, ENT_QUOTES); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="tel_no">Telefon:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tel_no" name="tel_no" value="<?php echo htmlspecialchars($tel_no, ENT_QUOTES); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="profil_resmi">Profil Resmi:</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    Dosya Seç <input type="file" name="profil_resmi" id="profil_resmi" accept="image/*">
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <span class="help-block">Sadece JPG, JPEG, PNG ve GIF dosyaları yükleyebilirsiniz. Maksimum boyut: 5MB</span>
                                        <?php if(!$profil_resmi_var): ?>
                                        <div class="alert alert-info" style="margin-top: 10px;">
                                            <i class="fas fa-info-circle"></i> Profil resmi özelliği henüz etkinleştirilmemiş. Bir resim yüklediğinizde otomatik olarak etkinleşecektir.
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Bilgileri Kaydet
                                        </button>
                                        <a href="/proje/admin/profil/admin_profil.php?kadi=<?php echo $_SESSION["loginkey"]; ?>" class="btn btn-default">
                                            <i class="fas fa-arrow-left"></i> Profile Dön
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-shield-alt"></i> Hesap Güvenliği</h3>
                </div>
                <div class="panel-body">
                    <p>Şifrenizi değiştirmek için <a href="/proje/admin/sifre_degistir.php" class="btn btn-sm btn-warning"><i class="fas fa-key"></i> Şifre Değiştir</a> sayfasını ziyaret edebilirsiniz.</p>
                    <hr>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Güvenlik İpucu:</strong> Hesabınızın güvenliği için aşağıdaki önlemleri almanızı öneririz:
                        <ul>
                            <li>Güçlü ve benzersiz bir şifre kullanın</li>
                            <li>Şifrenizi düzenli olarak değiştirin</li>
                            <li>Yönetici bilgilerinizi başkalarıyla paylaşmayın</li>
                            <li>Ortak kullanılan cihazlarda işiniz bittiğinde oturumunuzu kapatın</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->

<!-- Dosya seçildiğinde metnin güncellenmesi için jQuery kodu -->
<script>
$(document).ready(function() {
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this);
        var numFiles = input.get(0).files ? input.get(0).files.length : 1;
        var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text');
        input.val(label);
    });
});
</script>

<!-- Dosya seçimi için stil -->
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/footer.php'; ?>