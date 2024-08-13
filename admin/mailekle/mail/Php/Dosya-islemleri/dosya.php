<?php
/**
 * Created by kadirkasim.com
 * Developer: Kadir Kasim
 * Date: 1.04.2018
 * Time: 17:59
 */

// Dosya oluşturma veya var olan dosyayı açma
$filename = 'deneme.txt';

// Dosyayi acma (a+ kipi ile)
$file = fopen($filename, 'a+');

if ($file) {
    // Dosyaya yazma
    fwrite($file, 'Bu bir deneme yazisidir.' . PHP_EOL);

    // Dosya işaretçisini başa sar
    rewind($file);

    // Dosya Tum icerik okuma
    $content = fread($file, filesize($filename));
    echo "Dosya İçeriği:\n" . $content . "\n";

    // Satir satir okuma
    rewind($file); // işaretçiyi başa sar
    echo "Satır Satır Okuma:\n";
    while (!feof($file)) {
        $line = fgets($file);
        echo $line;
    }

    // Dosyanin sonuna geldigini soyler
    if (feof($file)) {
        echo "Dosyanin sonuna gelindi.\n";
    }

    // Dosyayi kapatir
    fclose($file);
} else {
    echo "Dosya açılamadı.";
}
?>
