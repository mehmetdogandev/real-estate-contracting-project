<?php
session_start();
if ($_SESSION["loginkey"] == "") {
	// oturum açılmamışsa login.php sayfasına git
	header("Location: /proje/admin/login.php");
}

// veritabanı bağlantı dosyasını dahil et
/*include_once "/proje/config/vtabani.php";

$onaySayKullanici = $con->query('SELECT count(*) FROM kullanicilar WHERE onay="0"')->fetchColumn(); 
$onaySayIlan = $con->query('SELECT count(*) FROM urunler WHERE onay="0"')->fetchColumn(); 
$onayToplam = $onaySayKullanici + $onaySayIlan;
$con->null;*/
?>

<!doctype html>
<html lang="tr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Emlak & Müteahit - Proje</title>
	<!-- Bootstrap CSS dosyası -->
	<link rel="stylesheet" href="/proje/content/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/proje/content/css/style.css" />
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<!-- Bootstrap CSS -->
	<style>
    .btn-fixed-height {
        height: 200px; /* Butonların aynı yükseklikte olması için sabit bir yükseklik belirleyin */
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
</style>





</head>

<body class="admin">
	<!-- Menü – Bootstrap Fixed Navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="navbar-header" style="margin-left: 100px">

			<a class="navbar-brand" href="#">Emlak & Müteahit - Proje</a>
		</div>
		<div class="container">

			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<!--- tarayıcının adres satırındaki url ifadesini okur
 ve buna göre ilgili menü seçeneğini aktifleştirir -->


					<?php $aktif_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
					<li <?php echo (strpos($aktif_link, '/admin/index') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/index.php">Anasayfa</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/projeler/liste') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/projeler/liste.php">Projeler</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/projeler_kategori/liste') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/projeler_kategori/liste.php">Proje Kategoriler</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/ilan/liste') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/ilan/liste.php">İlanlar</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/kategori/liste') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/kategori/liste.php">İlan Kategoriler</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/kullanici/liste') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/kullanici/liste.php">Kullanıcılar</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/onay/liste') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/onay/liste.php">Onay İşlemleri</a></li>
					<li <?php echo (strpos($aktif_link, '/admin/mailekle') !== false ? 'class="active"' : ''); ?>><a href="/proje/admin/mailekle/islem_sec.php">Mail İşlemleri</a></li>


					<?php
					/*if($onayToplam>0){
	echo "(".$onayToplam.")";
}*/ ?>
					</a></li>
					<li <?php echo ((strpos($aktif_link, 'mesaj/') !== false) ? 'class="active"' : ''); ?>><a href="/proje/admin/mesaj/liste.php">Mesajlar</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/proje/admin/admin_profil.php?kadi=<?php echo $_SESSION["loginkey"]; ?>"> <span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION["loginkey"]; ?></a></li>
					<li><a href="/proje/admin/login.php?cikis=1"><span class="glyphicon glyphicon-log-out"></span> Oturumu kapat</a></li>
				</ul>
			</div><!--/.nav-collapse -->

		</div>

	</nav>

	<!-- Menü sonu -->
	<br>