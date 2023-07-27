<?php
session_start();
require("../../lib/mainconfig.php");
$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">

    <meta name="theme-color" content="#ffffff">
    <title><?php echo $data_settings['web_title']; ?></title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>" />

    <!-- favicon -->
    <link rel="icon" type="image/png" href="../assets/img/favicon/32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="../assets/img/favicon/favicon192.png">

    <!-- CSS Libraries-->
    <!-- bootstrap v4.6.0 -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!--
        theiconof v3.0
        https://www.theiconof.com/search
     -->
    <link rel="stylesheet" href="../assets/css/icons.css">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="../assets/css/remixicon.css">
    <!-- Swiper 6.4.11 -->
    <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">
    <!-- Owl Carousel v2.3.4 -->
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- normalize.css v8.0.1 -->
    <link rel="stylesheet" href="../assets/css/normalize.css">


    <!-- manifest meta -->
    <link rel="manifest" href="../_manifest.json" />
    <?php echo $data_settings['seo_meta']; ?>
     <?php echo $data_settings['seo_analytics']; ?>
</head>


<body>

    

    <div id="wrapper">
        <div id="content">
            <!-- Start main_haeder -->
            <header class="main_haeder header-sticky multi_item d-lfex justify-content-end">
                <div class="em_side_right">
                    
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start npPage_introDefault -->
            <section class="npPage_introDefault padding-t-70">
                <div class="cover">
                    <img src="../assets/img/0sd6f8.jpg" alt="">
                </div>

                <!-- Swiper -->
                <div class="swiper-container swiper-intro-default swiper__text padding-t-40">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="content_text">
                                <h2 class="txt_gradient">Layanan All Sosmed</h2>
                                <p>
                                    Menyediakan beragam layanan sosial media dalam satu apps.
                                </p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="content_text">
                                <h2 class="txt_gradient">Pembayaran Mudah.</h2>
                                <p>
                                    Sistem menggunakan pembayaran otomatis tanpa verifikasi admin.
                                </p>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="content_text">
                                <h2 class="txt_gradient">Mudah Digunakan.</h2>
                                <p>
                                    Sistem apps kami mudah digunakan dan ringan
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>

                <div class="npButtons_networks env-pb margin-b-20">
                    

                    <a href="../login" class="btn rounded-pill bg-primary">
                        
                        <span class="color-white">Login</span>
                    </a>
                    <a href="../daftar" class="btn rounded-pill bg-secondary">
                        
                        <span class="color-white">Daftar</span>
                    </a>
                </div>

            </section>
            <!-- End. npPage_introDefault -->


        </div>
    </div>

    <!-- jquery -->
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <!-- popper.min.js 1.16.1 -->
    <script src="../assets/js/popper.min.js"></script>
    <!-- bootstrap.js v4.6.0 -->
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Owl Carousel v2.3.4 -->
    <script src="../assets/js/vendor/owl.carousel.min.js"></script>
    <!-- Swiper 6.4.11 -->
    <script src="../assets/js/vendor/swiper-bundle.min.js"></script>
    <!-- sharer 0.4.0 -->
    <script src="../assets/js/vendor/sharer.js"></script>
    <!-- short-and-sweet v1.0.2 - Accessible character counter for input elements -->
    <script src="../assets/js/vendor/short-and-sweet.min.js"></script>
    <!-- jquery knob -->
    <script src="../assets/js/vendor/jquery.knob.min.js"></script>
    <!-- main.js -->
   
    <!-- PWA app service registration and works js -->
    <script src="../assets/js/pwa-services.js"></script>
    
</body>

</html>