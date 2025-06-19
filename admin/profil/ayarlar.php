<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'; ?>

<?php
// PHPMailer sınıflarını dahil et
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kullanıcı bilgilerini almak için sorgu
try {
    $sorgu = "SELECT * FROM kullanicilar WHERE kadi = ? LIMIT 0,1";
    $stmt = $con->prepare($sorgu);
    $stmt->bindParam(1, $_SESSION["loginkey"]);
    $stmt->execute();
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kullanici) {
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

    // Profil resmi için varsayılan değer
    $profil_resmi = "/admin/profil/profil-image/default-profile.jpg";

    // Eğer profil_resmi alanı varsa ve dolu ise
    if ($profil_resmi_var && isset($kullanici['profil_resmi']) && !empty($kullanici['profil_resmi'])) {
        $profil_resmi = $kullanici['profil_resmi'];
    }
} catch (PDOException $exception) {
    die('<div class="alert alert-danger">HATA: ' . $exception->getMessage() . '</div>');
}

// Mail ayarlarını getir
try {
    $mailSorgu = $con->prepare("SELECT * FROM gonderen_mail WHERE aktif = 1 LIMIT 1");
    $mailSorgu->execute();

    if ($mailSorgu->rowCount() > 0) {
        $mailAyarlari = $mailSorgu->fetch(PDO::FETCH_ASSOC);

        // Mail ayarlarını değişkenlere ata
        $mail_id = $mailAyarlari['id'];
        $mail_email = $mailAyarlari['email'];
        $mail_password = $mailAyarlari['password'];
        $mail_host = $mailAyarlari['smtp_host'];
        $mail_port = $mailAyarlari['smtp_port'];
        $mail_secure = $mailAyarlari['smtp_secure'];
    } else {
        // Varsayılan değerler
        $mail_id = 0;
        $mail_email = '';
        $mail_password = '';
        $mail_host = 'smtp.gmail.com';
        $mail_port = '587';
        $mail_secure = 'tls';
    }
} catch (PDOException $exception) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> HATA: Mail ayarları alınamadı: ' . $exception->getMessage() . '
          </div>';
}

// Profil bilgilerini güncelleme işlemi
if (isset($_POST['profil_guncelle'])) {
    try {
        // Güncelleme için değerleri al
        $adsoyad = htmlspecialchars(strip_tags($_POST['adsoyad'] ?? ''));
        $eposta = htmlspecialchars(strip_tags($_POST['eposta'] ?? ''));
        $tel_no = htmlspecialchars(strip_tags($_POST['tel_no'] ?? ''));

        // Profil resmi yükleme işlemi
        $resim_guncellendi = false;
        $profil_resmi_alani_eklendi = false;

        if (isset($_FILES["profil_resmi"]) && $_FILES["profil_resmi"]["error"] == 0) {
            $izin_verilen_uzantilar = array("jpg", "jpeg", "png", "gif");
            $dosya_adi = $_FILES["profil_resmi"]["name"];
            $dosya_uzantisi = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));

            // Dosya uzantısı kontrolü
            if (in_array($dosya_uzantisi, $izin_verilen_uzantilar)) {
                // Dosya boyutu kontrolü (5MB)
                if ($_FILES["profil_resmi"]["size"] < 5 * 1024 * 1024) {
                    // Benzersiz dosya adı oluşturma
                    $yeni_dosya_adi = uniqid() . "." . $dosya_uzantisi;
                    $hedef_dizin = $_SERVER['DOCUMENT_ROOT'] . "/content/images/profil/";
                    $hedef_dosya = $hedef_dizin . $yeni_dosya_adi;

                    // Dizin kontrolü ve oluşturma
                    if (!file_exists($hedef_dizin)) {
                        mkdir($hedef_dizin, 0777, true);
                    }

                    // Dosyayı taşıma
                    if (move_uploaded_file($_FILES["profil_resmi"]["tmp_name"], $hedef_dosya)) {
                        $profil_resmi = "/content/images/profil/" . $yeni_dosya_adi;
                        $resim_guncellendi = true;

                        // profil_resmi alanı yoksa, ekle
                        if (!$profil_resmi_var) {
                            try {
                                $alan_ekleme_sorgu = $con->prepare("ALTER TABLE kullanicilar ADD profil_resmi VARCHAR(255) NULL");
                                $alan_ekleme_sorgu->execute();
                                $profil_resmi_var = true;
                                $profil_resmi_alani_eklendi = true;

                                echo '<div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Profil resmi özelliği tabloya eklendi.
                                      </div>';
                            } catch (PDOException $e) {
                                echo '<div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Profil resmi özelliği eklenemedi: ' . $e->getMessage() . '
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

        if ($resim_guncellendi && $profil_resmi_var) {
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

        if ($stmt->execute($parametreler)) {
            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Profil bilgileriniz başarıyla güncellendi.
                  </div>';
        } else {
            echo '<div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Güncelleme işlemi sırasında bir hata oluştu.
                  </div>';
        }
    } catch (PDOException $exception) {
        echo '<div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> HATA: ' . $exception->getMessage() . '
              </div>';
    }
}

// Mail ayarlarını güncelleme işlemi
if (isset($_POST['mail_guncelle'])) {
    try {
        // Formdan gelen değerleri al
        $mail_email = htmlspecialchars(strip_tags($_POST['mail_email']));
        $mail_password = $_POST['mail_password']; // Şifreyi HTML filtreleme yapmadan al
        $mail_host = htmlspecialchars(strip_tags($_POST['mail_host']));
        $mail_port = htmlspecialchars(strip_tags($_POST['mail_port']));
        $mail_secure = htmlspecialchars(strip_tags($_POST['mail_secure']));

        if ($mail_id > 0) {
            // Varolan kaydı güncelle
            $mailGuncelle = $con->prepare("UPDATE gonderen_mail SET 
                email = ?, 
                password = ?, 
                smtp_host = ?, 
                smtp_port = ?, 
                smtp_secure = ? 
                WHERE id = ?");

            $mailGuncelle->execute([
                $mail_email,
                $mail_password,
                $mail_host,
                $mail_port,
                $mail_secure,
                $mail_id
            ]);

            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Mail ayarlarınız başarıyla güncellendi.
                  </div>';
        } else {
            // Yeni kayıt ekle
            $mailEkle = $con->prepare("INSERT INTO gonderen_mail 
                (email, password, smtp_host, smtp_port, smtp_secure, aktif) 
                VALUES (?, ?, ?, ?, ?, 1)");

            $mailEkle->execute([
                $mail_email,
                $mail_password,
                $mail_host,
                $mail_port,
                $mail_secure
            ]);

            // Yeni eklenen kaydın ID'sini al
            $mail_id = $con->lastInsertId();

            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Mail ayarlarınız başarıyla kaydedildi.
                  </div>';
        }

        // Test mail gönderme işlemi
        if (isset($_POST['test_mail']) && $_POST['test_mail'] == 1) {
            // Test e-postası gönderme işlemi kod bloğu buraya eklenebilir
        }
    } catch (PDOException $exception) {
        echo '<div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> HATA: ' . $exception->getMessage() . '
              </div>';
    }
}

// Test e-postası gönderme işlemi
if (isset($_POST['send_test_mail'])) {
    // PHPMailer dosyalarını dahil et
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/ilan/mailgonder/PHPMailer-6.9.1/src/Exception.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/ilan/mailgonder/PHPMailer-6.9.1/src/PHPMailer.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/ilan/mailgonder/PHPMailer-6.9.1/src/SMTP.php';

    // Test değerlerini al
    $test_subject = htmlspecialchars(strip_tags($_POST['test_subject']));
    $test_recipient = htmlspecialchars(strip_tags($_POST['test_recipient']));
    $recipient_name = $adsoyad; // Varsayılan olarak kullanıcının adını kullan

    try {
        // PHPMailer nesnesi oluştur
        $testMail = new PHPMailer(true);

        // SMTP ayarları
        $testMail->isSMTP();
        $testMail->Host = $mail_host;
        $testMail->SMTPAuth = true;
        $testMail->Username = $mail_email;
        $testMail->Password = $mail_password;

        if ($mail_secure === 'tls') {
            $testMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($mail_secure === 'ssl') {
            $testMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $testMail->Port = $mail_port;
        $testMail->CharSet = "UTF-8";

        // Gönderici ve alıcı
        $testMail->setFrom($mail_email, 'Emlak & Müteahit - Test');
        $testMail->addAddress($test_recipient, $recipient_name);

        // Mail içeriği
        $testMail->isHTML(true);
        $testMail->Subject = $test_subject;
        $testMail->Body = '<div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <h2 style="color: #4285f4;">Mail Ayarları Test Mesajı</h2>
            <p>Merhaba <strong>' . $recipient_name . '</strong>,</p>
            <p>Bu e-posta, mail ayarlarınızın doğru çalıştığını doğrulamak için gönderilmiştir.</p>
            <p>Mail ayarlarınız aşağıdaki gibidir:</p>
            <ul>
                <li><strong>SMTP Sunucu:</strong> ' . $mail_host . '</li>
                <li><strong>SMTP Port:</strong> ' . $mail_port . '</li>
                <li><strong>Güvenlik:</strong> ' . $mail_secure . '</li>
                <li><strong>Mail Adresi:</strong> ' . $mail_email . '</li>
            </ul>
            <p>Test tarihi: ' . date('d.m.Y H:i:s') . '</p>
            <p style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #eee;">
                <em>Bu mesaj otomatik olarak gönderilmiştir. Lütfen yanıtlamayınız.</em>
            </p>
        </div>';

        // Maili gönder
        if ($testMail->send()) {
            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Test e-postası başarıyla gönderildi: ' . $test_recipient . '
                    <hr>
                    <strong>Ayarlar:</strong><br>
                    SMTP: ' . $mail_host . ':' . $mail_port . '<br>
                    Güvenlik: ' . $mail_secure . '<br>
                    Gönderen: ' . $mail_email . '<br>
                    Alıcı: ' . $test_recipient . '
                  </div>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> Test e-postası gönderilirken bir hata oluştu: ' . $e->getMessage() . '
              </div>';
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Profil Ayarları</h1>
    </div>

    <!-- Sekme Başlıkları -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-user"></i> Profil Bilgileri</a></li>
        <li role="presentation"><a href="#security" aria-controls="security" role="tab" data-toggle="tab"><i class="fas fa-shield-alt"></i> Hesap Güvenliği</a></li>
        <li role="presentation"><a href="#mail" aria-controls="mail" role="tab" data-toggle="tab"><i class="fas fa-envelope"></i> Mail Ayarları</a></li>
    </ul>

    <!-- Sekme İçerikleri -->
    <div class="tab-content">
        <!-- Profil Bilgileri Sekmesi -->
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-user-edit"></i> Profil Bilgilerini Düzenleme</h3>
                </div>
                <div class="panel-body">
                    <!-- Profil Resmi ve Bilgiler -->
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="<?php echo htmlspecialchars($profil_resmi); ?>" alt="Profil Resmi" class="img-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; margin-bottom: 10px;">
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
                                        <?php if (!$profil_resmi_var): ?>
                                            <div class="alert alert-info" style="margin-top: 10px;">
                                                <i class="fas fa-info-circle"></i> Profil resmi özelliği henüz etkinleştirilmemiş. Bir resim yüklediğinizde otomatik olarak etkinleşecektir.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" name="profil_guncelle" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Bilgileri Kaydet
                                        </button>
                                        <a href="/admin/profil/admin_profil.php?kadi=<?php echo $_SESSION["loginkey"]; ?>" class="btn btn-default">
                                            <i class="fas fa-arrow-left"></i> Profile Dön
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hesap Güvenliği Sekmesi -->
        <div role="tabpanel" class="tab-pane" id="security">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-shield-alt"></i> Hesap Güvenliği</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Şifrenizi değiştirmek için <a href="/admin/profil/sifre_degistir.php" class="btn btn-warning"><i class="fas fa-key"></i> Şifre Değiştir</a> sayfasını ziyaret edebilirsiniz.</p>
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
        </div>

        <!-- Mail Ayarları Sekmesi -->
        <div role="tabpanel" class="tab-pane" id="mail">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-envelope"></i> Mail Ayarları</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="mail_email">E-posta Adresi:</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="mail_email" name="mail_email" value="<?php echo htmlspecialchars($mail_email, ENT_QUOTES); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="mail_password">E-posta Şifresi:</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="mail_password" name="mail_password" value="<?php echo htmlspecialchars($mail_password, ENT_QUOTES); ?>" required>
                                        <span class="help-block">Gmail kullanıyorsanız, uygulama şifresi oluşturmanız gerekebilir</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="mail_host">SMTP Sunucu:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="mail_host" name="mail_host" value="<?php echo htmlspecialchars($mail_host, ENT_QUOTES); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="mail_port">SMTP Port:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="mail_port" name="mail_port" value="<?php echo htmlspecialchars($mail_port, ENT_QUOTES); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="mail_secure">Güvenlik:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="mail_secure" name="mail_secure" required>
                                            <option value="tls" <?php echo ($mail_secure == 'tls') ? 'selected' : ''; ?>>TLS</option>
                                            <option value="ssl" <?php echo ($mail_secure == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" name="mail_guncelle" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Mail Ayarlarını Kaydet
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Bilgi:</strong> Mail ayarları güncellendiğinde, tüm e-posta işlemleri için otomatik olarak bu ayarlar kullanılacaktır.
                            </div>

                            <hr>

                            <h4><i class="fas fa-paper-plane"></i> Mail Ayarlarını Test Et</h4>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="test_subject">Test Başlık:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="test_subject" name="test_subject" value="Mail Ayarları Test Mesajı" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="test_recipient">Alıcı E-posta:</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="test_recipient" name="test_recipient" value="<?php echo htmlspecialchars($eposta, ENT_QUOTES); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" name="send_test_mail" class="btn btn-info">
                                            <i class="fas fa-paper-plane"></i> Test E-postası Gönder
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        
        // Sekmeye tıklandığında URL hash'i değiştir
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
        
        // Sayfa yüklendiğinde hash varsa ilgili sekmeyi aktif et
        var hash = window.location.hash;
        if (hash) {
            $('ul.nav-tabs a[href="' + hash + '"]').tab('show');
        }
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
    
    .panel-primary {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    
    .tab-content {
        padding-top: 20px;
    }
    
    .nav-tabs {
        margin-bottom: 0;
    }
    
    .page-header {
        margin-bottom: 20px;
    }
    
    .img-circle.img-thumbnail {
        object-fit: cover;
    }
</style>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>