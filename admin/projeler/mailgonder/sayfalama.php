<?php
echo "<ul class='pagination pull-left margin-zero mt0'>";

// önceki sayfa butonu
if ($sayfa > 1) {
    $onceki_sayfa = $sayfa - 1;
    echo "<li>";
    echo "<a href='{$sayfa_url}?id={$ilanId}&sayfa={$onceki_sayfa}&aranan={$aranan}'>";
    echo "<span style='margin:0.5em;'>&laquo;</span>";
    echo "</a>";
    echo "</li>";
}

// tıklanabilir sayfa numaraları
$sayfa_sayisi = ceil($kayit_sayisi / $sayfa_kayit_sayisi);
$aralik = 2;
$baslangic_no = max($sayfa - $aralik, 1);
$bitis_no = min($sayfa + $aralik, $sayfa_sayisi);

for ($x = $baslangic_no; $x <= $bitis_no; $x++) {
    if ($x > 0 && $x <= $sayfa_sayisi) {
        if ($x == $sayfa) {
            echo "<li class='active'>";
            echo "<a href='javascript::void();'>{$x}</a>";
            echo "</li>";
        } else {
            echo "<li>";
            echo "<a href='{$sayfa_url}?id={$ilanId}&sayfa={$x}&aranan={$aranan}'>{$x}</a>";
            echo "</li>";
        }
    }
}

// sonraki sayfa butonu
if ($sayfa < $sayfa_sayisi) {
    $sonraki_sayfa = $sayfa + 1;
    echo "<li>";
    echo "<a href='{$sayfa_url}?id={$ilanId}&sayfa={$sonraki_sayfa}&aranan={$aranan}'>";
    echo "<span style='margin:0 .5em;'>&raquo;</span>";
    echo "</a>";
    echo "</li>";
}

echo "</ul>";
?>
<!-- sayfa yönlendirme menüsü -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
    <div class="row">
        <div class="col-xs-3 col-md-2 pull-right">
            <div class="input-group">
                <select name="sayfa" class="form-control no-arrow">
                    <?php
                    for ($i = 1; $i <= $sayfa_sayisi; $i++) {
                        echo "<option value=" . $i . ($i == $sayfa ? " selected" : "") . ">" . $i . "</option>";
                    }
                    ?>
                </select>
                <input type="hidden" name="id" value="<?php echo $ilanId; ?>">
                <input type="hidden" name="aranan" value="<?php echo $aranan; ?>">
                <div class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                        <span>Git</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
