<?php  include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/header.php';  ?>
<?php require_once "tablo.php" ?>

<?php echo ilanDetayTablosu($_GET["id"]) ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/proje/admin/footer.php';   ?>
