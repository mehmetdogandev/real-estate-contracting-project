<?php
// Oturum işlemlerini başlat
ob_start();
session_start();

// Favori session oluşturulmamışsa oluştur
$_SESSION['favori'] = isset($_SESSION['favori']) ? $_SESSION['favori'] : array();

// veritabanı bağlantı dosyasını dahil et
include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';

// aktif kayıt bilgilerini oku
try {
    // seçme sorgusunu hazırla
    $sorgu = "SELECT logo_baglanti FROM logo WHERE logo_k_durum=1";
    $stmt = $con->prepare($sorgu);

    // sorguyu çalıştır
    $stmt->execute();

    // okunan kayıt bilgilerini bir değişkene kaydet
    $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

    // formu dolduracak değişken bilgileri
    $logo_baglanti =$kayit['logo_baglanti'];

}
// hatayı göster
catch (PDOException $exception) {
    die('HATA: ' . $exception->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emlak & Müteahhit - Premium Gayrimenkul Çözümleri</title>
    
    <!-- CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }

        .top-bar a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .top-bar a:hover {
            color: #fbbf24;
            text-decoration: none;
        }

        .btn-warning-custom {
            background: linear-gradient(45deg, #f59e0b, #fbbf24);
            border: none;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-warning-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        /* Main Header */
        .main-header {
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand img {
            height: 50px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            padding: 10px 20px 10px 45px;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .search-btn {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        /* Navigation Menu */
        .navbar-nav .nav-link {
            color: #334155 !important;
            font-weight: 500;
            padding: 12px 20px !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #3b82f6 !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after {
            width: 80%;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 15px 0;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 10px 25px;
            transition: all 0.3s ease;
            color: #334155;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #3b82f6;
            transform: translateX(5px);
        }

        /* Favorites */
        .favorites-link {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .favorites-link:hover {
            color: #fbbf24;
            transform: translateY(-1px);
        }

        .heart-icon {
            position: relative;
            margin-right: 8px;
        }

        .badge-custom {
            background: linear-gradient(45deg, #ef4444, #f87171);
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 5px;
        }

        /* Mobile Menu Toggle */
        .navbar-toggler {
            border: none;
            padding: 5px 10px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .search-container {
                margin: 15px 0;
                max-width: 100%;
            }
            
            .top-bar .d-flex {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .top-bar {
                font-size: 12px;
                padding: 10px 0;
            }
            
            .navbar-brand img {
                height: 40px;
            }
        }

        /* Animation */
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

        .main-header {
            animation: fadeInUp 0.6s ease;
        }
    </style>
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <?php if (!isset($_SESSION["kullanici_loginkey"]) || empty($_SESSION["kullanici_loginkey"])) { ?>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-user-plus fa-sm"></i>
                                <a href="/register/">Kayıt Ol</a>
                                <span class="mx-2">|</span>
                                <i class="fas fa-sign-in-alt fa-sm"></i>
                                <a href="/login-page/">Giriş Yap</a>
                            </div>
                            <a class="btn btn-warning-custom" href="/register/?islem=girisYokilanver">
                                <i class="fas fa-plus fa-sm me-1"></i>Ücretsiz İlan Ver
                            </a>
                        <?php } else { ?>
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <a href="/profil.php"><i class="fas fa-user fa-sm me-1"></i>Profil</a>
                                <a href="/ilanlarim.php"><i class="fas fa-list fa-sm me-1"></i>İlanlarım</a>
                                <a href="/mesajlarim.php"><i class="fas fa-envelope fa-sm me-1"></i>Mesajlarım</a>
                                <a class="btn btn-warning-custom" href="/ad/">
                                    <i class="fas fa-plus fa-sm me-1"></i>Ücretsiz İlan Ver
                                </a>
                                <a href="/login-page/?cikis=1"><i class="fas fa-sign-out-alt fa-sm me-1"></i>Çıkış</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 text-end">
                    <a href="/my-favorites/" class="favorites-link">
                        <div class="heart-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        Favoriler
                        <span class="badge-custom">
                            <?php
                            if (isset($_SESSION['favori']) && !empty($_SESSION['favori'])) {
                                echo count($_SESSION['favori']);
                            } else {
                                echo 0;
                            }
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <nav class="navbar navbar-expand-lg main-header">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/home-page/">
                <img src="/content/images/<?php echo $logo_baglanti; ?>" alt="Emlak Logo" class="img-fluid">
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Bar -->
                <div class="mx-auto">
                    <form class="d-flex search-container" action="/urunler.php" method="get">
                        <div class="position-relative flex-grow-1 me-2">
                            <i class="fas fa-search search-icon"></i>
                            <input class="search-input" type="search" placeholder="Konum, mahalle veya ilan no ile ara..." name="aranan">
                        </div>
                        <button class="search-btn" type="submit">
                            <i class="fas fa-search me-1"></i>Ara
                        </button>
                    </form>
                </div>

                <!-- Main Menu -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/home-page/">
                            <i class="fas fa-home me-1"></i>Anasayfa
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-building me-1"></i>Çalışmalarımız
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/projeler.php">
                                <i class="fas fa-city me-2"></i>Projeler
                            </a></li>
                            <li><a class="dropdown-item" href="/urunler.php">
                                <i class="fas fa-home me-2"></i>İlanlar
                            </a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-handshake me-1"></i>Bizden Olun
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/register/">
                                <i class="fas fa-chart-line me-2"></i>Pazarlamacımız Olun
                            </a></li>
                            <li><a class="dropdown-item" href="/register/">
                                <i class="fas fa-user-plus me-2"></i>Müşterimiz Olun
                            </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/hakkimizda.php">
                            <i class="fas fa-info-circle me-1"></i>Hakkımızda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/blog.php">
                            <i class="fas fa-blog me-1"></i>Blog
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Ortaklarımız.php">
                            <i class="fas fa-users me-1"></i>Ortaklarımız
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add active class to current page
        const currentLocation = location.pathname;
        const menuItems = document.querySelectorAll('.navbar-nav .nav-link');
        
        menuItems.forEach(item => {
            if(item.getAttribute('href') === currentLocation.split('/').pop()){
                item.classList.add('active');
            }
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 100) {
                header.style.backdropFilter = 'blur(10px)';
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            } else {
                header.style.backdropFilter = 'none';
                header.style.backgroundColor = 'white';
            }
        });
    </script>
</body>
</html>