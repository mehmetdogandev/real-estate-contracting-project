<?php  include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'; 

$ilanId = isset($_GET['id']) ? $_GET['id'] : die('HATA: Kayıt bulunamadı.');

?>
<div class="container">
    <div class="page-header">
        <h1>Kişi Listesi</h1>
    </div>
    <!-- Kayıtları listeleyecek PHP kodları bu alana eklenecek -->
    <?php
    // SAYFALANDIRMA DEĞİŞKENLERİ
    $sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 1;
    $sayfa_kayit_sayisi = 5;
    $ilk_kayit_no = ($sayfa_kayit_sayisi * $sayfa) - $sayfa_kayit_sayisi;

    // veritabanı bağlantı dosyasını çağır

    // silme mesajı burada yer alacak
    $islem = isset($_GET['islem']) ? $_GET['islem'] : "";
    if ($islem == 'silindi') {
        echo "<div class='alert alert-success'>Kayıt silindi.</div>";
    } else if ($islem == 'silinemedi') {
        echo "<div class='alert alert-danger'>Kayıt silinemedi.</div>";
    }

    // sayfada görüntülenecek kayıtları seç
    $aranan = isset($_GET['aranan']) ? $_GET['aranan'] : "";
    $arama_sarti = isset($_GET['aranan']) ? "%" . $_GET['aranan'] . "%" : "%";
    $sorgu = "SELECT id, ad, soyad, email, son_gonderilen_email_tarih
              FROM kisiler
              WHERE id LIKE :aranan OR ad LIKE :aranan OR soyad LIKE :aranan OR email LIKE :aranan
              ORDER BY id DESC
              LIMIT :ilk_kayit_no, :sayfa_kayit_sayisi";
    $stmt = $con->prepare($sorgu);
    $stmt->bindParam(":ilk_kayit_no", $ilk_kayit_no, PDO::PARAM_INT);
    $stmt->bindParam(":sayfa_kayit_sayisi", $sayfa_kayit_sayisi, PDO::PARAM_INT);
    $stmt->bindParam(":aranan", $arama_sarti);
    $stmt->execute();

    // geriye dönen kayıt sayısı
    $sayi = $stmt->rowCount();

    // kayıt ekleme sayfasının linki
    echo "<a href='../../mailekle/ekle_mail.php' class='btn btn-primary m-b-1em col-xs col-md m-r-1em pull-left'> 
 <span class='glyphicon glyphicon glyphicon-plus'></span> Yeni Kişi</a>";

    echo "<a href='#' class='btn btn-primary m-b-1em col-xs col-md m-r-1em pull-left' id='selectAllButton'> 
 <span class='glyphicon glyphicon-check'></span> Tümünü Seç</a>";
    ?>
    <!-- kişi arama formu -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <div class="row">
            <div class="col-xs-6 col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="ID'ye göre kişi ara..." name="aranan" value="<?php echo isset($_GET['aranan']) ? $_GET['aranan'] : ""; ?>" />
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form id="emailForm">
        <?php
        //kayıt varsa listele
        if ($sayi > 0) {

            // kayıtlar burada listelenecek
            echo "<table class='table table-hover table-responsive table-bordered favori-tablo'>";
            //tablo başlangıcı
            //tablo başlıkları
            echo "<tr>";
            echo "<th></th>";
            echo "<th>ID</th>";
            echo "<th>Ad</th>";
            echo "<th>Soyad</th>";
            echo "<th>Email</th>";
            echo "<th>Son Gönderilen Email Tarihi</th>";
            echo "<th>İşlem</th>";
            echo "</tr>";

            // tablo içeriği burada yer alacak
            while ($kayit = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($kayit);

                // her kayıt için yeni bir tablo satırı oluştur
                echo "<tr>";
                echo "<td><input type='checkbox' name='sil_id[]' value='{$id}'/></td>";
                echo "<td>{$id}</td>";
                echo "<td>{$ad}</td>";
                echo "<td>{$soyad}</td>";
                echo "<td>{$email}</td>";
                echo "<td>{$son_gonderilen_email_tarih}</td>";
                echo "<td>";
                // kayıt detay sayfa bağlantısı
             
                // kayıt güncelleme sayfa bağlantısı
                echo "<a href='duzelt.php?id={$id}' class='btn btn-primary m-r-1em'> <span
class='glyphicon glyphicon glyphicon-edit'></span> Düzelt</a>";
                // kayıt silme bağlantısı
                echo "<a href='#' onclick='silme_onay({$id});' class='btn btn-danger'> <span
class='glyphicon glyphicon glyphicon-remove-circle'></span> Sil</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>"; // tablo sonu
            // SAYFALANDIRMA
            // toplam kayıt sayısını hesapla
            $sorgu = "SELECT COUNT(*) as kayit_sayisi FROM kisiler
          WHERE id LIKE :aranan OR ad LIKE :aranan OR soyad LIKE :aranan OR email LIKE :aranan";
            $stmt = $con->prepare($sorgu);
            $stmt->bindParam(":aranan", $arama_sarti);

            // sorguyu çalıştır
            $stmt->execute();

            // kayıt sayısını oku
            $kayit = $stmt->fetch(PDO::FETCH_ASSOC);
            $kayit_sayisi = $kayit['kayit_sayisi'];
            // kayıtları sayfalandır
            $sayfa_url = "liste.php?id=$ilanId";
            include_once "sayfalama.php";
        }
        // kayıt yoksa mesajla bildir
        else {
            echo "<div class='alert alert-danger'>Listelenecek kayıt bulunamadı.</div>";
        }
        ?>
        <td>
            <?php if ($sayi > 0) { ?>
                <button type="button" class="btn btn-primary" id="submitButton">
                    <i class="fas fa-envelope"></i> Seçilen Kişilere E-posta Gönder
                </button>
            <?php } ?>
    </form>
    </td>
</div> <!-- /container -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php';   ?>

<!-- Kayıt silme onay kodları bu alana eklenecek -->
<script type='text/javascript'>
    // kayıt silme işlemini onayla
    function silme_onay(id) {

        var cevap = confirm('Kaydı silmek istiyor musunuz?');
        if (cevap) {
            // kullanıcı evet derse,
            // id bilgisini sil.php sayfasına yönlendirir
            window.location = 'sil.php?id=' + id;
        }
    }
</script>

<!--- SweetAlert destekli çoklu silme --->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type='text/javascript'>
    $(document).ready(function() {
        $('#btn_sil').click(function() {
            var id = [];
            $(':checkbox:checked').each(function(i) {
                id[i] = $(this).val();
            });
            if (id.length === 0) { //dizi boşsa bilgi ver
                swal("Silmek için seçilmiş kişi yok!", {
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                });
            } else {
                swal({ // onay al
                        title: "Emin misiniz?",
                        text: "Silme işlemi geri alınamaz!",
                        icon: "warning",
                        buttons: ["Hayır", "Evet"],
                        dangerMode: true,
                        closeModal: false,
                    })
                    .then(function(yes) {
                        if (yes)
                            $.ajax({
                                cache: false,
                                type: 'POST',
                                url: 'coklusil.php',
                                data: {
                                    id: id
                                },
                                success: function(sonuc) {
                                    swal("Seçili kişiler silindi!", {
                                        icon: "success",
                                        buttons: false,
                                        timer: 3000,
                                    });
                                    // silinen kayıtları html tablosundan da sil
                                    jQuery('input:checkbox:checked').parents("tr").remove();
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    alert(textStatus + errorThrown);
                                }
                            });
                    })
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('submitButton').addEventListener('click', function() {
            // Get the form element
            const form = document.getElementById('emailForm');
            const checkboxes = form.querySelectorAll('input[name="sil_id[]"]:checked');
            const selectedIds = [];

            // Gather selected checkbox values
            checkboxes.forEach((checkbox) => {
                selectedIds.push(checkbox.value);
            });

            // Check if any checkboxes are selected
            if (selectedIds.length > 0) {
                const ilanId = "<?php echo $ilanId; ?>"; // assuming $ilanId is available in the scope
                const kisilerParam = selectedIds.map(id => `kisiler[]=${encodeURIComponent(id)}`).join('&');
                const queryString = `id=${encodeURIComponent(ilanId)}&${kisilerParam}`;

                // Redirect to mailgonder.php with the query string
                window.location.href = `mailgonder.php?${queryString}`;
            } else {
                alert('Lütfen en az bir kişi seçin.');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('selectAllButton').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="sil_id[]"]');

            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
        });
    });
</script>