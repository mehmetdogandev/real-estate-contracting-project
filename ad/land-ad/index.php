<?php
include $_SERVER['DOCUMENT_ROOT'] . "/header.php";

// Oturum kontrolü
if (!isset($_SESSION["kullanici_loginkey"]) || $_SESSION["kullanici_loginkey"] == "") {
    header("Location: /login-page/?islem=girisYokilanver");
    exit();
}

// Alert mesajları için değişkenler
$alert_message = "";
$alert_type = "";

try {
    // Kullanıcı ID'sini al
    $sorgu = "SELECT id, adsoyad, onay FROM kullanicilar WHERE eposta = ? LIMIT 0,1";
    $stmt = $con->prepare($sorgu);
    $stmt->bindParam(1, $_SESSION["kullanici_loginkey"]);
    $stmt->execute();
    $kullanici_bilgi = $stmt->fetch(PDO::FETCH_ASSOC);
    $kullanici_id = $kullanici_bilgi['id'];
    
    // Hesap onay kontrolü
    if ($kullanici_bilgi['onay'] == 0) {
        $alert_message = "Hesabınız henüz onaylanmamış. İlan verebilmek için hesap onayınızın tamamlanmasını bekleyin.";
        $alert_type = "warning";
    }
}
catch (PDOException $exception) {
    die('HATA: ' . $exception->getMessage());
}

// Form işleme
if ($_POST && $kullanici_bilgi['onay'] != 0) {
    try {
        // Dosya yükleme fonksiyonu
        function uploadFile($fileKey, $allowedTypes = ["jpg", "jpeg", "png", "gif"], $maxSize = 2048000) {
            if (empty($_FILES[$fileKey]["name"])) {
                return ["success" => true, "filename" => "", "message" => ""];
            }
            
            $filename = uniqid() . "-" . basename($_FILES[$fileKey]["name"]);
            $filename = htmlspecialchars(strip_tags($filename));
            $targetPath = "content/images/" . $filename;
            $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
            
            // Dosya türü kontrolü
            if (!in_array($fileType, $allowedTypes)) {
                return ["success" => false, "filename" => "", "message" => "Sadece " . implode(", ", $allowedTypes) . " türündeki dosyalar yüklenebilir."];
            }
            
            // Dosya boyutu kontrolü
            if ($_FILES[$fileKey]['size'] > $maxSize) {
                return ["success" => false, "filename" => "", "message" => "Dosya boyutu " . ($maxSize/1024/1024) . " MB sınırını aşamaz."];
            }
            
            // Dosya var mı kontrolü
            if (file_exists($targetPath)) {
                return ["success" => false, "filename" => "", "message" => "Aynı isimde başka bir dosya var."];
            }
            
            // Dosyayı yükle
            if (move_uploaded_file($_FILES[$fileKey]["tmp_name"], $targetPath)) {
                return ["success" => true, "filename" => $filename, "message" => "Dosya başarıyla yüklendi."];
            } else {
                return ["success" => false, "filename" => "", "message" => "Dosya yüklenirken hata oluştu."];
            }
        }
        
        // Form validasyonu
        $required_fields = ['urunadi', 'aciklama', 'fiyat', 'kategori_id', 'il_id', 'ilce_id', 'arsa_metrekare'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            $alert_message = "Lütfen tüm zorunlu alanları doldurun.";
            $alert_type = "danger";
        } else {
            // Dosyaları yükle
            $upload_results = [];
            $upload_results['resim'] = uploadFile('resim');
            $upload_results['resim_iki'] = uploadFile('resim_iki');
            $upload_results['resim_uc'] = uploadFile('resim_uc');
            $upload_results['resim_dort'] = uploadFile('resim_dort');
            
            // Ana ürün kaydını ekle
            $sorgu = "INSERT INTO urunler SET urunadi=:urunadi, il_id=:il_id, ilce_id=:ilce_id, evarsa_id=:evarsa_id, 
                     aciklama=:aciklama, fiyat=:fiyat, giris_tarihi=:giris_tarihi, resim=:resim, resim_iki=:resim_iki, 
                     resim_uc=:resim_uc, resim_dort=:resim_dort, kategori_id=:kategori_id";
            
            $stmt = $con->prepare($sorgu);
            
            // Post edilen değerler
            $urunadi = htmlspecialchars(strip_tags($_POST['urunadi']));
            $il_id = htmlspecialchars(strip_tags($_POST['il_id']));
            $ilce_id = htmlspecialchars(strip_tags($_POST['ilce_id']));
            $evarsa_id = 2; // Arsa
            $aciklama = htmlspecialchars(strip_tags($_POST['aciklama']));
            $fiyat = str_replace([',', '.'], '', htmlspecialchars(strip_tags($_POST['fiyat'])));
            $kategori_id = htmlspecialchars(strip_tags($_POST['kategori_id']));
            $giris_tarihi = date('Y-m-d H:i:s');
            
            // Parametreleri bağla
            $stmt->bindParam(':urunadi', $urunadi);
            $stmt->bindParam(':il_id', $il_id);
            $stmt->bindParam(':ilce_id', $ilce_id);
            $stmt->bindParam(':evarsa_id', $evarsa_id);
            $stmt->bindParam(':aciklama', $aciklama);
            $stmt->bindParam(':fiyat', $fiyat);
            $stmt->bindParam(':resim', $upload_results['resim']['filename']);
            $stmt->bindParam(':resim_iki', $upload_results['resim_iki']['filename']);
            $stmt->bindParam(':resim_uc', $upload_results['resim_uc']['filename']);
            $stmt->bindParam(':resim_dort', $upload_results['resim_dort']['filename']);
            $stmt->bindParam(':kategori_id', $kategori_id);
            $stmt->bindParam(':giris_tarihi', $giris_tarihi);
            
            if ($stmt->execute()) {
                $son_kayit_id = $con->lastInsertId();
                
                // Arsa bilgilerini ekle
                $sorgu2 = "INSERT INTO arsabilgi SET arsa_urun_id=:arsa_urun_id, imar_durumu=:imar_durumu, 
                          arsa_metrekare=:arsa_metrekare, metrekare_fiyat=:metrekare_fiyat, ada_no=:ada_no, 
                          parsel_no=:parsel_no, pafta_no=:pafta_no, emsal=:emsal, tapu_durumu=:tapu_durumu,
                          kat_karsiligi=:kat_karsiligi, arsa_krediye_uygun=:arsa_krediye_uygun, 
                          arsa_kimden=:arsa_kimden, arsa_takas=:arsa_takas";
                
                $stmt2 = $con->prepare($sorgu2);
                
                // Arsa değişkenleri
                $arsa_urun_id = $son_kayit_id;
                $imar_durumu = htmlspecialchars(strip_tags($_POST['imar_durumu'] ?? ''));
                $arsa_metrekare = htmlspecialchars(strip_tags($_POST['arsa_metrekare']));
                $metrekare_fiyat = str_replace([',', '.'], '', htmlspecialchars(strip_tags($_POST['metrekare_fiyat'] ?? '')));
                $ada_no = htmlspecialchars(strip_tags($_POST['ada_no'] ?? ''));
                $parsel_no = htmlspecialchars(strip_tags($_POST['parsel_no'] ?? ''));
                $pafta_no = htmlspecialchars(strip_tags($_POST['pafta_no'] ?? ''));
                $emsal = htmlspecialchars(strip_tags($_POST['emsal'] ?? ''));
                $tapu_durumu = htmlspecialchars(strip_tags($_POST['tapu_durumu'] ?? ''));
                $kat_karsiligi = htmlspecialchars(strip_tags($_POST['kat_karsiligi'] ?? 'Hayır'));
                $arsa_krediye_uygun = htmlspecialchars(strip_tags($_POST['arsa_krediye_uygun'] ?? 'Hayır'));
                $arsa_kimden = $kullanici_id;
                $arsa_takas = htmlspecialchars(strip_tags($_POST['arsa_takas'] ?? 'Hayır'));
                
                // Parametreleri bağla
                $stmt2->bindParam(':arsa_urun_id', $arsa_urun_id);
                $stmt2->bindParam(':imar_durumu', $imar_durumu);
                $stmt2->bindParam(':arsa_metrekare', $arsa_metrekare);
                $stmt2->bindParam(':metrekare_fiyat', $metrekare_fiyat);
                $stmt2->bindParam(':ada_no', $ada_no);
                $stmt2->bindParam(':parsel_no', $parsel_no);
                $stmt2->bindParam(':pafta_no', $pafta_no);
                $stmt2->bindParam(':emsal', $emsal);
                $stmt2->bindParam(':tapu_durumu', $tapu_durumu);
                $stmt2->bindParam(':kat_karsiligi', $kat_karsiligi);
                $stmt2->bindParam(':arsa_krediye_uygun', $arsa_krediye_uygun);
                $stmt2->bindParam(':arsa_kimden', $arsa_kimden);
                $stmt2->bindParam(':arsa_takas', $arsa_takas);
                
                if ($stmt2->execute()) {
                    $alert_message = "Arsa ilanınız başarıyla kaydedildi! Onay sürecinden sonra yayınlanacaktır.";
                    $alert_type = "success";
                    
                    // Upload mesajları
                    $upload_messages = [];
                    foreach ($upload_results as $key => $result) {
                        if (!$result['success'] && !empty($result['message'])) {
                            $upload_messages[] = $result['message'];
                        }
                    }
                    
                    if (!empty($upload_messages)) {
                        $alert_message .= "<br><small>Dosya yükleme uyarıları: " . implode(", ", $upload_messages) . "</small>";
                    }
                } else {
                    $alert_message = "Arsa bilgileri kaydedilemedi.";
                    $alert_type = "danger";
                }
            } else {
                $alert_message = "İlan kaydedilemedi.";
                $alert_type = "danger";
            }
        }
    }
    catch (PDOException $exception) {
        $alert_message = "Sistem hatası: " . $exception->getMessage();
        $alert_type = "danger";
    }
}
?>

<!-- CSS Styles -->
<style>
    /* Land Ad Form Styles */
    .land-ad-container {
        min-height: 100vh;
        padding: 40px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
    }

    .form-header {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        color: white;
        padding: 30px 40px;
        text-align: center;
        position: relative;
    }

    .form-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: white;
        border-radius: 20px 20px 0 0;
    }

    .form-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        backdrop-filter: blur(10px);
    }

    .form-icon i {
        font-size: 2rem;
        color: white;
    }

    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-subtitle {
        opacity: 0.9;
        margin-bottom: 0;
    }

    .form-body {
        padding: 40px;
    }

    /* Multi-step form */
    .form-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
        position: relative;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        position: relative;
        margin: 0 20px;
        transition: all 0.3s ease;
    }

    .step.active {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
        transform: scale(1.1);
    }

    .step.completed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .step::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 40px;
        height: 2px;
        background: #e2e8f0;
        transform: translateY(-50%);
        z-index: -1;
    }

    .step:last-child::after {
        display: none;
    }

    .step.completed::after {
        background: #10b981;
    }

    /* Form sections */
    .form-section {
        display: none;
        animation: fadeInUp 0.5s ease;
    }

    .form-section.active {
        display: block;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        color: #06b6d4;
    }

    /* Form groups */
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .required::after {
        content: ' *';
        color: #ef4444;
        font-weight: 700;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8fafc;
        width: 100%;
    }

    .form-control:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
        background: white;
        outline: none;
    }

    .form-control.error {
        border-color: #ef4444;
        background: #fef2f2;
    }

    /* File upload */
    .file-upload {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 30px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
        color: #64748b;
        text-align: center;
    }

    .file-upload-label:hover {
        border-color: #06b6d4;
        background: #f0fdfa;
        color: #0891b2;
    }

    .file-upload-label i {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }

    .file-selected {
        border-color: #10b981;
        background: #f0fdf4;
        color: #059669;
    }

    /* Custom select */
    .custom-select {
        position: relative;
    }

    .custom-select select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }

    /* Alert styles */
    .custom-alert {
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.5s ease;
    }

    .custom-alert.alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .custom-alert.alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .custom-alert.alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    /* Navigation buttons */
    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-nav {
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-prev {
        background: #6b7280;
        color: white;
    }

    .btn-prev:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }

    .btn-next {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .btn-next:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    /* Price input formatting */
    .price-input {
        position: relative;
    }

    .price-input::before {
        content: '₺';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-weight: 600;
        z-index: 2;
    }

    .price-input input {
        padding-right: 35px;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .land-ad-container {
            padding: 20px 0;
        }

        .form-body {
            padding: 20px;
        }

        .form-header {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-steps {
            flex-wrap: wrap;
            gap: 10px;
        }

        .step {
            margin: 5px;
        }

        .step::after {
            display: none;
        }
    }
</style>

<div class="land-ad-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-card">
                    <!-- Form Header -->
                    <div class="form-header">
                        <div class="form-icon">
                            <i class="fas fa-map"></i>
                        </div>
                        <h2 class="form-title">Arsa İlanı Ver</h2>
                        <p class="form-subtitle">Arsanızı satmak veya kiralamak için detayları doldurun</p>
                    </div>

                    <!-- Form Body -->
                    <div class="form-body">
                        <!-- User Info -->
                        <?php if (isset($kullanici_bilgi['adsoyad'])): ?>
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-user-circle me-2"></i>
                            <strong><?php echo htmlspecialchars($kullanici_bilgi['adsoyad']); ?></strong> olarak ilan veriyorsunuz.
                        </div>
                        <?php endif; ?>

                        <!-- Alert Messages -->
                        <?php if ($alert_message): ?>
                            <div class="custom-alert alert-<?php echo $alert_type; ?>">
                                <?php if ($alert_type == 'success'): ?>
                                    <i class="fas fa-check-circle me-2"></i>
                                <?php elseif ($alert_type == 'danger'): ?>
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                <?php elseif ($alert_type == 'warning'): ?>
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php endif; ?>
                                <?php echo $alert_message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($kullanici_bilgi['onay'] != 0): ?>
                        <!-- Form Steps -->
                        <div class="form-steps">
                            <div class="step active" data-step="1">1</div>
                            <div class="step" data-step="2">2</div>
                            <div class="step" data-step="3">3</div>
                            <div class="step" data-step="4">4</div>
                        </div>

                        <!-- Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id="landAdForm">
                            
                            <!-- Step 1: Basic Information -->
                            <div class="form-section active" data-section="1">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    Temel Bilgiler
                                </h3>
                                
                                <div class="form-group">
                                    <label class="form-label required">İlan Başlığı</label>
                                    <input type="text" name="urunadi" class="form-control" placeholder="Örn: Merkez'de 500m² İmarlı Arsa" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label required">Açıklama</label>
                                    <textarea name="aciklama" class="form-control" rows="4" placeholder="Arsanız hakkında detaylı bilgi verin..." required></textarea>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label required">Toplam Fiyat</label>
                                        <div class="price-input">
                                            <input type="text" name="fiyat" class="form-control price-format" placeholder="Örn: 250000" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label required">Kategori</label>
                                        <div class="custom-select">
                                            <select name="kategori_id" class="form-control" required>
                                                <option value="">Kategori Seçin</option>
                                                <?php
                                                $sorgu = 'SELECT id, kategoriadi FROM kategoriler';
                                                $stmt = $con->prepare($sorgu);
                                                $stmt->execute();
                                                $kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($kategoriler as $kategori) { ?>
                                                    <option value="<?php echo $kategori["id"] ?>"><?php echo htmlspecialchars($kategori["kategoriadi"]) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Location -->
                            <div class="form-section" data-section="2">
                                <h3 class="section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Konum Bilgileri
                                </h3>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label required">İl</label>
                                        <div class="custom-select">
                                            <select name="il_id" id="il" class="form-control" required>
                                                <option value="">İl Seçiniz</option>
                                                <?php
                                                $sorgu = 'SELECT id, sehir FROM il ORDER BY sehir';
                                                $stmt = $con->prepare($sorgu);
                                                $stmt->execute();
                                                $iller = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($iller as $il) { ?>
                                                    <option value="<?php echo $il["id"] ?>"><?php echo htmlspecialchars($il["sehir"]) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label required">İlçe</label>
                                        <div class="custom-select">
                                            <select name="ilce_id" id="ilce" class="form-control" required>
                                                <option value="">İlçe seçmek için önce il seçiniz</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Ada No</label>
                                        <input type="text" name="ada_no" class="form-control" placeholder="Ada numarası">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Parsel No</label>
                                        <input type="text" name="parsel_no" class="form-control" placeholder="Parsel numarası">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Pafta No</label>
                                    <input type="text" name="pafta_no" class="form-control" placeholder="Pafta numarası">
                                </div>
                            </div>

                            <!-- Step 3: Land Details -->
                            <div class="form-section" data-section="3">
                                <h3 class="section-title">
                                    <i class="fas fa-ruler"></i>
                                    Arsa Detayları
                                </h3>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label required">Metrekare</label>
                                        <input type="text" name="arsa_metrekare" class="form-control number-format" placeholder="Örn: 500" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Metrekare Fiyatı</label>
                                        <div class="price-input">
                                            <input type="text" name="metrekare_fiyat" class="form-control price-format" placeholder="Metrekare başına fiyat">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">İmar Durumu</label>
                                        <div class="custom-select">
                                            <select name="imar_durumu" class="form-control">
                                                <option value="">İmar Durumu Seçin</option>
                                                <option value="İmarlı">İmarlı</option>
                                                <option value="İmarsız">İmarsız</option>
                                                <option value="Tarla">Tarla</option>
                                                <option value="Bahçe">Bahçe</option>
                                                <option value="Ticari">Ticari</option>
                                                <option value="Konut">Konut</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Emsal</label>
                                        <input type="text" name="emsal" class="form-control" placeholder="Örn: 1.2">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Tapu Durumu</label>
                                        <div class="custom-select">
                                            <select name="tapu_durumu" class="form-control">
                                                <option value="">Tapu Durumu Seçin</option>
                                                <option value="Kat Mülkiyeti">Kat Mülkiyeti</option>
                                                <option value="Kat İrtifakı">Kat İrtifakı</option>
                                                <option value="Arsa Tapusu">Arsa Tapusu</option>
                                                <option value="Hisseli Tapu">Hisseli Tapu</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Kat Karşılığı</label>
                                        <div class="custom-select">
                                            <select name="kat_karsiligi" class="form-control">
                                                <option value="Hayır">Hayır</option>
                                                <option value="Evet">Evet</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Krediye Uygun</label>
                                        <div class="custom-select">
                                            <select name="arsa_krediye_uygun" class="form-control">
                                                <option value="Hayır">Hayır</option>
                                                <option value="Evet">Evet</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Takas</label>
                                        <div class="custom-select">
                                            <select name="arsa_takas" class="form-control">
                                                <option value="Hayır">Hayır</option>
                                                <option value="Evet">Evet</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Photos -->
                            <div class="form-section" data-section="4">
                                <h3 class="section-title">
                                    <i class="fas fa-camera"></i>
                                    Fotoğraflar
                                </h3>
                                
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    En az 1 fotoğraf yüklemeniz önerilir. Maksimum dosya boyutu: 2MB
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Ana Fotoğraf</label>
                                        <div class="file-upload">
                                            <input type="file" name="resim" id="resim" accept="image/*">
                                            <label for="resim" class="file-upload-label">
                                                <div>
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <div>Ana fotoğrafı seçin</div>
                                                    <small>JPG, PNG, GIF (Max: 2MB)</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Fotoğraf 2</label>
                                        <div class="file-upload">
                                            <input type="file" name="resim_iki" id="resim_iki" accept="image/*">
                                            <label for="resim_iki" class="file-upload-label">
                                                <div>
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <div>2. fotoğrafı seçin</div>
                                                    <small>JPG, PNG, GIF (Max: 2MB)</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Fotoğraf 3</label>
                                        <div class="file-upload">
                                            <input type="file" name="resim_uc" id="resim_uc" accept="image/*">
                                            <label for="resim_uc" class="file-upload-label">
                                                <div>
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <div>3. fotoğrafı seçin</div>
                                                    <small>JPG, PNG, GIF (Max: 2MB)</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Fotoğraf 4</label>
                                        <div class="file-upload">
                                            <input type="file" name="resim_dort" id="resim_dort" accept="image/*">
                                            <label for="resim_dort" class="file-upload-label">
                                                <div>
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <div>4. fotoğrafı seçin</div>
                                                    <small>JPG, PNG, GIF (Max: 2MB)</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="form-navigation">
                                <button type="button" class="btn-nav btn-prev" id="prevBtn" style="display: none;">
                                    <i class="fas fa-arrow-left me-2"></i>Önceki
                                </button>
                                <div></div>
                                <button type="button" class="btn-nav btn-next" id="nextBtn">
                                    Sonraki<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button type="submit" class="btn-nav btn-submit" id="submitBtn" style="display: none;">
                                    <i class="fas fa-check me-2"></i>İlanı Yayınla
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    
    // Multi-step form navigation
    function showStep(step) {
        // Hide all sections
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Show current section
        document.querySelector(`[data-section="${step}"]`).classList.add('active');
        
        // Update step indicators
        document.querySelectorAll('.step').forEach((stepEl, index) => {
            stepEl.classList.remove('active', 'completed');
            if (index + 1 < step) {
                stepEl.classList.add('completed');
            } else if (index + 1 === step) {
                stepEl.classList.add('active');
            }
        });
        
        // Update navigation buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        
        prevBtn.style.display = step === 1 ? 'none' : 'block';
        nextBtn.style.display = step === totalSteps ? 'none' : 'block';
        submitBtn.style.display = step === totalSteps ? 'block' : 'none';
    }
    
    // Next button
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });
    
    // Previous button
    document.getElementById('prevBtn').addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });
    
    // Step validation
    function validateStep(step) {
        const section = document.querySelector(`[data-section="${step}"]`);
        const requiredFields = section.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        if (!isValid) {
            alert('Lütfen tüm zorunlu alanları doldurun.');
        }
        
        return isValid;
    }
    
    // Price formatting
    function formatPrice(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('tr-TR');
        }
        input.value = value;
    }
    
    // Number formatting
    function formatNumber(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('tr-TR');
        }
        input.value = value;
    }
    
    // Apply formatting to price inputs
    document.querySelectorAll('.price-format').forEach(input => {
        input.addEventListener('input', function() {
            formatPrice(this);
        });
        
        input.addEventListener('blur', function() {
            formatPrice(this);
        });
    });
    
    // Apply formatting to number inputs
    document.querySelectorAll('.number-format').forEach(input => {
        input.addEventListener('input', function() {
            formatNumber(this);
        });
    });
    
    // File upload handling
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const fileName = this.files[0]?.name;
            
            if (fileName) {
                label.classList.add('file-selected');
                label.querySelector('div').innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <div>${fileName}</div>
                    <small>Dosya seçildi</small>
                `;
            } else {
                label.classList.remove('file-selected');
                // Reset to original content
                const originalContent = label.getAttribute('data-original') || label.innerHTML;
                label.innerHTML = originalContent;
            }
        });
        
        // Store original label content
        const label = input.nextElementSibling;
        label.setAttribute('data-original', label.innerHTML);
    });
    
    // Real-time validation
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            if (this.hasAttribute('required') && this.value.trim()) {
                this.classList.remove('error');
            }
        });
    });
    
    // Form submission
    document.getElementById('landAdForm').addEventListener('submit', function(e) {
        if (!validateStep(currentStep)) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>İlan Kaydediliyor...';
        submitBtn.disabled = true;
        
        // Clean price values before submission
        document.querySelectorAll('.price-format, .number-format').forEach(input => {
            input.value = input.value.replace(/[^\d]/g, '');
        });
    });
    
    // Auto-calculate price per square meter
    const totalPriceInput = document.querySelector('input[name="fiyat"]');
    const squareMeterInput = document.querySelector('input[name="arsa_metrekare"]');
    const pricePerMeterInput = document.querySelector('input[name="metrekare_fiyat"]');
    
    function calculatePricePerMeter() {
        const totalPrice = parseInt(totalPriceInput.value.replace(/[^\d]/g, '') || 0);
        const squareMeter = parseInt(squareMeterInput.value.replace(/[^\d]/g, '') || 0);
        
        if (totalPrice && squareMeter) {
            const pricePerMeter = Math.round(totalPrice / squareMeter);
            pricePerMeterInput.value = pricePerMeter.toLocaleString('tr-TR');
        }
    }
    
    totalPriceInput?.addEventListener('blur', calculatePricePerMeter);
    squareMeterInput?.addEventListener('blur', calculatePricePerMeter);
});

// İl-İlçe AJAX
$(document).ready(function() {
    $("#il").change(function() {
        var ilid = $(this).val();
        if (ilid) {
            $.ajax({
                type: "POST",
                url: "/content/ajax.php",
                data: {"il": ilid},
                success: function(e) {
                    $("#ilce").html(e);
                },
                error: function() {
                    $("#ilce").html('<option value="">İlçe yüklenemedi</option>');
                }
            });
        } else {
            $("#ilce").html('<option value="">İlçe seçmek için önce il seçiniz</option>');
        }
    });
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/footer.php"; ?>