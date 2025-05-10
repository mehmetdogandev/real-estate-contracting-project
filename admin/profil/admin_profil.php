<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-circle"></i> Kullanıcı Profili</h1>
    </div>

    <?php
    // gelen parametre değerini oku, kadi bilgisi...
    $kadi = isset($_GET['kadi']) ? $_GET['kadi'] : die('HATA: kadi bilgisi bulunamadı.');

    // veritabanı bağlantı dosyasını dahil et
    // aktif kayıt bilgilerini oku
    try {
        // seçme sorgusunu hazırla
        $sorgu = "SELECT * FROM kullanicilar WHERE kadi = ? LIMIT 0,1";
        $stmt = $con->prepare($sorgu);

        // kadi parametresini bağla
        $stmt->bindParam(1, $kadi);

        // sorguyu çalıştır
        $stmt->execute();

        // okunan kayıt bilgilerini bir değişkene kaydet
        $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$kayit) {
            die('HATA: Kullanıcı bulunamadı.');
        }

        // Profil bilgileri
        $id = $kayit['id'];
        $adsoyad = $kayit['adsoyad'];
        $kadi = $kayit['kadi'];
        $eposta = $kayit['eposta'];
        $tel_no = $kayit['tel_no'];
        $onay = $kayit['onay'];

        // Onay durumuna göre kullanıcı tipi belirleme
        $kullanici_tipi = "";
        if ($onay == "1") {
            $kullanici_tipi = '<span class="label label-primary"><i class="fas fa-user-shield"></i> Admin</span>';
        } else if ($onay == "2") {
            $kullanici_tipi = '<span class="label label-success"><i class="fas fa-user"></i> Normal Üye</span>';
        } else {
            $kullanici_tipi = '<span class="label label-warning"><i class="fas fa-user-clock"></i> Onaylanmamış</span>';
        }

        // Profil resmi kontrolü
        // Veritabanında profil_resmi alanının olup olmadığını kontrol et
        $tablo_kontrol = $con->prepare("SHOW COLUMNS FROM kullanicilar LIKE 'profil_resmi'");
        $tablo_kontrol->execute();
        $profil_resmi_var = ($tablo_kontrol->rowCount() > 0);

        // Profil resmi için varsayılan değer ata
        $profil_resmi = "/proje/admin/profil/profil-image/default-profile.jpg";
        
        // Eğer profil_resmi alanı varsa ve dolu ise
        if ($profil_resmi_var && isset($kayit['profil_resmi']) && !empty($kayit['profil_resmi'])) {
            $profil_resmi = $kayit['profil_resmi'];
        }

        // İlan sayısını çek - evbilgi ve arsabilgi tablolarını kullanarak
        $ilan_sayisi = 0;

        // Ev ilanlarını say
        $ilan_sorgu_ev = $con->prepare("SELECT COUNT(*) as sayi FROM evbilgi WHERE ev_kimden = ?");
        $ilan_sorgu_ev->bindParam(1, $id); // Kullanıcı ID'si
        $ilan_sorgu_ev->execute();
        $ev_sayisi = $ilan_sorgu_ev->fetch(PDO::FETCH_ASSOC)['sayi'];

        // Arsa ilanlarını say
        $ilan_sorgu_arsa = $con->prepare("SELECT COUNT(*) as sayi FROM arsabilgi WHERE arsa_kimden = ?");
        $ilan_sorgu_arsa->bindParam(1, $id); // Kullanıcı ID'si
        $ilan_sorgu_arsa->execute();
        $arsa_sayisi = $ilan_sorgu_arsa->fetch(PDO::FETCH_ASSOC)['sayi'];

        // Toplam ilan sayısı
        $ilan_sayisi = $ev_sayisi + $arsa_sayisi;

        // Proje sayısını çek (tüm projeler)
        $proje_sorgu = $con->prepare("SELECT COUNT(*) as sayi FROM projeler");
        $proje_sorgu->execute();
        $proje_sayisi = $proje_sorgu->fetch(PDO::FETCH_ASSOC)['sayi'];

        // Mesaj sayısını çek
        $mesaj_sorgu = $con->prepare("SELECT COUNT(*) as sayi FROM kullanicilar_mesaj WHERE k_msj_kimden = ?");
        $mesaj_sorgu->bindParam(1, $id); // Kullanıcı ID'si
        $mesaj_sorgu->execute();
        $mesaj_sayisi = $mesaj_sorgu->fetch(PDO::FETCH_ASSOC)['sayi'];

        // Son eklenen ilanları çek - evbilgi ve urunler tablolarını birleştirerek
        $son_ilanlar_ev_sorgu = $con->prepare("
            SELECT u.*, k.kategoriadi as kategori_adi, e.ev_kimden
            FROM urunler u 
            LEFT JOIN kategoriler k ON u.kategori_id = k.id
            LEFT JOIN evbilgi e ON u.id = e.ev_urun_id
            WHERE e.ev_kimden = ? AND u.evarsa_id = 1
            ORDER BY u.giris_tarihi DESC LIMIT 3");
        $son_ilanlar_ev_sorgu->bindParam(1, $id);
        $son_ilanlar_ev_sorgu->execute();

        // Son eklenen arsa ilanlarını çek - arsabilgi ve urunler tablolarını birleştirerek
        $son_ilanlar_arsa_sorgu = $con->prepare("
            SELECT u.*, k.kategoriadi as kategori_adi, a.arsa_kimden
            FROM urunler u 
            LEFT JOIN kategoriler k ON u.kategori_id = k.id
            LEFT JOIN arsabilgi a ON u.id = a.arsa_urun_id
            WHERE a.arsa_kimden = ? AND u.evarsa_id = 2
            ORDER BY u.giris_tarihi DESC LIMIT 2");
        $son_ilanlar_arsa_sorgu->bindParam(1, $id);
        $son_ilanlar_arsa_sorgu->execute();

        // Her iki sorgudan da verileri al ve birleştir
        $ilanlar = array();
        while ($ilan = $son_ilanlar_ev_sorgu->fetch(PDO::FETCH_ASSOC)) {
            $ilanlar[] = $ilan;
        }
        while ($ilan = $son_ilanlar_arsa_sorgu->fetch(PDO::FETCH_ASSOC)) {
            $ilanlar[] = $ilan;
        }

        // Tarihe göre sırala (eğer ilan varsa)
        if (count($ilanlar) > 0) {
            usort($ilanlar, function ($a, $b) {
                return strtotime($b['giris_tarihi']) - strtotime($a['giris_tarihi']);
            });
        }

        // İlk 5 ilanı al
        $son_ilanlar = array_slice($ilanlar, 0, 5);

        // Son eklenen projeler (tüm projeler, en son eklenen 5 tanesi)
        $son_projeler_sorgu = $con->prepare("
            SELECT p.*, pk.kategoriadi as kategori_adi FROM projeler p 
            LEFT JOIN projeler_kategoriler pk ON p.kategori_id = pk.id 
            ORDER BY p.giris_tarihi DESC LIMIT 5");
        $son_projeler_sorgu->execute();
    }
    // hatayı göster
    catch (PDOException $exception) {
        die('HATA: ' . $exception->getMessage());
    }
    ?>

    <div class="row">
        <div class="col-md-4">
            <!-- Profil Kartı -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-id-card"></i> Profil Bilgileri</h3>
                </div>
                <div class="panel-body text-center">
                    <img src="<?php echo htmlspecialchars($profil_resmi); ?>" alt="Profil Resmi" class="img-circle img-thumbnail" style="width: 150px; height: 150px; margin-bottom: 15px;">
                    <h3><?php echo htmlspecialchars($adsoyad); ?></h3>
                    <p><?php echo $kullanici_tipi; ?></p>

                    <div class="list-group">
                        <div class="list-group-item">
                            <i class="fas fa-user"></i> <strong>Kullanıcı Adı:</strong> <?php echo htmlspecialchars($kadi); ?>
                        </div>
                        <div class="list-group-item">
                            <i class="fas fa-envelope"></i> <strong>E-posta:</strong> <?php echo htmlspecialchars($eposta); ?>
                        </div>
                        <div class="list-group-item">
                            <i class="fas fa-phone"></i> <strong>Telefon:</strong> <?php echo htmlspecialchars($tel_no); ?>
                        </div>
                    </div>

                    <?php if (isset($_SESSION["loginkey"]) && $_SESSION["loginkey"] == $kadi): ?>
                        <div class="btn-group btn-group-justified" role="group" style="margin-top: 20px;">
                            <a href="/proje/admin/ayarlar.php" class="btn btn-info"><i class="fas fa-edit"></i> Profili Düzenle</a>
                            <a href="/proje/admin/sifre_degistir.php" class="btn btn-warning"><i class="fas fa-key"></i> Şifre Değiştir</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Aktivite ve İstatistik Panelleri -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-chart-line"></i> Kullanıcı İstatistikleri</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">
                                    <h4><i class="fas fa-ad"></i> İlan Sayısı</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <h2><?php echo $ilan_sayisi; ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="panel panel-success">
                                <div class="panel-heading text-center">
                                    <h4><i class="fas fa-project-diagram"></i> Proje Sayısı</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <h2><?php echo $proje_sayisi; ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading text-center">
                                    <h4><i class="fas fa-envelope"></i> Mesaj Sayısı</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <h2><?php echo $mesaj_sayisi; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son Eklenen İlanlar -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-ad"></i> Son Eklenen İlanlar</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>İlan Başlığı</th>
                                    <th>Kategori</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($ilanlar) > 0) {
                                    foreach ($son_ilanlar as $ilan) {
                                        // Onay durumunu görsel olarak gösterme
                                        $durum = "";
                                        if ($ilan['onay'] == "1") {
                                            $durum = '<span class="label label-success"><i class="fas fa-check-circle"></i> Onaylı</span>';
                                        } else {
                                            $durum = '<span class="label label-warning"><i class="fas fa-clock"></i> Onay Bekliyor</span>';
                                        }

                                        echo '<tr>';
                                        echo '<td><a href="/proje/admin/ilan/guncelle.php?id=' . $ilan['id'] . '">'
                                            . htmlspecialchars($ilan['urunadi']) . '</a></td>';
                                        echo '<td>' . htmlspecialchars($ilan['kategori_adi']) . '</td>';
                                        echo '<td>' . date('d.m.Y', strtotime($ilan['giris_tarihi'])) . '</td>';
                                        echo '<td>' . $durum . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">Henüz ilan eklenmemiş.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Son Eklenen Projeler -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fas fa-project-diagram"></i> Son Eklenen Projeler</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Proje Adı</th>
                                    <th>Kategori</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($son_projeler_sorgu->rowCount() > 0) {
                                    while ($proje = $son_projeler_sorgu->fetch(PDO::FETCH_ASSOC)) {
                                        // Onay durumunu görsel olarak gösterme
                                        $durum = "";
                                        if ($proje['onay'] == "1") {
                                            $durum = '<span class="label label-success"><i class="fas fa-check-circle"></i> Onaylı</span>';
                                        } else {
                                            $durum = '<span class="label label-warning"><i class="fas fa-clock"></i> Onay Bekliyor</span>';
                                        }

                                        echo '<tr>';
                                        echo '<td><a href="/proje/admin/projeler/guncelle.php?id=' . $proje['id'] . '">'
                                            . htmlspecialchars($proje['urunadi']) . '</a></td>';
                                        echo '<td>' . htmlspecialchars($proje['kategori_adi']) . '</td>';
                                        echo '<td>' . date('d.m.Y', strtotime($proje['giris_tarihi'])) . '</td>';
                                        echo '<td>' . $durum . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">Henüz proje eklenmemiş.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/footer.php'; ?>