<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';
?>
<?php require_once "tablo.php" ?>

<?php echo ilanDetayTablosu($_GET["id"]) ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php';   ?>
