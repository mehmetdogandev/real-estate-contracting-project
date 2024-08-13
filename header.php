<?php
// Oturum işlemlerini başlat
ob_start();
session_start();

// Favori session oluşturulmamışsa oluştur
$_SESSION['favori'] = isset($_SESSION['favori']) ? $_SESSION['favori'] : array();

// veritabanı bağlantı dosyasını dahil et
include 'config/vtabani.php';

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
	$logo_baglanti = $kayit['logo_baglanti'];
}
// hatayı göster
catch (PDOException $exception) {
	die('HATA: ' . $exception->getMessage());
}

?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en"> <!--<![endif]-->

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					
	<meta name="description" content="Buildify">
	<meta name="author" content="Marketify">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Emlak & Müteahit - Proje</title>

	<!-- STYLES -->
	<link rel="stylesheet" type="text/css" href="css/fontello.css" />
	<link rel="stylesheet" type="text/css" href="css/skeleton.css" />
	<link rel="stylesheet" type="text/css" href="css/plugins.css" />
	<link rel="stylesheet" type="text/css" href="css/base.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<!-- Simge kütüphanesi -->
	<link rel="stylesheet" href="content/css/font-awesome-4.7.0/css/font-awesome.min.css">
	<!-- Benim stil dosyam -->
	<link rel="stylesheet" type="text/css" href="content/css/style.css">
	<!-- İlk önce jQuery, sonra Popper.js, sonra da Bootstrap JS -->
	<script type="text/javascript" src="content/js/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
	<!--[if lt IE 9]> <script type="text/javascript" src="js/modernizr.custom.js"></script> <![endif]-->
	<!-- /STYLES -->

</head>

<body>
	<div class="container-fluid bg-success"style="z-index:50; position:relative !important;">
		<div class="container">
			<div class="row p-2">
				<div class="col-md-8">
					<div class="text-white">
						<?php if (!isset($_SESSION["kullanici_loginkey"]) || empty($_SESSION["kullanici_loginkey"])) { ?>
							<a class="link1" style="font-size:12px; z-index: 50 !important;" href="kayit.php">Kayıt Ol</a> - <a class="link1" style="font-size:12px; z-index: 50 !important;" href="giris.php">Giriş Yap</a>
							&nbsp;
							<a class="btn btn-warning btn-sm" href="kayit.php?islem=girisYokilanver" role="button">Ücretsiz ilan ver!</a>
						<?php } else { ?>
							<a class="link1" style="font-size:12px;" href="profil.php">Profil</a>
							-
							<a class="link1" style="font-size:12px;" href="ilanlarim.php">İlanlarım</a>
							-
							<a class="link1" style="font-size:12px;" href="mesajlarim.php">Mesajlarım</a>
							&nbsp;
							<a class="btn btn-warning btn-sm" href="ilanver.php" role="button">Ücretsiz ilan ver!</a>
							&nbsp;
							<a class="link1" style="font-size:12px;" href="giris.php?cikis=1">Çıkış</a>
						<?php } ?>

					</div>
				</div>
				<div class="col-md-4 text-right text-white">
					<a class="link1" href="favorilerim.php"> <!-- Favori içeriği sayfası linki -->
						<span class="fa-stack fa-1x">
							<i class="fa fa-circle-thin fa-stack-2x"></i>
							<i class="fa fa-heart fa-stack-1x"></i>
						</span> Favoriler
						<span class="badge badge-light" id="urun-sayisi">
							<!-- Favorideki ürün sayısı -->
							<?php
							if (isset($_SESSION['favori']) || !empty($_SESSION['favori'])) {
								$urun_sayisi = count($_SESSION['favori']);
								echo $urun_sayisi;
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
	<!-- MAIN BACKGROUND -->
	<div class="buildify_tm_mainbg">

		<!-- PATTERN -->
		<div class="marketify_pattern_overlay"></div>
		<!-- /PATTERN -->

	</div>
	<!-- /MAIN BACKGROUND -->

	<!-- WRAPPER ALL -->
	<div class="buildify_tm_wrapper_all">

		<div class="buildify_tm_wrapper">


			<div class="buildify_tm_animate_submenu"></div>


			<!-- LEFTPART -->
			<div class="buildify_tm_leftpart_wrap">

				<!-- LEFT PATTERN -->
				<div class="buildify_tm_build_pattern"></div>
				<!-- /LEFT PATTERN -->

				<!-- MENUBAR -->
				<div class="buildify_tm_menubar">
				<div class="menu_logo">
								<a href="index.php">
									<img src="content/images/<?php echo $logo_baglanti; ?>" alt="Logo" style="width:150px; position:relative;">
								</a><br>
								
								<form class="form-inline my-2 my-lg-0" action="urunler.php" method="get" name="form_ara">
									<input class="form-control mr-sm-2" type="search" placeholder="Arama yapın..." aria-label="Ara" name="aranan">
									<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Ara</button>
								</form>

							</div>
					<div class="buildify_tm_menubar_in">
						
						<div class="buildify_tm_menubar_content">
						

							<div class="menu_nav">

								<div class="menu_nav_in">
									<div class="menu_nav_content scrollable">
										<ul class="vert_nav">
											<li><a href="index.php">Anasayfa <!--<span class="sronly">(current)</span>--></a></li>
											<li class="active1">
												<a href="#">Çalışmalarımız</a>
												<div class="inside_menu">
													<ul>
														<li><a href="projeler.php">Projeler</a></li>
														<li><a href="urunler.php">İlanlar</a></li>
													</ul>
												</div>
											</li>
											<li class="active1">
												<a href="#">Bizden Olun</a>
												<div class="inside_menu">
													<ul>
														<li><a href="kayit.php">Pazarlamacımız Olun</a></li>
														<li><a href="kayit.php">Müşterimiz Olun</a></li>
													</ul>
												</div>
											</li>
											<li><a href="hakkimizda.php">Hakkımızda</a></li>
											<li><a href="blog.php">Blog</a></li>
											<li><a href="Ortaklarımız.php">Ortaklarımız</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /MENUBAR -->
			</div>
			<!-- /LEFTPART -->

			<!-- RIGHTPART -->
			<div class="buildify_tm_rightpart_wrap">
				<div class="buildify_tm_rightpart">

					<!-- CONTENT -->
					<div class="buildify_tm_content_wrap">
						<div class="buildify_tm_content">

							<!-- TOPBAR -->
							<div class="buildify_tm_topbar_info">
								<div class="buildify_tm_connection">
									<div class="phone_numb">
										<div class="phone_numb_in">
											<img src="img/call.png" alt="" />
											<p>Toll Free: <span>1-800-987-6543</span></p>
										</div>
									</div>
									<div class="send_msg">
										<a href="mesajlarım.php">
											<img class="svg" src="img/svg/message2.svg" alt="" />
										</a>
									</div>
								</div>
								<div class="buildify_tm_social_list">
									<ul>
										<li><a href="#"><i class="xcon-facebook"></i></a></li>
										<li><a href="#"><i class="xcon-twitter"></i></a></li>
										<li><a href="#"><i class="xcon-instagram"></i></a></li>
										<li><a href="#"><i class="xcon-pinterest"></i></a></li>
										<li><a href="#"><i class="xcon-gplus"></i></a></li>
									</ul>
								</div>
							</div>
							<!-- /TOPBAR -->

							<!-- HEADER -->
							<div class="buildify_tm_mobile_header_wrap">
								<div class="in">
									<div class="container">
										<div class="header_inner">
											<div class="logo">
												<img src="content/images/<?php echo $logo_baglanti; ?>" alt="Logo" style="">
											</div>
											<div class="buildify_tm_trigger">
												<div class="hamburger hamburger--collapse-r">
													<div class="hamburger-box">
														<div class="hamburger-inner"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="navigation_wrap">
										<div class="container">
											<div class="inner_navigation">
												<ul class="nav">
													<li><a href="index.html">Anasayfa</a></li>
													<li>
														<a href="#">Çalışmalarımız</a>
														<ul class="sub_menu">
															<li><a href="projects.html">Projeler</a></li>
															<li><a href="project_single.html">İlanlar</a></li>
														</ul>
													</li>
													<li>
														<a href="#">Bizden Olun</a>
														<ul class="sub_menu">
															<li><a href="kayit.php">Pazarlamacımız Olun</a></li>
															<li><a href="kayit.php">Müşterimiz Olun</a></li>
														</ul>
													</li>
													<li><a href="hakkimizda.php">Hakkımızda</a></li>
													<li><a href="blog.php">Blog</a></li>
													<li><a href="Ortaklarımız.php">Ortaklarımız</a></li>
												</ul>
											</div>
										</div>
									</div> -->
								</div>
							</div>
							<!-- /HEADER -->
