<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';
$ids = implode(',', $_POST['msj_id']);
$con->query("DELETE FROM admin_mesajlar WHERE msj_id IN ($ids)");
