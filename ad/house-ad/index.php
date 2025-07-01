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
       $required_fields = ['urunadi', 'aciklama', 'fiyat', 'kategori_id', 'il_id', 'ilce_id', 'ev_metrekare', 'oda_sayisi'];
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
           $evarsa_id = 1; // Ev
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
               
               // Ev bilgilerini ekle
               $sorgu2 = "INSERT INTO evbilgi SET ev_urun_id=:ev_urun_id, ev_tipi=:ev_tipi, ev_metrekare=:ev_metrekare, 
                         oda_sayisi=:oda_sayisi, bina_yasi=:bina_yasi, kat_sayisi=:kat_sayisi, isitma=:isitma, 
                         banyo_sayisi=:banyo_sayisi, esyali=:esyali, kullanim_durumu=:kullanim_durumu, 
                         site_icinde=:site_icinde, aidat=:aidat, ev_krediye_uygun=:ev_krediye_uygun, 
                         ev_kimden=:ev_kimden, ev_takas=:ev_takas";
               
               $stmt2 = $con->prepare($sorgu2);
               
               // Ev değişkenleri
               $ev_urun_id = $son_kayit_id;
               $ev_tipi = htmlspecialchars(strip_tags($_POST['ev_tipi'] ?? 'Apartman Dairesi'));
               $ev_metrekare = htmlspecialchars(strip_tags($_POST['ev_metrekare']));
               $oda_sayisi = htmlspecialchars(strip_tags($_POST['oda_sayisi']));
               $bina_yasi = htmlspecialchars(strip_tags($_POST['bina_yasi'] ?? '0'));
               $kat_sayisi = htmlspecialchars(strip_tags($_POST['kat_sayisi'] ?? '1'));
               $isitma = htmlspecialchars(strip_tags($_POST['isitma'] ?? 'Doğal Gaz'));
               $banyo_sayisi = htmlspecialchars(strip_tags($_POST['banyo_sayisi'] ?? '1'));
               $esyali = htmlspecialchars(strip_tags($_POST['esyali'] ?? 'Hayır'));
               $kullanim_durumu = htmlspecialchars(strip_tags($_POST['kullanim_durumu'] ?? 'Hayır'));
               $site_icinde = htmlspecialchars(strip_tags($_POST['site_icinde'] ?? 'Hayır'));
               $aidat = str_replace([',', '.'], '', htmlspecialchars(strip_tags($_POST['aidat'] ?? '0')));
               $ev_krediye_uygun = htmlspecialchars(strip_tags($_POST['ev_krediye_uygun'] ?? 'Hayır'));
               $ev_kimden = $kullanici_id;
               $ev_takas = htmlspecialchars(strip_tags($_POST['ev_takas'] ?? 'Hayır'));
               
               // Parametreleri bağla
               $stmt2->bindParam(':ev_urun_id', $ev_urun_id);
               $stmt2->bindParam(':ev_tipi', $ev_tipi);
               $stmt2->bindParam(':ev_metrekare', $ev_metrekare);
               $stmt2->bindParam(':oda_sayisi', $oda_sayisi);
               $stmt2->bindParam(':bina_yasi', $bina_yasi);
               $stmt2->bindParam(':kat_sayisi', $kat_sayisi);
               $stmt2->bindParam(':isitma', $isitma);
               $stmt2->bindParam(':banyo_sayisi', $banyo_sayisi);
               $stmt2->bindParam(':esyali', $esyali);
               $stmt2->bindParam(':kullanim_durumu', $kullanim_durumu);
               $stmt2->bindParam(':site_icinde', $site_icinde);
               $stmt2->bindParam(':aidat', $aidat);
               $stmt2->bindParam(':ev_krediye_uygun', $ev_krediye_uygun);
               $stmt2->bindParam(':ev_kimden', $ev_kimden);
               $stmt2->bindParam(':ev_takas', $ev_takas);
               
               if ($stmt2->execute()) {
                   $alert_message = "Ev ilanınız başarıyla kaydedildi! Onay sürecinden sonra yayınlanacaktır.";
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
                   $alert_message = "Ev bilgileri kaydedilemedi.";
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
   /* House Ad Form Styles */
   .house-ad-container {
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
       background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
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
       background: linear-gradient(135deg, #ef4444, #dc2626);
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
       color: #ef4444;
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
       border-color: #ef4444;
       box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
       background: white;
       outline: none;
   }

   .form-control.error {
       border-color: #ef4444;
       background: #fef2f2;
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
       border-color: #ef4444;
       background: #fef2f2;
       color: #dc2626;
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

   /* Custom alert */
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
       background: linear-gradient(135deg, #ef4444, #dc2626);
       color: white;
   }

   .btn-next:hover {
       transform: translateY(-2px);
       box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
   }

   .btn-submit {
       background: linear-gradient(135deg, #10b981, #059669);
       color: white;
   }

   .btn-submit:hover {
       transform: translateY(-2px);
       box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
   }

   /* Price input */
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

   /* Room info display */
   .room-info {
       background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
       border: 1px solid #0ea5e9;
       border-radius: 12px;
       padding: 15px;
       margin-top: 10px;
       text-align: center;
       color: #0c4a6e;
       font-weight: 600;
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
       .house-ad-container {
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

<div class="house-ad-container">
   <div class="container">
       <div class="row justify-content-center">
           <div class="col-lg-10">
               <div class="form-card">
                   <!-- Form Header -->
                   <div class="form-header">
                       <div class="form-icon">
                           <i class="fas fa-home"></i>
                       </div>
                       <h2 class="form-title">Ev İlanı Ver</h2>
                       <p class="form-subtitle">Evinizi satmak veya kiralamak için detayları doldurun</p>
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
                       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id="houseAdForm">
                           
                           <!-- Step 1: Basic Information -->
                           <div class="form-section active" data-section="1">
                               <h3 class="section-title">
                                   <i class="fas fa-info-circle"></i>
                                   Temel Bilgiler
                               </h3>
                               
                               <div class="form-group">
                                   <label class="form-label required">İlan Başlığı</label>
                                   <input type="text" name="urunadi" class="form-control" placeholder="Örn: Merkez'de 3+1 Kiralık Daire" required>
                               </div>
                               
                               <div class="form-group">
                                   <label class="form-label required">Açıklama</label>
                                   <textarea name="aciklama" class="form-control" rows="4" placeholder="Eviniz hakkında detaylı bilgi verin..." required></textarea>
                               </div>
                               
                               <div class="form-row">
                                   <div class="form-group">
                                       <label class="form-label required">Fiyat</label>
                                       <div class="price-input">
                                           <input type="text" name="fiyat" class="form-control price-format" placeholder="Örn: 150000" required>
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

                           <!-- Step 2: Location & Property Details -->
                           <div class="form-section" data-section="2">
                               <h3 class="section-title">
                                   <i class="fas fa-map-marker-alt"></i>
                                   Konum & Mülk Bilgileri
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
                                       <label class="form-label required">Ev Tipi</label>
                                       <div class="custom-select">
                                           <select name="ev_tipi" class="form-control" required>
                                               <option value="">Ev Tipi Seçin</option>
                                             <option value="Apartman Dairesi">Apartman Dairesi</option>
                                               <option value="Villa">Villa</option>
                                               <option value="Müstakil Ev">Müstakil Ev</option>
                                               <option value="Dublex">Dublex</option>
                                               <option value="Köşk">Köşk</option>
                                               <option value="Bahçeli">Bahçeli</option>
                                           </select>
                                       </div>
                                   </div>
                                   
                                   <div class="form-group">
                                       <label class="form-label required">Metrekare</label>
                                       <input type="text" name="ev_metrekare" class="form-control number-format" placeholder="Örn: 120" required>
                                   </div>
                               </div>
                               
                               <div class="form-row">
                                   <div class="form-group">
                                       <label class="form-label required">Oda Sayısı</label>
                                       <div class="custom-select">
                                           <select name="oda_sayisi" class="form-control" id="oda_sayisi" required>
                                               <option value="">Oda Sayısı Seçin</option>
                                               <option value="1+0">1+0 (Stüdyo)</option>
                                               <option value="1+1">1+1</option>
                                               <option value="2+1">2+1</option>
                                               <option value="3+1">3+1</option>
                                               <option value="4+1">4+1</option>
                                               <option value="5+1">5+1</option>
                                               <option value="6+1">6+1</option>
                                               <option value="7+1">7+1</option>
                                               <option value="8+1">8+1</option>
                                               <option value="9+1">9+1</option>
                                               <option value="10+1">10+1</option>
                                           </select>
                                       </div>
                                       <div id="room_info" class="room-info" style="display: none;"></div>
                                   </div>
                                   
                                   <div class="form-group">
                                       <label class="form-label">Banyo Sayısı</label>
                                       <input type="number" name="banyo_sayisi" class="form-control" placeholder="Örn: 2" min="1" value="1">
                                   </div>
                               </div>
                           </div>

                           <!-- Step 3: Building Details -->
                           <div class="form-section" data-section="3">
                               <h3 class="section-title">
                                   <i class="fas fa-building"></i>
                                   Bina & Özellik Bilgileri
                               </h3>
                               
                               <div class="form-row">
                                   <div class="form-group">
                                       <label class="form-label">Bina Yaşı</label>
                                       <div class="custom-select">
                                           <select name="bina_yasi" class="form-control">
                                               <option value="0">Yeni (0)</option>
                                               <option value="1">1</option>
                                               <option value="2">2</option>
                                               <option value="3">3</option>
                                               <option value="4">4</option>
                                               <option value="5">5</option>
                                               <option value="6-10">6-10</option>
                                               <option value="11-15">11-15</option>
                                               <option value="16-20">16-20</option>
                                               <option value="21-25">21-25</option>
                                               <option value="26-30">26-30</option>
                                               <option value="31+">31+</option>
                                           </select>
                                       </div>
                                   </div>
                                   
                                   <div class="form-group">
                                       <label class="form-label">Kat Sayısı</label>
                                       <input type="number" name="kat_sayisi" class="form-control" placeholder="Hangi katta?" min="0" max="50">
                                   </div>
                               </div>
                               
                               <div class="form-row">
                                   <div class="form-group">
                                       <label class="form-label">Isıtma Sistemi</label>
                                       <div class="custom-select">
                                           <select name="isitma" class="form-control">
                                               <option value="Doğal Gaz">Doğal Gaz</option>
                                               <option value="Kombi">Kombi</option>
                                               <option value="Merkezi Sistem">Merkezi Sistem</option>
                                               <option value="Soba">Soba</option>
                                               <option value="Kömürlü Kalorifer">Kömürlü Kalorifer</option>
                                               <option value="Klima">Klima</option>
                                               <option value="Şömine">Şömine</option>
                                               <option value="Yerden Isıtma">Yerden Isıtma</option>
                                           </select>
                                       </div>
                                   </div>
                                   
                                   <div class="form-group">
                                       <label class="form-label">Aidat (Aylık)</label>
                                       <div class="price-input">
                                           <input type="text" name="aidat" class="form-control price-format" placeholder="Varsa aidat miktarı">
                                       </div>
                                   </div>
                               </div>
                               
                               <div class="form-row">
                                   <div class="form-group">
                                       <label class="form-label">Eşyalı mı?</label>
                                       <div class="custom-select">
                                           <select name="esyali" class="form-control">
                                               <option value="Hayır">Hayır</option>
                                               <option value="Evet">Evet</option>
                                               <option value="Kısmen">Kısmen</option>
                                           </select>
                                       </div>
                                   </div>
                                   
                                   <div class="form-group">
                                       <label class="form-label">Şu an kullanılıyor mu?</label>
                                       <div class="custom-select">
                                           <select name="kullanim_durumu" class="form-control">
                                               <option value="Hayır">Hayır (Boş)</option>
                                               <option value="Evet">Evet (Dolu)</option>
                                           </select>
                                       </div>
                                   </div>
                               </div>
                               
                               <div class="form-row">
                                   <div class="form-group">
                                       <label class="form-label">Site içerisinde mi?</label>
                                       <div class="custom-select">
                                           <select name="site_icinde" class="form-control">
                                               <option value="Hayır">Hayır</option>
                                               <option value="Evet">Evet</option>
                                           </select>
                                       </div>
                                   </div>
                                   
                                   <div class="form-group">
                                       <label class="form-label">Krediye uygun mu?</label>
                                       <div class="custom-select">
                                           <select name="ev_krediye_uygun" class="form-control">
                                               <option value="Evet">Evet</option>
                                               <option value="Hayır">Hayır</option>
                                           </select>
                                       </div>
                                   </div>
                               </div>
                               
                               <div class="form-group">
                                   <label class="form-label">Takas kabul ediyor musunuz?</label>
                                   <div class="custom-select">
                                       <select name="ev_takas" class="form-control">
                                           <option value="Hayır">Hayır</option>
                                           <option value="Evet">Evet</option>
                                       </select>
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
                                   Kaliteli fotoğraflar ilanınızın daha çok ilgi görmesini sağlar. Maksimum dosya boyutu: 2MB
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
                               
                               <div class="alert alert-warning mt-4">
                                   <i class="fas fa-lightbulb me-2"></i>
                                   <strong>Fotoğraf İpuçları:</strong> Iyi aydınlatma kullanın, odaları geniş açıdan çekin, temiz ve düzenli görüntüler seçin.
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
   
   // Room info display
   const odaSayisiSelect = document.getElementById('oda_sayisi');
   const roomInfoDiv = document.getElementById('room_info');
   
   if (odaSayisiSelect) {
       odaSayisiSelect.addEventListener('change', function() {
           const selectedValue = this.value;
           if (selectedValue) {
               const roomInfo = getRoomInfo(selectedValue);
               roomInfoDiv.innerHTML = roomInfo;
               roomInfoDiv.style.display = 'block';
           } else {
               roomInfoDiv.style.display = 'none';
           }
       });
   }
   
   function getRoomInfo(roomType) {
       const roomTypes = {
           '1+0': '1 oda, salon yok (stüdyo)',
           '1+1': '1 yatak odası + 1 salon',
           '2+1': '2 yatak odası + 1 salon',
           '3+1': '3 yatak odası + 1 salon',
           '4+1': '4 yatak odası + 1 salon',
           '5+1': '5 yatak odası + 1 salon',
           '6+1': '6 yatak odası + 1 salon',
           '7+1': '7 yatak odası + 1 salon',
           '8+1': '8 yatak odası + 1 salon',
           '9+1': '9 yatak odası + 1 salon',
           '10+1': '10 yatak odası + 1 salon'
       };
       
       return `<i class="fas fa-info-circle me-2"></i>${roomTypes[roomType] || 'Oda bilgisi'}`;
   }
   
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
   document.getElementById('houseAdForm').addEventListener('submit', function(e) {
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
   
   // Auto-calculate suggestions
   const metreSuareInput = document.querySelector('input[name="ev_metrekare"]');
   const fiyatInput = document.querySelector('input[name="fiyat"]');
   
   function suggestPriceRange() {
       const metreSuare = parseInt(metreSuareInput?.value.replace(/[^\d]/g, '') || 0);
       if (metreSuare > 50 && metreSuare < 500) {
           // Simple price suggestion based on square meter (this is just an example)
           const minPrice = metreSuare * 2000;
           const maxPrice = metreSuare * 5000;
           
           console.log(`${metreSuare}m² için önerilen fiyat aralığı: ${minPrice.toLocaleString('tr-TR')} - ${maxPrice.toLocaleString('tr-TR')} TL`);
       }
   }
   
   metreSuareInput?.addEventListener('blur', suggestPriceRange);
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