<?php

function ilanDetayTablosu($ilanId)
{
    $tuşlar = true;
    ob_start();
    require 'ilanDetayTablosu.php';
    return ob_get_clean();
}

function tamIlanDetayTablosu($ilanId)
{
    $tuşlar = false;
    ob_start();
    require 'tamIlanDetayTablosu.php';
    return ob_get_clean();
}
