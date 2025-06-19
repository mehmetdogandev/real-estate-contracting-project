<?php
require_once "../tablo.php";

function imageToBase64($imagePath)
{
    // Resmi dosya yolundan oku
    $imageData = file_get_contents($imagePath);

    // Base64'e dönüştür
    $base64 = base64_encode($imageData);

    // Base64 verisini data URL formatında birleştir
    $dataUrl = 'data:' . mime_content_type($imagePath) . ';base64,' . $base64;

    return $dataUrl;
}

// Veritabanı bağlantısı için gerekli parametreler
$ilanId = isset($_GET['id']) ? $_GET['id'] : die('HATA: İlan bulunamadı.');
$kisiler = isset($_GET['kisiler']) ? $_GET['kisiler'] : die('HATA: Kişiler bulunamadı.');

include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';
include $_SERVER['DOCUMENT_ROOT'] . '/config/mail-info.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/ilan/mailgonder/PHPMailer-6.9.1/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/ilan/mailgonder/PHPMailer-6.9.1/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/ilan/mailgonder/PHPMailer-6.9.1/src/SMTP.php';

$bugun = date('m-d'); // Yalnızca ay ve günü kontrol edeceğiz

foreach ($kisiler as $kisi_id) {
    // Kişi bilgilerini veritabanından al
    $sorgu = $con->prepare("SELECT * FROM kisiler WHERE id = ?");
    $sorgu->execute([$kisi_id]);
    $row = $sorgu->fetch(PDO::FETCH_ASSOC);

    // Kişi bilgilerini değişkenlere atama
    $ad = $row['ad'];
    $soyad = $row['soyad'];
    $email = $row['email'];

    // İlan bilgilerini veritabanından al
    $sorgu = $con->prepare("SELECT * FROM projeler WHERE id = ?");
    $sorgu->execute([$ilanId]);
    $ilan = $sorgu->fetch(PDO::FETCH_ASSOC);




    // gelen Id parametresini al
    // isset() bir değer olup olmadığını kontrol eden PHP fonksiyonudur
    $id = isset($ilanId) ? $ilanId : throw new Exception('HATA: Kayıt bulunamadı.');

    // veritabanı bağlantı dosyasını çağır
    include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php';
    // aktif kayıt bilgilerini oku
    // seçme sorgusunu hazırla
    $sorgu = "SELECT projeler.urunadi, projeler.aciklama, projeler.fiyat, projeler.giris_tarihi, projeler.dzltm_tarihi,
        projeler.resim, projeler.resim_iki, projeler.resim_uc, projeler.resim_dort, projeler.evarsa_id, projeler_kategoriler.kategoriadi, evbilgi.ev_tipi, evbilgi.ev_metrekare, evbilgi.oda_sayisi,
            evbilgi.bina_yasi, evbilgi.kat_sayisi, evbilgi.isitma, evbilgi.banyo_sayisi, evbilgi.esyali, evbilgi.kullanim_durumu,
            evbilgi.site_icinde, evbilgi.aidat, evbilgi.ev_krediye_uygun, evbilgi.ev_kimden, evbilgi.ev_takas,
            arsabilgi.imar_durumu, arsabilgi.arsa_metrekare, arsabilgi.metrekare_fiyat, arsabilgi.ada_no, arsabilgi.parsel_no,
            arsabilgi.pafta_no, arsabilgi.emsal, arsabilgi.tapu_durumu, arsabilgi.kat_karsiligi, arsabilgi.arsa_krediye_uygun, arsabilgi.arsa_kimden, arsabilgi.arsa_takas
        FROM projeler 
        LEFT JOIN projeler_kategoriler ON projeler.kategori_id = projeler_kategoriler.id 
        LEFT JOIN evbilgi ON projeler.id = evbilgi.ev_urun_id 
        LEFT JOIN arsabilgi ON projeler.id = arsabilgi.arsa_urun_id 
        WHERE projeler.id = ? LIMIT 0,1";
    $stmt = $con->prepare($sorgu);


    // Id parametresini bağla
    $stmt->bindParam(1, $id);

    // sorguyu çalıştır
    $stmt->execute();

    // gelen kaydı bir değişkende sakla
    $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

    // tabloya yazılacak bilgileri değişkenlere doldur
    $urunadi = $kayit['urunadi'];
    $aciklama = $kayit['aciklama'];
    $fiyat = $kayit['fiyat'];
    $giris_tarihi = $kayit['giris_tarihi'];
    $dzltm_tarihi = $kayit['dzltm_tarihi'];
    $resim = htmlspecialchars(imageToBase64("C:\laragon\www\proje\content\images\\" . $kayit['resim']), ENT_QUOTES);
    $resim_iki = htmlspecialchars(imageToBase64("C:\laragon\www\proje\content\images\\" . $kayit['resim_iki']), ENT_QUOTES);
    $resim_uc = htmlspecialchars(imageToBase64("C:\laragon\www\proje\content\images\\" . $kayit['resim_uc']), ENT_QUOTES);
    $resim_dort = htmlspecialchars(imageToBase64("C:\laragon\www\proje\content\images\\" . $kayit['resim_dort']), ENT_QUOTES);
    $evarsa_id = $kayit['evarsa_id'];
    $kategoriadi = $kayit['kategoriadi'];
    $ev_tipi = $kayit['ev_tipi'];
    $ev_metrekare = $kayit['ev_metrekare'];
    $oda_sayisi = $kayit['oda_sayisi'];
    $bina_yasi = $kayit['bina_yasi'];
    $kat_sayisi = $kayit['kat_sayisi'];
    $isitma = $kayit['isitma'];
    $banyo_sayisi = $kayit['banyo_sayisi'];
    $esyali = $kayit['esyali'];
    $kullanim_durumu = $kayit['kullanim_durumu'];
    $site_icinde = $kayit['site_icinde'];
    $aidat = $kayit['aidat'];
    $ev_krediye_uygun = $kayit['ev_krediye_uygun'];
    $ev_kimden = $kayit['ev_kimden'];
    $ev_takas = $kayit['ev_takas'];
    $imar_durumu = $kayit['imar_durumu'];
    $arsa_metrekare = $kayit['arsa_metrekare'];
    $metrekare_fiyat = $kayit['metrekare_fiyat'];
    $ada_no = $kayit['ada_no'];
    $parsel_no = $kayit['parsel_no'];
    $pafta_no = $kayit['pafta_no'];
    $emsal = $kayit['emsal'];
    $tapu_durumu = $kayit['tapu_durumu'];
    $kat_karsiligi = $kayit['kat_karsiligi'];
    $arsa_krediye_uygun = $kayit['arsa_krediye_uygun'];
    $arsa_kimden = $kayit['arsa_kimden'];
    $arsa_takas = $kayit['arsa_takas'];


    $giris_tarihi = substr(htmlspecialchars($giris_tarihi, ENT_QUOTES), '0', '10');


    if ($evarsa_id == 3) {
        // kayıt listeleme sorgusu
        $sorgu = 'SELECT adsoyad, tel_no FROM kullanicilar WHERE id=' . $ev_kimden;
        $stmt = $con->prepare($sorgu); // sorguyu hazırla
        $stmt->execute(); // sorguyu çalıştır
        $veri = $stmt->fetch(PDO::FETCH_ASSOC); // tablo verilerini oku
        $adsoyad = $veri['adsoyad'];
        $telefon = $veri['tel_no'];


        $message = file_get_contents("email_template_$evarsa_id.html");

        $message = str_replace('{AD}', $ad, $message);
        $message = str_replace('{SOYAD}', $soyad, $message);
        $message = str_replace('{resim}', $resim, $message);
        $message = str_replace('{resim_iki}', $resim_iki, $message);
        $message = str_replace('{resim_uc}', $resim_uc, $message);
        $message = str_replace('{resim_dort}', $resim_dort, $message);
        $message = str_replace('{İlanGirisTarihi}', $giris_tarihi, $message);
        $message = str_replace('{İlanGuncellenmeTarihi}', $dzltm_tarihi, $message);
        $message = str_replace('{İlanBasligi}', $urunadi, $message);
        $message = str_replace('{Aciklama}', $aciklama, $message);
        $message = str_replace('{Fiyat}', $fiyat, $message);
        $message = str_replace('{Kategori}', $kategoriadi, $message);
        $message = str_replace('{EvTipi}', $ev_tipi, $message);
        $message = str_replace('{Metrekare}', $ev_metrekare, $message);
        $message = str_replace('{OdaSayisi}', $oda_sayisi, $message);
        $message = str_replace('{Binayasi}', $bina_yasi, $message);
        $message = str_replace('{KatSayisi}', $kat_sayisi, $message);
        $message = str_replace('{Isıtma}', $isitma, $message);
        $message = str_replace('{BanyoSayısı}', $banyo_sayisi, $message);
        $message = str_replace('{Esyalimi}', $esyali, $message);
        $message = str_replace('{KullanımDurumu}', $kullanim_durumu, $message);
        $message = str_replace('{Siteicindemi}', $site_icinde, $message);
        $message = str_replace('{Aidat}', $aidat, $message);
        $message = str_replace('{KrediyeUygunmu}', $ev_krediye_uygun, $message);
        $message = str_replace('{Kimden}', $adsoyad, $message);
        $message = str_replace('{Telefon}', $telefon, $message);
        $message = str_replace('{Takas}', $ev_takas, $message);
    } elseif ($evarsa_id == 2) {

        //arsa kimden
        $sorgu = 'SELECT adsoyad, tel_no FROM kullanicilar WHERE id=' . $arsa_kimden;
        $stmt = $con->prepare($sorgu); // sorguyu hazırla
        $stmt->execute(); // sorguyu çalıştır
        $veri = $stmt->fetch(PDO::FETCH_ASSOC); // tablo verilerini oku
        $adsoyad = $veri['adsoyad'];
        $telefon = $veri['tel_no'];

        $message = file_get_contents("email_template_$evarsa_id.html");

        $message = str_replace('{AD}', $ad, $message);
        $message = str_replace('{SOYAD}', $soyad, $message);
        $message = str_replace('{resim}', $resim, $message);
        $message = str_replace('{resim_iki}', $resim_iki, $message);
        $message = str_replace('{resim_uc}', $resim_uc, $message);
        $message = str_replace('{resim_dort}', $resim_dort, $message);
        $message = str_replace('{İlanGirisTarihi}', $giris_tarihi, $message);
        $message = str_replace('{İlanGuncellenmeTarihi}', $dzltm_tarihi, $message);
        $message = str_replace('{İlanBasligi}', $urunadi, $message);
        $message = str_replace('{Aciklama}', $aciklama, $message);
        $message = str_replace('{Fiyat}', $fiyat, $message);
        $message = str_replace('{Kategori}', $kategoriadi, $message);
        $message = str_replace('{İmarDurumu}', $imar_durumu, $message);
        $message = str_replace('{Metrekare}', $arsa_metrekare, $message);
        $message = str_replace('{MetrekareFiyatı}', $metrekare_fiyat, $message);
        $message = str_replace('{AdaNo}', $ada_no, $message);
        $message = str_replace('{ParselNo}', $parsel_no, $message);
        $message = str_replace('{PaftaNo}', $pafta_no, $message);
        $message = str_replace('{Emsal}', $emsal, $message);
        $message = str_replace('{TapuDurumu}', $tapu_durumu, $message);
        $message = str_replace('{KatKarşılığı}', $kat_karsiligi, $message);
        $message = str_replace('{KrediyeUygunmu}', $arsa_krediye_uygun, $message);
        $message = str_replace('{Kimden}', $adsoyad, $message);
        $message = str_replace('{Telefon}', $telefon, $message);
        $message = str_replace('{Takas}', $arsa_takas, $message);
    }
    // HTML şablonunu oku


    // PHPMailer kullanarak e-posta gönderimi
    $mail = new PHPMailer(true);

    try {
        // SMTP ayarları
        $mail->isSMTP();
        $mail->Host = $mail_host; // Örnek SMTP sunucusu, kendi SMTP sunucunuzu kullanın
        $mail->SMTPAuth = true;
        $mail->Username = $mail_username; // SMTP kullanıcı adı
        $mail->Password = $mail_password; // SMTP şifresi
        if ($mail_secure === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($mail_secure === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }
        $mail->Port = $mail_port;

        // Gönderici bilgileri
        $mail->setFrom($mail_username, 'Emlak & Müteahit - Proje');
        $mail->addAddress($email, "$ad $soyad");
        $mail->CharSet = "UTF-8";


        // E-posta içeriği
        $mail->isHTML(true);
        $mail->Subject = $urunadi;
        $mail->Body = $message;

        $mail->send();

        // Başarılı gönderimleri kaydet
        $kayit = $con->prepare("INSERT INTO gonderilenler (kisi_id) VALUES (?)");
        $kayit->execute([$kisi_id]);

        echo "E-posta başarıyla gönderildi: $email\n";
        header("Location: ../../index.php");
    } catch (Exception $e) {
        echo "E-posta gönderilirken hata oluştu: {$mail->ErrorInfo}\n";
    }
}
