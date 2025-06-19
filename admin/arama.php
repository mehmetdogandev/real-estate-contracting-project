<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-search"></i> Arama Sonuçları</h1>
    </div>

    <?php
    // Arama sorgusunu al
    $arama_terimi = isset($_GET['q']) ? trim($_GET['q']) : '';

    // Arama terimi boşsa uyarı ver
    if (empty($arama_terimi)) {
        echo '<div class="alert alert-warning">Lütfen bir arama terimi giriniz.</div>';
    } else {
        // Arama terimini güvenli hale getir
        $arama_terimi = '%' . $arama_terimi . '%';
        
        try {
            // İlanları ara
            $ilan_sorgu = $con->prepare("
                SELECT u.id, u.urunadi, u.fiyat, u.giris_tarihi, u.resim, k.kategoriadi, 
                       CASE WHEN e.id IS NOT NULL THEN 'Ev' ELSE 'Arsa' END as ilan_tipi 
                FROM urunler u 
                LEFT JOIN kategoriler k ON u.kategori_id = k.id
                LEFT JOIN evbilgi e ON u.id = e.ev_urun_id
                LEFT JOIN arsabilgi a ON u.id = a.arsa_urun_id
                WHERE u.urunadi LIKE ? OR u.aciklama LIKE ?
                ORDER BY u.giris_tarihi DESC
            ");
            $ilan_sorgu->bindParam(1, $arama_terimi, PDO::PARAM_STR);
            $ilan_sorgu->bindParam(2, $arama_terimi, PDO::PARAM_STR);
            $ilan_sorgu->execute();
            $ilan_sonuclari = $ilan_sorgu->fetchAll(PDO::FETCH_ASSOC);
            
            // Projeleri ara
            $proje_sorgu = $con->prepare("
                SELECT p.id, p.urunadi, p.fiyat, p.giris_tarihi, p.resim, pk.kategoriadi
                FROM projeler p
                LEFT JOIN projeler_kategoriler pk ON p.kategori_id = pk.id
                WHERE p.urunadi LIKE ? OR p.aciklama LIKE ?
                ORDER BY p.giris_tarihi DESC
            ");
            $proje_sorgu->bindParam(1, $arama_terimi, PDO::PARAM_STR);
            $proje_sorgu->bindParam(2, $arama_terimi, PDO::PARAM_STR);
            $proje_sorgu->execute();
            $proje_sonuclari = $proje_sorgu->fetchAll(PDO::FETCH_ASSOC);
            
            // Kullanıcıları ara
            $kullanici_sorgu = $con->prepare("
                SELECT id, kadi, adsoyad, eposta, tel_no, profil_resmi, onay
                FROM kullanicilar
                WHERE kadi LIKE ? OR adsoyad LIKE ? OR eposta LIKE ?
                ORDER BY adsoyad ASC
            ");
            $kullanici_sorgu->bindParam(1, $arama_terimi, PDO::PARAM_STR);
            $kullanici_sorgu->bindParam(2, $arama_terimi, PDO::PARAM_STR);
            $kullanici_sorgu->bindParam(3, $arama_terimi, PDO::PARAM_STR);
            $kullanici_sorgu->execute();
            $kullanici_sonuclari = $kullanici_sorgu->fetchAll(PDO::FETCH_ASSOC);
            
            // Sonuçları göster
            $toplam_sonuc = count($ilan_sonuclari) + count($proje_sonuclari) + count($kullanici_sonuclari);
            
            echo '<div class="alert alert-info">';
            echo '<strong>"' . htmlspecialchars(trim($_GET['q'])) . '"</strong> için toplam ' . $toplam_sonuc . ' sonuç bulundu.';
            echo '</div>';
            
            // Tabs oluştur
            ?>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#ilanlar" aria-controls="ilanlar" role="tab" data-toggle="tab">
                    <i class="fas fa-ad"></i> İlanlar <span class="badge"><?php echo count($ilan_sonuclari); ?></span>
                </a></li>
                <li role="presentation"><a href="#projeler" aria-controls="projeler" role="tab" data-toggle="tab">
                    <i class="fas fa-project-diagram"></i> Projeler <span class="badge"><?php echo count($proje_sonuclari); ?></span>
                </a></li>
                <li role="presentation"><a href="#kullanicilar" aria-controls="kullanicilar" role="tab" data-toggle="tab">
                    <i class="fas fa-users"></i> Kullanıcılar <span class="badge"><?php echo count($kullanici_sonuclari); ?></span>
                </a></li>
            </ul>
            
            <div class="tab-content">
                <!-- İlanlar Tab -->
                <div role="tabpanel" class="tab-pane active" id="ilanlar">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>İlan Sonuçları</h3>
                            <?php if (count($ilan_sonuclari) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Resim</th>
                                                <th>İlan Adı</th>
                                                <th>Kategori</th>
                                                <th>Tür</th>
                                                <th>Fiyat</th>
                                                <th>Tarih</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ilan_sonuclari as $ilan): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($ilan['resim'])): ?>
                                                            <img src="/content/images/<?php echo htmlspecialchars($ilan['resim']); ?>" style="max-width: 50px; max-height: 50px;" class="img-thumbnail">
                                                        <?php else: ?>
                                                            <img src="/content/images/no-image.png" style="max-width: 50px; max-height: 50px;" class="img-thumbnail">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($ilan['urunadi']); ?></td>
                                                    <td><?php echo htmlspecialchars($ilan['kategoriadi']); ?></td>
                                                    <td><?php echo htmlspecialchars($ilan['ilan_tipi']); ?></td>
                                                    <td><?php echo number_format($ilan['fiyat'], 2, ',', '.') . ' TL'; ?></td>
                                                    <td><?php echo date('d.m.Y', strtotime($ilan['giris_tarihi'])); ?></td>
                                                    <td>
                                                        <a href="/admin/ilan/duzelt.php?id=<?php echo $ilan['id']; ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Düzenle
                                                        </a>
                                                        <a href="/admin/ilan/detay.php?id=<?php echo $ilan['id']; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Detay
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Bu arama kriterine uygun ilan bulunamadı.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Projeler Tab -->
                <div role="tabpanel" class="tab-pane" id="projeler">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Proje Sonuçları</h3>
                            <?php if (count($proje_sonuclari) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Resim</th>
                                                <th>Proje Adı</th>
                                                <th>Kategori</th>
                                                <th>Fiyat</th>
                                                <th>Tarih</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proje_sonuclari as $proje): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($proje['resim'])): ?>
                                                            <img src="/content/images/<?php echo htmlspecialchars($proje['resim']); ?>" style="max-width: 50px; max-height: 50px;" class="img-thumbnail">
                                                        <?php else: ?>
                                                            <img src="/content/images/no-image.png" style="max-width: 50px; max-height: 50px;" class="img-thumbnail">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($proje['urunadi']); ?></td>
                                                    <td><?php echo htmlspecialchars($proje['kategoriadi']); ?></td>
                                                    <td><?php echo number_format($proje['fiyat'], 2, ',', '.') . ' TL'; ?></td>
                                                    <td><?php echo date('d.m.Y', strtotime($proje['giris_tarihi'])); ?></td>
                                                    <td>
                                                        <a href="/admin/projeler/duzelt.php?id=<?php echo $proje['id']; ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Düzenle
                                                        </a>
                                                        <a href="/admin/projeler/detay.php?id=<?php echo $proje['id']; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Detay
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Bu arama kriterine uygun proje bulunamadı.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Kullanıcılar Tab -->
                <div role="tabpanel" class="tab-pane" id="kullanicilar">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Kullanıcı Sonuçları</h3>
                            <?php if (count($kullanici_sonuclari) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Profil</th>
                                                <th>Kullanıcı Adı</th>
                                                <th>Ad Soyad</th>
                                                <th>E-posta</th>
                                                <th>Telefon</th>
                                                <th>Durum</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kullanici_sonuclari as $kullanici): ?>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                        $profil_resmi = "/admin/profil/profil-image/default-profile.jpg";
                                                        if (!empty($kullanici['profil_resmi'])) {
                                                            $profil_resmi = $kullanici['profil_resmi'];
                                                        }
                                                        ?>
                                                        <img src="<?php echo htmlspecialchars($profil_resmi); ?>" style="width: 40px; height: 40px;" class="img-circle">
                                                    </td>
                                                    <td><?php echo htmlspecialchars($kullanici['kadi']); ?></td>
                                                    <td><?php echo htmlspecialchars($kullanici['adsoyad']); ?></td>
                                                    <td><?php echo htmlspecialchars($kullanici['eposta']); ?></td>
                                                    <td><?php echo htmlspecialchars($kullanici['tel_no']); ?></td>
                                                    <td>
                                                        <?php 
                                                        if ($kullanici['onay'] == '1') {
                                                            echo '<span class="label label-success">Admin</span>';
                                                        } elseif ($kullanici['onay'] == '2') {
                                                            echo '<span class="label label-primary">Onaylı Üye</span>';
                                                        } else {
                                                            echo '<span class="label label-warning">Onay Bekliyor</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="/admin/profil/admin_profil.php?kadi=<?php echo $kullanici['kadi']; ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-user-circle"></i> Profil
                                                        </a>
                                                        <a href="/admin/kullanici/duzelt.php?id=<?php echo $kullanici['id']; ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Düzenle
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Bu arama kriterine uygun kullanıcı bulunamadı.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Arama işlemi sırasında bir hata oluştu: ' . $e->getMessage() . '</div>';
        }
    }
    ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>