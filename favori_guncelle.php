<?php
// oturum işlemlerini başlat
session_start();
// ilan id ve adet bilgilerini al
$id = isset($_POST['id']) ? $_POST['id'] : "";
$adet = isset($_POST['adet']) ? $_POST['adet'] : "";
// ilan sepetten sil
unset($_SESSION['favori'][$id]);
if ($adet <> "") {
    // ilan güncel adet bilgisiyle kaydet
    $_SESSION['favori'][$id] = array('adet' => $adet);
}
