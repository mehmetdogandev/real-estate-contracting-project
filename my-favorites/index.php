<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; 
// favori boşsa uyar
if (!isset($_SESSION['favori']) || empty($_SESSION['favori'])) {
    echo "<br/>
 <div class='container mt-5 mb-5'><div class='col-md-12'><div class='alert alert-danger text-center shadow-sm rounded-4 p-4 fs-5'>
 <i class='fas fa-heart-broken fa-lg me-2 text-danger'></i>
 Favorilerinize eklenmiş bir ürün yok!
 </div></div></div><br/><br/>";
}
// favori boş değilse ürünleri listele
else {
    // favorideki ürün bilgilerini veritabanından okuyan kodlar burada yer alacak
    // favorideki ürün id'lerini diziye kaydet
    $ids = array();
    if (isset($_SESSION['favori']) || !empty($_SESSION['favori'])) {
        foreach ($_SESSION['favori'] as $id => $value) {
            array_push($ids, $id);
        }
        $ids_arr = str_repeat('?,', count($ids) - 1) . '?';
    } else {
        $ids_arr = 0;
    }

include $_SERVER['DOCUMENT_ROOT'] . '/config/vtabani.php'; 
    // favorideki ürünleri getiren sorgu
    $sorgu = "SELECT urunler.id, urunler.urunadi, kategoriler.kategoriadi, il.sehir, ilce.ilce, urunler.fiyat, urunler.resim, urunler.evarsa_id 
 FROM urunler 
 LEFT JOIN il ON il.id=urunler.il_id
 LEFT JOIN ilce ON ilce.id=urunler.ilce_id
 LEFT JOIN kategoriler ON kategoriler.id=urunler.kategori_id
 WHERE urunler.id IN ({$ids_arr}) ORDER BY urunler.urunadi";
    // sorguyu hazırla
    $stmt = $con->prepare($sorgu);
    // sorguyu çalıştır
    $stmt->execute($ids);

?>
    <style>
        .favori-tablo th {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #fff;
            font-weight: 600;
            border: none;
            font-size: 16px;
            letter-spacing: 0.5px;
        }
        .favori-tablo td {
            vertical-align: middle;
            background: #f8fafc;
            border: none;
            font-size: 15px;
        }
        .favori-tablo tr {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(59,130,246,0.04);
        }
        .favori-tablo img {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(59,130,246,0.08);
        }
        .favori-tablo .urun-sil {
            color: #ef4444;
            transition: color 0.2s;
        }
        .favori-tablo .urun-sil:hover {
            color: #b91c1c;
            text-decoration: none;
        }
        .favori-baslik {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
            padding: 24px 0 16px 0;
            margin-bottom: 0;
            text-align: center;
            box-shadow: 0 2px 12px rgba(30,64,175,0.08);
        }
        @media (max-width: 576px) {
            .favori-tablo th, .favori-tablo td {
                font-size: 13px;
                padding: 8px 4px;
            }
            .favori-baslik {
                font-size: 1.2rem;
                padding: 16px 0 10px 0;
            }
        }
    </style>
    <div class="container mt-4 mb-5">
        <div class="favori-baslik mb-0">
            <h3 class="mb-0"><i class="fas fa-heart me-2"></i>Favorilerim</h3>
        </div>
        <div class="table-responsive shadow rounded-bottom-4">
            <table class="table table-bordered favori-tablo mb-0">
                <thead>
                    <tr>
                        <th>Resim</th>
                        <th>Başlık</th>
                        <th>İl / İlçe</th>
                        <th>Kategori</th>
                        <th>Fiyat</th>
                        <th>Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $urun_toplami = 0;
                    $urun_sayisi = 0;
                    // Sepetteki ilanları listeleyen döngü
                    while ($kayit = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($kayit);
                        $adet = $_SESSION['favori'][$id]['adet'];
                        $urun_sayisi += $adet;
                        $urun_toplami += $fiyat * $adet;
                    ?>
                        <tr>
                            <td>
                                <?php echo $resim ? "<img src='content/images/{$resim}' alt='{$urunadi}' class='img-fluid img-thumbnail' width='80' />" : "<img src='content/images/gorsel-yok.jpg' class='img-fluid img-thumbnail' width='80' />"; ?>
                            </td>
                            <td class="text-left">
                                <h6 class="mb-1"><a href="/urundetay.php?id=<?php echo $id; ?>" class="link2 text-primary fw-semibold"><?php echo $urunadi; ?></a></h6>
                            </td>
                            <td>
                                <h6 class="mb-1"><?php echo $sehir . "/" . $ilce; ?></h6>
                            </td>
                            <td>
                                <h6 class="mb-1"><?php
                                    if ($evarsa_id == "1") {
                                        $evORarsa = "Ev";
                                    } else if ($evarsa_id == "2") {
                                        $evORarsa = "Arsa";
                                    }
                                    echo $kategoriadi . " " . $evORarsa; ?></h6>
                            </td>
                            <td>
                                <span class="badge bg-gradient" style="background: linear-gradient(45deg,#f59e0b,#fbbf24); color:#fff; font-size:15px;">
                                    <?php echo number_format($fiyat, 0, ',', '.'); ?>&#8378;
                                </span>
                            </td>
                            <td>
                                <a href="#" class="link2 urun-sil" id="<?php echo $id; ?>" title="Favorilerden Kaldır">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    } // while döngüsü sonu
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>