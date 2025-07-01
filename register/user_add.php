<?php
if($_POST){
 // veritabanı yapılandırma dosyasını dahil et
include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php'; 
 if($_POST['adsoyad']=="" || $_POST['kadi']=="" || $_POST['sifre']=="" || $_POST['eposta']=="" || $_POST['tel_no']==""){
	 header("Location: /register/?islem=bosluk");
 }
 else{
 try{
 // kayıt ekleme sorgusu
 $sorgu = "INSERT INTO kullanicilar SET adsoyad=:adsoyad, kadi=:kadi,
sifre=:sifre, eposta=:eposta, tel_no=:tel_no";
 // sorguyu hazırla
 $stmt = $con->prepare($sorgu);
 // post edilen değerler
 $adsoyad=htmlspecialchars(strip_tags($_POST['adsoyad']));
 $kadi=htmlspecialchars(strip_tags($_POST['kadi']));
 $sifre=htmlspecialchars(strip_tags($_POST['sifre']));
 $eposta=htmlspecialchars(strip_tags($_POST['eposta']));
 $tel_no=htmlspecialchars(strip_tags($_POST['tel_no']));
 // parametreleri bağla
 $stmt->bindParam(':adsoyad', $adsoyad);
 $stmt->bindParam(':kadi', $kadi);
 $stmt->bindParam(':sifre', $sifre);
 $stmt->bindParam(':eposta', $eposta);
 $stmt->bindParam(':tel_no', $tel_no);
 // sorguyu çalıştır
 if($stmt->execute()){
 header("Location: /register/?islem=basarili");
 }else{
 header("Location: /register/?islem=basarisiz");
 }
 }
 // hatayı göster
 catch(PDOException $exception){
 die('ERROR: ' . $exception->getMessage());
 }
 }
}
 ?>