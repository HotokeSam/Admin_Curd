<?php
ini_set('display_errors', 1);
session_start();
define('DB_HOST', 'database');
define('DB_USER', 'bendeer');
define('DB_PASS', 'bendeer');
define('DB_NAME', 'vvtadmin');
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($con, "SET NAMES utf8");

ini_set('memory_limit', '1000M');
date_default_timezone_set('Europe/Budapest');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

function formatMoney($amount) {
    return ((!isset($amount) || $amount === '' || $amount == 0 || $amount === '0') ? '0 Ft' : number_format($amount, 0, "", " ")." Ft");
}
$statuses = [
    'wait-to-pay' => 'Fizetésre vár',
    'paid' => 'Fizetve',
    'processing' => 'Folyamatban',
    'in-transit' => 'Szállítás alatt',
    'done' => 'Kiszállítva',
    'deleted' => 'Törölt'
];

$paymentMethod = [
    's' => 'Stripe',
    'a' => 'Átutalás'
];

$shippingMethod = [
    'm' => 'MPL házhozszállítás',
    'g' => 'GLS házhozszállítás'
];

$pets = [
    'dog' => 'Gugyus',
    'cat' => 'Macska',
    '' => 'Nincs',
    '0' => 'Nincs',
    '-1' => 'Nincs',
    'none' => 'Nincs'
];

$paymentCosts = [
    's' => 0,
    'a' => 450
];

$shippingCosts = [
    'm' => 6500,
    'g' => 5500
];
$stiliz = [
    'á' => 'a',
    'é' => 'e',
    'ó' => 'o',
    'ö' => 'o',
    'ő' => 'o',
    'ü' => 'u',
    'ú' => 'u',
    'ű' => 'u',
    'í' => 'i',
    '(' => '',
    ')' => '',
    ':' => '',
    '@' => '',
    '.' => '',
    ',' => '',
    ' ' => '-',
    ' ' => '-'
];
function orsi_destroy() {
    session_destroy();
}
?>
