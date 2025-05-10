<?php
session_start();
if ($_SESSION["loginkey"] == "") {
    // oturum açılmamışsa login.php sayfasına git
    header("Location: /proje/admin/login.php");
}
include $_SERVER['DOCUMENT_ROOT'] . '/proje/config/vtabani.php';
// Okunmamış mesaj sayısını çekme
$bildirim_sorgu = $con->prepare("SELECT COUNT(*) as sayi FROM kullanicilar_mesaj WHERE k_msj_kime = :kime");
$bildirim_sorgu->bindParam(':kime', $_SESSION["loginkey"], PDO::PARAM_STR);
$bildirim_sorgu->execute();
$bildirim = $bildirim_sorgu->fetch(PDO::FETCH_ASSOC);
$bildirim_sayisi = $bildirim['sayi'];
// Onay bekleyen içerikleri çekme (ilanlar, projeler ve kullanıcılar)
$onay_ilan_sorgu = $con->prepare("SELECT COUNT(*) as sayi FROM urunler WHERE onay = '0'");
$onay_ilan_sorgu->execute();
$onay_ilan = $onay_ilan_sorgu->fetch(PDO::FETCH_ASSOC);
$onay_ilan_bekleyen = $onay_ilan['sayi'];
$onay_proje_sorgu = $con->prepare("SELECT COUNT(*) as sayi FROM projeler WHERE onay = '0'");
$onay_proje_sorgu->execute();
$onay_proje = $onay_proje_sorgu->fetch(PDO::FETCH_ASSOC);
$onay_proje_bekleyen = $onay_proje['sayi'];
$onay_kullanici_sorgu = $con->prepare("SELECT COUNT(*) as sayi FROM kullanicilar WHERE onay = '0'");
$onay_kullanici_sorgu->execute();
$onay_kullanici = $onay_kullanici_sorgu->fetch(PDO::FETCH_ASSOC);
$onay_kullanici_bekleyen = $onay_kullanici['sayi'];
// Toplam onay bekleyen sayısı
$onay_bekleyen = $onay_ilan_bekleyen + $onay_proje_bekleyen + $onay_kullanici_bekleyen;
// Kullanıcı bilgilerini çekme
$kullanici_sorgu = $con->prepare("SELECT * FROM kullanicilar WHERE kadi = :kadi");
$kullanici_sorgu->bindParam(':kadi', $_SESSION["loginkey"], PDO::PARAM_STR);
$kullanici_sorgu->execute();
$kullanici = $kullanici_sorgu->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Emlak & Müteahit - Proje</title>
    <!-- Bootstrap CSS dosyası -->
    <!-- jQuery ve Bootstrap için gerekli dosyalar -->
    <script src="/proje/content/js/jquery-3.3.1.min.js"></script>
    <script src="/proje/content/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/proje/content/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/proje/content/css/style.css" />
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS -->
    <style>
        .btn-fixed-height {
            height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .btn-fixed-height span.icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .btn-fixed-height span.text {
            font-size: 16px;
        }

        /* Custom Navbar Styling */
        .custom-navbar {
            background-color: #2c3e50;
            border: none;
            border-radius: 0;
            margin-bottom: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: 'Quicksand', sans-serif;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
        }

        .custom-navbar .navbar-brand {
            color: #ecf0f1;
            font-weight: bold;
            font-size: 18px;
            padding: 15px;
            margin-left: 0 !important;
        }

        .custom-navbar .navbar-brand:hover {
            color: #3498db;
        }

        .custom-navbar .navbar-nav>li>a {
            color: #ecf0f1;
            padding: 15px 12px;
            transition: all 0.3s ease;
        }

        .custom-navbar .navbar-nav>li>a:hover,
        .custom-navbar .navbar-nav>li>a:focus {
            color: #3498db;
            background-color: transparent;
        }

        .custom-navbar .navbar-nav>.active>a,
        .custom-navbar .navbar-nav>.active>a:hover,
        .custom-navbar .navbar-nav>.active>a:focus {
            color: #ffffff;
            background-color: #1a242f;
            border-bottom: 3px solid #3498db;
        }

        .custom-navbar .navbar-toggle {
            border-color: #1a242f;
            margin-right: 15px;
        }

        .custom-navbar .navbar-toggle:hover,
        .custom-navbar .navbar-toggle:focus {
            background-color: #1a242f;
        }

        .custom-navbar .navbar-toggle .icon-bar {
            background-color: #ecf0f1;
        }

        .custom-navbar .navbar-collapse {
            border-color: #1a242f;
            padding-left: 15px;
            padding-right: 15px;
        }

        .custom-navbar .dropdown-menu {
            background-color: #2c3e50;
            border: none;
            border-radius: 0;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        }

        .custom-navbar .dropdown-menu>li>a {
            color: #ecf0f1;
            padding: 10px 20px;
        }

        .custom-navbar .dropdown-menu>li>a:hover,
        .custom-navbar .dropdown-menu>li>a:focus {
            color: #3498db;
            background-color: #1a242f;
        }

        .navbar-right {
            margin-right: 0 !important;
        }

        .logout-btn:hover {
            color: #e74c3c !important;
        }

        /* Container boyutu düzeltmesi */
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Bildirim Rozeti */
        .badge-notify {
            background-color: #e74c3c;
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 10px;
            padding: 3px 5px;
        }

        /* Profil Dropdown */
        .navbar-profile {
            padding: 10px 15px;
            display: flex;
            align-items: center;
        }

        .navbar-profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .navbar-profile .profile-info {
            line-height: 1;
        }

        .navbar-profile .profile-name {
            font-weight: bold;
            color: #ecf0f1;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .navbar-profile .profile-role {
            color: #bdc3c7;
            font-size: 12px;
        }

        /* Arama kutusu */
        .navbar-form {
            margin: 10px 0;
            padding: 0 15px;
            width: 300px;
            /* Arama alanı genişliği sınırlandı */
        }

        .navbar-form .form-control {
            background-color: #1a242f;
            border: 1px solid #34495e;
            color: #ecf0f1;
            width: 100%;
            /* Arama alanını tam genişlikte kullan */
        }

        .navbar-form .form-control::placeholder {
            color: #95a5a6;
        }

        .navbar-form .btn {
            background-color: #3498db;
            color: #ffffff;
            border: none;
        }

        /* Hızlı Erişim Butonu */
        .quick-access-btn {
            position: relative;
            padding: 15px;
        }

        .quick-access-btn:hover {
            background-color: #1a242f;
        }

        /* Mobil görünüm düzenlemeleri */
        @media (max-width: 767px) {
            .custom-navbar .navbar-nav {
                margin: 0 -15px;
            }

            .custom-navbar .navbar-nav .open .dropdown-menu {
                background-color: #1a242f;
            }

            .custom-navbar .navbar-nav .open .dropdown-menu>li>a {
                color: #ecf0f1;
            }

            .custom-navbar .navbar-nav .open .dropdown-menu>li>a:hover,
            .custom-navbar .navbar-nav .open .dropdown-menu>li>a:focus {
                color: #3498db;
            }

            .custom-navbar .navbar-header {
                float: none;
            }

            .custom-navbar .navbar-collapse {
                max-height: none;
            }

            .navbar-form {
                border: none;
                margin: 0;
                padding: 10px 15px;
                width: 100%;
            }
        }

        /* Sayfa içeriğinin navbar altında doğru konumlandırılması */
        body.admin {
            padding-top: 60px;
            background-color: #f5f5f5;
        }

        /* Navbar spacing fix */
        .navbar-collapse.collapse {
            display: flex !important;
            justify-content: space-between;
        }

        .navbar-left-container {
            display: flex;
            align-items: center;
        }

        .navbar-right-container {
            display: flex;
            align-items: center;
        }

        /* Dropdown menü içi ikonların düzenlenmesi */
        .dropdown-menu>li>a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
    </style>
</head>

<body class="admin">
    <!-- Menü – Bootstrap Fixed Navbar -->
    <nav class="navbar navbar-default navbar-ed-top custom-navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><i class="fas fa-building"></i> Emlak & Müteahit - Proje</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <?php $aktif_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>

                <div class="navbar-left-container">
                    <!-- Sol Navigasyon Öğeleri -->
                    <ul class="nav navbar-nav">
                        <li <?php echo (strpos($aktif_link, '/admin/index') !== false ? 'class="active"' : ''); ?>>
                            <a href="/proje/admin/index.php"><i class="fas fa-home"></i> Anasayfa</a>
                        </li>
                        <li <?php echo (strpos($aktif_link, '/admin/projeler/liste') !== false ? 'class="active"' : ''); ?>>
                            <a href="/proje/admin/projeler/liste.php"><i class="fas fa-project-diagram"></i> Projeler</a>
                        </li>
                        <li class="dropdown <?php echo (strpos($aktif_link, '/admin/ilan/liste') !== false ? 'active' : ''); ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ad"></i> İlanlar <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/proje/admin/ilan/liste.php"><i class="fas fa-list"></i> Tüm İlanlar</a></li>
                                <li class="divider"></li>
                                <li><a href="/proje/admin/ilan/ekle_ev.php"><i class="fas fa-plus-square"></i> Yeni Ev İlanı Ekle</a></li>
                                <li><a href="/proje/admin/ilan/ekle_arsa.php"><i class="fas fa-plus-square"></i> Yeni Arsa İlanı Ekle</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-list"></i> Kategoriler <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li <?php echo (strpos($aktif_link, '/admin/projeler_kategori/liste') !== false ? 'class="active"' : ''); ?>>
                                    <a href="/proje/admin/projeler_kategori/liste.php"><i class="fas fa-tags"></i> Proje Kategorileri</a>
                                </li>
                                <li <?php echo (strpos($aktif_link, '/admin/kategori/liste') !== false ? 'class="active"' : ''); ?>>
                                    <a href="/proje/admin/kategori/liste.php"><i class="fas fa-list"></i> İlan Kategorileri</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown <?php echo (strpos($aktif_link, '/admin/ilan/liste') !== false ? 'active' : ''); ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ad"></i> Kullanıcılar <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/proje/admin/kullanici/liste.php"><i class="fas fa-list"></i> Tüm Kullanıcılar</a></li>
                                <li class="divider"></li>
                                <li><a href="/proje/admin/kullanici/onay.php"><i class="fas fa-plus-square"></i> Onay Bekleyen Kullanıcılar</a></li>

                                <li><a href="/proje/admin/kullanici/ekle.php"><i class="fas fa-plus-square"></i> Yeni Kullanıcı Ekle</a></li>

                            </ul>
                        </li> <li class="dropdown <?php echo (strpos($aktif_link, '/admin/ilan/liste') !== false ? 'active' : ''); ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ad"></i> E-Posta İşlemleri <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/proje/admin/mailekle/liste.php"><i class="fas fa-list"></i> Kayıtlı Mailler</a></li>
                                <li class="divider"></li>
                                <li><a href="/proje/admin/mailekle/ekle_mail.php"><i class="fas fa-plus-square"></i> Sisteme Yeni Mail Ekle</a></li>

                                <li><a href="/proje/admin/mailekle/istatistik.php"><i class="fas fa-plus-square"></i> Gönderilen Mail İstatistikleri</a></li>

                            </ul>
                        </li>
                    </ul>

                    <!-- Arama Formu -->
                    <form class="navbar-form" action="/proje/admin/arama.php" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Proje, ilan veya kullanıcı ara..." name="q">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="navbar-right-container">
                    <!-- Sağ Navigasyon Öğeleri -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Mesaj Bildirimleri -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-envelope"></i>
                                <?php if ($bildirim_sayisi > 0) : ?>
                                    <span class="badge badge-notify"><?php echo $bildirim_sayisi; ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">Mesajlar</li>
                                <?php if ($bildirim_sayisi > 0) : ?>
                                    <li><a href="/proje/admin/mesaj/liste.php"><i class="fas fa-envelope"></i> <?php echo $bildirim_sayisi; ?> yeni mesaj</a></li>
                                <?php else : ?>
                                    <li><a href="#">Yeni mesaj yok</a></li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <li><a href="/proje/admin/mesaj/liste.php">Tüm mesajları gör</a></li>
                            </ul>
                        </li>
                        <!-- Hızlı İşlemler -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle quick-access-btn" data-toggle="dropdown">
                                <i class="fas fa-bolt"></i>
                                <?php if ($onay_bekleyen > 0) : ?>
                                    <span class="badge badge-notify"><?php echo $onay_bekleyen; ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">Hızlı İşlemler</li>
                                <li><a href="/proje/admin/projeler/ekle_proje.php"><i class="fas fa-plus-circle"></i> Yeni Proje Ekle</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#"><i class="fas fa-plus-square"></i> Yeni İlan Ekle</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/proje/admin/ilan/ekle_ev.php"><i class="fas fa-home"></i> Ev İlanı Ekle</a></li>
                                        <li><a href="/proje/admin/ilan/ekle_arsa.php"><i class="fas fa-map"></i> Arsa İlanı Ekle</a></li>
                                    </ul>
                                </li>
                                <li><a href="/proje/admin/kullanici/ekle.php"><i class="fas fa-user-plus"></i> Yeni Kullanıcı Ekle</a></li>
                                <li class="divider"></li>
                                <li><a href="/proje/admin/ilan/onay.php"><i class="fas fa-check-circle"></i> Onay Bekleyen İlanlar (<?php echo $onay_ilan_bekleyen; ?>)</a></li>
                                <li><a href="/proje/admin/projeler/onay.php"><i class="fas fa-check-double"></i> Onay Bekleyen Projeler (<?php echo $onay_proje_bekleyen; ?>)</a></li>
                                <li><a href="/proje/admin/kullanici/onay.php"><i class="fas fa-user-check"></i> Onay Bekleyen Kullanıcılar (<?php echo $onay_kullanici_bekleyen; ?>)</a></li>
                            </ul>
                        </li>

                        <?php
                        // Profil resmi kontrolü
                        $profil_resmi_var = false;
                        $profil_resmi = "/proje/admin/profil/profil-image/default-profile.jpg"; // Varsayılan resim

                        // Veritabanında profil_resmi alanının olup olmadığını kontrol et
                        $tablo_kontrol = $con->prepare("SHOW COLUMNS FROM kullanicilar LIKE 'profil_resmi'");
                        $tablo_kontrol->execute();
                        $profil_resmi_var = ($tablo_kontrol->rowCount() > 0);

                        // Eğer profil_resmi sütunu varsa ve kullanıcının profil resmi varsa
                        if ($profil_resmi_var && isset($kullanici['profil_resmi']) && !empty($kullanici['profil_resmi'])) {
                            $profil_resmi = $kullanici['profil_resmi'];
                        }
                        ?>

                        <!-- Kullanıcı Profili -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo htmlspecialchars($profil_resmi); ?>" alt="Profil" class="img-circle" width="20" height="20">
                                <span><?php echo $_SESSION["loginkey"]; ?> <span class="caret"></span></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/proje/admin/profil/admin_profil.php?kadi=<?php echo $_SESSION["loginkey"]; ?>"><i class="fas fa-user-circle"></i> Profilim</a></li>
                                <li><a href="/proje/admin/profil/ayarlar.php"><i class="fas fa-cog"></i> Ayarlar</a></li>
                                <li><a href="/proje/admin/profil/sifre_degistir.php"><i class="fas fa-key"></i> Şifre Değiştir</a></li>
                                <li class="divider"></li>
                                <li><a href="/proje/admin/login.php?cikis=1" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Oturumu Kapat</a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <br>

    <!-- Alt menü desteği için gerekli JavaScript kodu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobil ve masaüstü için alt menü desteği
            $('.dropdown-submenu > a').on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $(this).parent().siblings().removeClass('open');
                $(this).parent().toggleClass('open');
            });
        });
    </script>

    <!-- Hızlı erişim menüsündeki alt menüler için CSS -->
    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            border-radius: 0;
            background-color: #2c3e50;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        .dropdown-submenu>a:after {
            display: block;
            content: " ";
            float: right;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
            border-width: 5px 0 5px 5px;
            border-left-color: #cccccc;
            margin-top: 5px;
            margin-right: -10px;
        }

        .dropdown-submenu:hover>a:after {
            border-left-color: #ffffff;
        }

        .dropdown-submenu.pull-left {
            float: none;
        }

        .dropdown-submenu.pull-left>.dropdown-menu {
            left: -100%;
            margin-left: 10px;
            border-radius: 0;
        }

        /* Mobil görünümde alt menülerin doğru çalışması için */
        @media (max-width: 767px) {
            .dropdown-submenu>.dropdown-menu {
                left: 0;
                margin-left: 15px;
                top: 100%;
            }

            .dropdown-submenu>a:after {
                transform: rotate(90deg);
                margin-top: 8px;
            }
        }
    </style>

    <script>
        // Sayfanın yüklenmesi tamamlandığında çalışacak kod
        $(document).ready(function() {
            // Dropdown menüler için hover etkinliği (masaüstü cihazlarda)
            if (window.innerWidth >= 768) {
                $('.dropdown').hover(
                    function() {
                        $(this).addClass('open');
                    },
                    function() {
                        $(this).removeClass('open');
                    }
                );
            }

            // Dropdown submenüler için açılıp kapanma işlemleri
            $('.dropdown-submenu > a').on('click', function(e) {
                // Tıklama olayının yukarı yayılmasını engelleyelim
                e.stopPropagation();
                e.preventDefault();

                // Tüm açık alt menüleri kapatalım
                var $allDropdowns = $('.dropdown-submenu');
                $allDropdowns.not($(this).parent()).removeClass('open');

                // Tıklanan alt menüyü aç/kapat
                $(this).parent().toggleClass('open');
            });

            // Sayfa içeriği tıklamalarında açık menüleri kapat
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown-submenu').length) {
                    $('.dropdown-submenu').removeClass('open');
                }
            });

            // Mobil görünümde navbar toggle işlemleri
            $('.navbar-toggle').on('click', function() {
                $('#navbar').toggleClass('in');
            });

            // Küçük ekranlarda dropdown menüye tıklandığında
            if (window.innerWidth < 768) {
                $('.dropdown > a').on('click', function(e) {
                    var $parent = $(this).parent();

                    // Eğer dropdown zaten açıksa ve bir alt linkine tıklanmışsa, bağlantıya izin ver
                    if ($parent.hasClass('open') && $(e.target).is('a') && $(e.target).attr('href') !== '#') {
                        return true;
                    }

                    // Diğer açık dropdownları kapat
                    $('.dropdown').not($parent).removeClass('open');

                    // Bu dropdownı aç/kapat
                    $parent.toggleClass('open');
                    return false;
                });
            }
        });
    </script>