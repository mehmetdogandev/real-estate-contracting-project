<?php
include "header.php";

// Veritabanını çağırıyoruz
include '../config/vtabani.php';

// Sadece üyeleri sayan sorgu - kullanici onay = 2 olanları sayıyorum
$uyeKullaniciSay = $con->query('SELECT count(*) FROM kullanicilar WHERE onay="2"')->fetchColumn();

// Toplam ilan sayısını sorguluyorum ve onay = 1 olanları çağırıyorum
$onayliIlanSay = $con->query('SELECT count(*) FROM urunler WHERE onay="1"')->fetchColumn();

// Toplam EV ilan sayısını sorguluyorum ve onay = 1 olanları çağırıyorum
$onayliEvIlanSay = $con->query('SELECT count(*) FROM urunler WHERE onay="1" AND evarsa_id="1" ')->fetchColumn();

// Toplam ARSA ilan sayısını sorguluyorum ve onay = 1 olanları çağırıyorum
$onayliArsaIlanSay = $con->query('SELECT count(*) FROM urunler WHERE onay="1" AND evarsa_id="2" ')->fetchColumn();

// Toplam mesaj sayısını sorguluyorum
$admin_mesaj_Say = $con->query('SELECT count(*) FROM admin_mesajlar')->fetchColumn();
?>

<div class="container m-t-1em">
    <!-- Sayfa kodları bu alana eklenecek -->
    <!-- Proje hakkında kısa bir bilgi içeren anasayfa -->
    <div class="jumbotron text-justify">
        <div class="page-header">
            <h2><span class='glyphicon glyphicon-stats'></span> Emlak Proje - Genel İstatistikler</h2>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <a href="kullanici/liste.php" class="btn btn-primary btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-user"></span>
                    <span class="text">Toplam<br />
                    <span class="text">Üye Sayısı<br /><?php echo $uyeKullaniciSay; ?></span>
                </a>
            </div>
            <div class="col-md-2">
                <a href="mesaj/liste.php" class="btn btn-danger btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-comment"></span>
                    <span class="text">Toplam<br />
                    <span class="text">Mesaj Sayısı<br /><?php echo $admin_mesaj_Say; ?></span>
                </a>
            </div>
            <div class="col-md-2">
                <a href="ilan/liste.php" class="btn btn-warning btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-list"></span>
                    <span class="text">Toplam<br />
                    <span class="text">Toplam İlan Sayısı<br /><?php echo $onayliIlanSay; ?></span>
                </a>
            </div>
            <div class="col-md-2">
                <a href="ilan/liste.php?aranan=ev" class="btn btn-success btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-home"></span>
                    <span class="text">Toplam<br />
                    <span class="text">Ev İlanı<br /><?php echo $onayliEvIlanSay; ?></span>
                </a>
            </div>
            <div class="col-md-2">
                <a href="ilan/liste.php?aranan=arsa" class="btn btn-info btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-road"></span>
                    <span class="text">Toplam<br />
                    <span class="text">Arsa İlanı<br /><?php echo $onayliArsaIlanSay; ?></span>
                </a>
            </div>
            <div class="col-md-2">
                <a href="projeler/liste.php?aranan=proje" class="btn btn-success btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-list-alt"></span>
                    <span class="text">Toplam<br />
                    <span class="text">Proje Sayısı<br /><?php echo $onayliEvIlanSay; ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="jumbotron text-justify">
        <div class="page-header">
            <h2><span class='glyphicon glyphicon-wrench'></span> Emlak Proje - Site Ayarları</h2>
        </div>
        <div class="row">
            <div class="col-md-3">
                <a href="site_ayar/logo_liste.php" class="btn btn-primary btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-cloud-upload"></span>
                    <span class="text">Logoyu Değiştir</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="site_ayar/slider_liste.php" class="btn btn-info btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-text-width"></span>
                    <span class="text">Sliderları Değiştir</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="kategori/liste.php" class="btn btn-success btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-folder-open"></span>
                    <span class="text">İlan Kategori İşlemleri</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="projeler_kategori/liste.php" class="btn btn-success btn-lg active m-b-1em btn-fixed-height" role="button" aria-pressed="true">
                    <span class="icon glyphicon glyphicon-briefcase"></span>
                    <span class="text">Proje Kategori İşlemleri</span>
                </a>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<?php include "footer.php"; ?>
