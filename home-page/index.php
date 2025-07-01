<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';


// aktif kayıt bilgilerini oku
try {
    // slider-1 için seçme sorgusunu hazırla
    $sorgu = "SELECT * FROM slider WHERE slider_k_durum='1'";
    $stmt = $con->prepare($sorgu);
    // slider-1 için sorguyu çalıştır
    $stmt->execute();
    //slider-2 için seçme sorgusunu hazırla
    $sorgu2 = "SELECT * FROM slider WHERE slider_k_durum='2'";
    $stmt2 = $con->prepare($sorgu2);
    //slider-2 için sorguyu çalıştır
    $stmt2->execute();
    //slider-3 için seçme sorgusunu hazırla
    $sorgu3 = "SELECT * FROM slider WHERE slider_k_durum='3'";
    $stmt3 = $con->prepare($sorgu3);
    //slider-3 için sorguyu çalıştır
    $stmt3->execute();

    // geriye dönen kayıt sayısı
    $sayi = $stmt->rowCount() + $stmt2->rowCount() + $stmt3->rowCount();
    $sayi1 = $stmt->rowCount();
    $sayi2 = $stmt2->rowCount();
    $sayi3 = $stmt3->rowCount();
}
// hatayı göster
catch (PDOException $exception) {
    die('HATA: ' . $exception->getMessage());
}
?>

<!-- CSS Styles -->
<style>
    /* Hero Slider Styles */
    .hero-slider {
        position: relative;
        height: 600px;
        overflow: hidden;
        border-radius: 0 0 50px 50px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .carousel-item {
        height: 600px;
        position: relative;
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(30, 64, 175, 0.7), rgba(59, 130, 246, 0.5));
        z-index: 1;
    }

    .carousel-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        text-align: center;
        width: 80%;
        max-width: 600px;
    }

    .carousel-caption h3 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: slideInUp 1s ease;
    }

    .carousel-caption p {
        font-size: 1.3rem;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        animation: slideInUp 1s ease 0.3s both;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        transition: all 0.3s ease;
    }

    .carousel-control-prev {
        left: 30px;
    }

    .carousel-control-next {
        right: 30px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-indicators {
        bottom: 30px;
    }

    .carousel-indicators li {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin: 0 8px;
        background-color: rgba(255, 255, 255, 0.5);
        border: 2px solid white;
        transition: all 0.3s ease;
    }

    .carousel-indicators .active {
        background-color: white;
        transform: scale(1.2);
    }

    /* Welcome Section */
    .welcome-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        position: relative;
    }

    .section-title {
        font-size: 3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 60px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 2px;
    }

    /* Features Section */
    .features-section {
        background: white;
        padding: 60px 0;
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        margin: -50px 15px 0;
        position: relative;
        z-index: 10;
    }

    .feature-item {
        text-align: center;
        padding: 30px 20px;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        transform: translateY(-10px);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
    }

    .feature-item:hover .feature-icon {
        transform: scale(1.1);
        box-shadow: 0 15px 40px rgba(59, 130, 246, 0.4);
    }

    .feature-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
    }

    .feature-description {
        color: #64748b;
        line-height: 1.6;
    }

    /* Projects & Listings Sections */
    .content-section {
        padding: 80px 0;
    }

    .content-section:nth-child(even) {
        background: #f8fafc;
    }

    /* Property Cards */
    .property-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 30px;
        border: none;
    }

    .property-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .property-image {
        position: relative;
        overflow: hidden;
        height: 280px;
    }

    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .property-card:hover .property-image img {
        transform: scale(1.1);
    }

    .property-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .property-content {
        padding: 25px;
    }

    .property-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .property-location {
        color: #64748b;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .property-location i {
        margin-right: 8px;
        color: #3b82f6;
    }

    .property-type {
        background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
        padding: 8px 15px;
        border-radius: 15px;
        font-size: 14px;
        color: #475569;
        display: inline-block;
        margin-bottom: 15px;
    }

    .property-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }

    .favorite-btn {
        display: flex;
        align-items: center;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        padding: 8px 15px;
        border-radius: 10px;
    }

    .favorite-btn:hover {
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        text-decoration: none;
        transform: translateY(-2px);
    }

    .favorite-btn i {
        margin-right: 8px;
        font-size: 16px;
    }

    .property-price {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 10px 20px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 16px;
    }

    /* Alert Styles */
    .custom-alert {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 1px solid #fca5a5;
        color: #991b1b;
        border-radius: 15px;
        padding: 20px;
        margin: 50px 0;
        text-align: center;
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-slider {
            height: 400px;
            border-radius: 0 0 30px 30px;
        }

        .carousel-item {
            height: 400px;
        }

        .carousel-caption h3 {
            font-size: 2rem;
        }

        .carousel-caption p {
            font-size: 1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .features-section {
            margin: -30px 10px 0;
            border-radius: 20px;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 45px;
            height: 45px;
        }

        .carousel-control-prev {
            left: 15px;
        }

        .carousel-control-next {
            right: 15px;
        }
    }

    @media (max-width: 576px) {
        .content-section {
            padding: 50px 0;
        }

        .property-footer {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }
</style>

<?php
//kayıt varsa listele
if ($sayi > 0) {
?>
    <!-- Hero Slider -->
    <div id="heroCarousel" class="carousel slide hero-slider" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        
        <div class="carousel-inner">
            <?php
            //kayıt varsa listele
            if ($sayi1 > 0) {
                //slider 1 verilerin okunması
                while ($kayit = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($kayit);
            ?>
                    <div class="carousel-item active">
                        <img src="/content/images/<?php echo htmlspecialchars($slider_baglanti); ?>" alt="<?php echo htmlspecialchars($slider_baslik); ?>">
                        <div class="carousel-caption">
                            <h3 class="text-white"><?php echo htmlspecialchars($slider_baslik); ?></h3>
                            <p class="text-white"><?php echo htmlspecialchars($slider_aciklama); ?></p>
                        </div>
                    </div>
            <?php
                }
            }
            
            if ($sayi2 > 0) {
                //slider 2 verilerin okunması
                while ($kayit = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    extract($kayit);
                ?>
                    <div class="carousel-item <?php echo ($sayi1 == 0) ? 'active' : ''; ?>">
                        <img src="/content/images/<?php echo htmlspecialchars($slider_baglanti); ?>" alt="<?php echo htmlspecialchars($slider_baslik); ?>">
                        <div class="carousel-caption">
                            <h3 class="text-warning"><?php echo htmlspecialchars($slider_baslik); ?></h3>
                            <p class="text-white"><?php echo htmlspecialchars($slider_aciklama); ?></p>
                        </div>
                    </div>
            <?php
                }
            }
            
            if ($sayi3 > 0) {
                //slider 3 verilerin okunması
                while ($kayit = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                    extract($kayit);
                ?>
                    <div class="carousel-item <?php echo ($sayi1 == 0 && $sayi2 == 0) ? 'active' : ''; ?>">
                        <img src="/content/images/<?php echo htmlspecialchars($slider_baglanti); ?>" alt="<?php echo htmlspecialchars($slider_baslik); ?>">
                        <div class="carousel-caption">
                            <h3 class="text-success"><?php echo htmlspecialchars($slider_baslik); ?></h3>
                            <p class="text-white"><?php echo htmlspecialchars($slider_aciklama); ?></p>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Önceki</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Sonraki</span>
        </button>
    </div>
<?php
} else {
    echo "<div class='custom-alert'>
            <i class='fas fa-exclamation-triangle fa-2x mb-3'></i>
            <h4>Slider Bulunamadı</h4>
            <p>Sisteme kayıtlı herhangi bir slider görünmüyor veya geçici bir süreliğine gösterimi durdurulmuş.</p>
          </div>";
}
?>

<!-- Welcome Section -->
<section class="welcome-section">
    <div class="container">
        <div class="text-center animate-on-scroll">
            <h1 class="section-title">M&D EMLAK MÜTEAHHİT'e Hoş Geldiniz</h1>
            <p class="lead text-muted mb-0">Hayalinizdeki eve giden yolda güvenilir partneriniz</p>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-item animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h5 class="feature-title">Sorunsuz İşlemler</h5>
                    <p class="feature-description">Tüm işlemleriniz en geç 7 iş gününde profesyonel ekibimiz tarafından tamamlanır.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-item animate-on-scroll" style="animation-delay: 0.2s;">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="feature-title">Güvenli Ödeme</h5>
                    <p class="feature-description">Ödemelerinizi M&D Emlak güvencesiyle güvenle yapabilirsiniz.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-item animate-on-scroll" style="animation-delay: 0.4s;">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5 class="feature-title">Ücretsiz Taşınma</h5>
                    <p class="feature-description">İlk işleminize özel ücretsiz taşımacılık hizmetinden yararlanabilirsiniz.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section class="content-section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title animate-on-scroll">PROJELERİMİZ</h1>
            <p class="lead text-muted animate-on-scroll">En yeni ve prestijli projelerimizi keşfedin</p>
        </div>
        
        <div class="row">
            <?php
            try {
                // kayıt listeleme sorgusu
                $sorgu = 'SELECT projeler.*, il.sehir, ilce.ilce, evarsa.ilanTuru, projeler_kategoriler.kategoriadi
                FROM projeler
                LEFT JOIN il ON projeler.il_id=il.id
                LEFT JOIN ilce ON projeler.ilce_id=ilce.id
                LEFT JOIN evarsa ON projeler.evarsa_id=evarsa.id
                LEFT JOIN projeler_kategoriler ON projeler.kategori_id=projeler_kategoriler.id
                WHERE onay="1" 
                ORDER BY giris_tarihi desc LIMIT 0,3';
                
                $stmt = $con->prepare($sorgu);
                $stmt->execute();
                $veri = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($veri as $kayit) { ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="property-card animate-on-scroll">
                            <div class="property-image">
                                <div class="property-badge">PROJE</div>
                                <a href="projedetay.php?id=<?php echo $kayit['id'] ?>">
                                    <?php 
                                    if($kayit['resim']) {
                                        echo "<img src='/content/images/" . htmlspecialchars($kayit['resim']) . "' alt='" . htmlspecialchars($kayit['urunadi']) . "' />";
                                    } else {
                                        echo "<img src='/content/images/gorsel-yok.jpg' alt='Görsel Yok' />";
                                    }
                                    ?>
                                </a>
                            </div>
                            <div class="property-content">
                                <a href="projedetay.php?id=<?php echo $kayit['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <h4 class="property-title"><?php echo htmlspecialchars(mb_substr($kayit['urunadi'], 0, 30, 'UTF-8')) . (mb_strlen($kayit['urunadi'], 'UTF-8') > 30 ? '...' : ''); ?></h4>
                                </a>
                                <p class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($kayit['sehir']) . "/" . htmlspecialchars($kayit['ilce']) ?>
                                </p>
                                <span class="property-type">
                                    <i class="fas fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($kayit['kategoriadi']) . " " . htmlspecialchars($kayit['ilanTuru']) ?>
                                </span>
                            </div>
                            <div class="property-footer">
                                <div class="property-price">
                                    <i class="fas fa-lira-sign me-1"></i>
                                    <?php echo number_format($kayit['fiyat'], 0, ',', '.'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php }
            } catch(Exception $e) {
                echo '<div class="col-12"><div class="custom-alert">Projeler yüklenirken bir hata oluştu.</div></div>';
            }
            ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="projeler.php" class="btn btn-primary btn-lg">
                <i class="fas fa-building me-2"></i>Tüm Projeleri Görüntüle
            </a>
        </div>
    </div>
</section>

<!-- Listings Section -->
<section class="content-section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title animate-on-scroll">İLANLARIMIZ</h1>
            <p class="lead text-muted animate-on-scroll">En güncel gayrimenkul ilanlarımızı inceleyin</p>
        </div>
        
        <div class="row">
            <?php
            try {
                // kayıt listeleme sorgusu
                $sorgu = 'SELECT urunler.*, il.sehir, ilce.ilce, evarsa.ilanTuru, kategoriler.kategoriadi
                FROM urunler
                LEFT JOIN il ON urunler.il_id=il.id
                LEFT JOIN ilce ON urunler.ilce_id=ilce.id
                LEFT JOIN evarsa ON urunler.evarsa_id=evarsa.id
                LEFT JOIN kategoriler ON urunler.kategori_id=kategoriler.id
                WHERE onay="1" 
                ORDER BY giris_tarihi desc LIMIT 0,3';
                
                $stmt = $con->prepare($sorgu);
                $stmt->execute();
                $veri = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($veri as $kayit) { ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="property-card animate-on-scroll">
                            <div class="property-image">
                                <div class="property-badge"><?php echo strtoupper(htmlspecialchars($kayit['kategoriadi'])); ?></div>
                                <a href="urundetay.php?id=<?php echo $kayit['id'] ?>">
                                    <?php 
                                    if($kayit['resim']) {
                                        echo "<img src='/content/images/" . htmlspecialchars($kayit['resim']) . "' alt='" . htmlspecialchars($kayit['urunadi']) . "' />";
                                    } else {
                                        echo "<img src='/content/images/gorsel-yok.jpg' alt='Görsel Yok' />";
                                    }
                                    ?>
                                </a>
                            </div>
                            <div class="property-content">
                                <a href="urundetay.php?id=<?php echo $kayit['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <h4 class="property-title"><?php echo htmlspecialchars(mb_substr($kayit['urunadi'], 0, 30, 'UTF-8')) . (mb_strlen($kayit['urunadi'], 'UTF-8') > 30 ? '...' : ''); ?></h4>
                                </a>
                                <p class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($kayit['sehir']) . "/" . htmlspecialchars($kayit['ilce']) ?>
                                </p>
                                <span class="property-type">
                                    <i class="fas fa-home me-1"></i>
                                    <?php echo htmlspecialchars($kayit['kategoriadi']) . " " . htmlspecialchars($kayit['ilanTuru']) ?>
                                </span>
                            </div>
                            <div class="property-footer">
                                <a href="#" class="favorite-btn favori-ekle" id="<?php echo $kayit['id'] ?>">
                                    <i class="fas fa-heart"></i>
                                    Favorile
                                </a>
                                <div class="property-price">
                                    <i class="fas fa-lira-sign me-1"></i>
                                    <?php echo number_format($kayit['fiyat'], 0, ',', '.'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php }
            } catch(Exception $e) {
                echo '<div class="col-12"><div class="custom-alert">İlanlar yüklenirken bir hata oluştu.</div></div>';
            }
            ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="urunler.php" class="btn btn-primary btn-lg">
                <i class="fas fa-list me-2"></i>Tüm İlanları Görüntüle
            </a>
        </div>
    </div>
</section>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all elements with animate-on-scroll class
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Enhanced carousel
    const carousel = document.querySelector('#heroCarousel');
    if (carousel) {
        carousel.addEventListener('slide.bs.carousel', function () {
            // Add slide animation effects here if needed
        });
    }
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
 ?>