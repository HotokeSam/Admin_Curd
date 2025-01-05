<?php
include("_/global.php");
ini_set('display_errors', 1);
$paths = explode("/",$_SERVER["REQUEST_URI"]); // Same as refresh_paths().
$page = $paths[1];
if(!isset($_SESSION['token'])) {
    $page = 'login';
} else {
    if ($page == '') {
        $page = 'main';
    }
}



?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="/view/style/main.css">
        <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
        <title>Vroom Admin</title>
    </head>
    <body>
        <div class="overlay hidden"></div>
        <div class="menubar">
            <div class="container">
                <div class="menubar-container desktop">
                    <a class="pd-all-10 mg-side-20" href="/">Főoldal</a>
                    <a class="pd-all-10 mg-side-20" href="/orders">Rendelések</a>
                    <a class="pd-all-10 mg-side-20" href="/products">Termékek</a>
                    <a class="pd-all-10 mg-side-20" href="/coupons">Kuponok</a>
                    <a class="pd-all-10 mg-side-20" href="/sliders">Sliderek</a>
                    <a class="pd-all-10 mg-side-20" href="/emails">E-mailek</a>
                    <a class="pd-all-10 mg-side-20" href="/users">Felhasználók</a>
                    <a class="pd-all-10 mg-side-20" class="logout" href="/logout">Kijelentkezés</a>
                </div>
                <div class="menubar-container mobile">
                    <i data-func="open.hamburger-menu" class="las la-bars"></i>
                    <div class="menupoints hamburger hidden">
                        <a class="pd-all-10 mg-side-20" href="/">Főoldal</a>
                        <a class="pd-all-10 mg-side-20" href="/orders">Rendelések</a>
                        <a class="pd-all-10 mg-side-20" href="/products">Termékek</a>
                        <a class="pd-all-10 mg-side-20" href="/coupons">Kuponok</a>
                        <a class="pd-all-10 mg-side-20" href="/sliders">Sliderek</a>
                        <a class="pd-all-10 mg-side-20" href="/emails">E-mailek</a>
                        <a class="pd-all-10 mg-side-20" href="/users">Felhasználók</a>
                        <a class="pd-all-10 mg-side-20" class="logout" href="/logout">Kijelentkezés</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="app">
            <div data-page="<?php echo $page; ?>" class="main  row mg-top-100">
                <div class="container pd-all-20 bg-white row">
            <?php 
                include($_SERVER['DOCUMENT_ROOT']."/pages/".$page.".php");
            ?>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.tiny.cloud/1/mrcxusog816kvegpxw75eoqxrap1par2ut95yhdlamfdz4zn/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="/app/js/script.js"></script>
    </body>
</html>