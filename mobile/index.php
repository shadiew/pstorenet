<?php
session_start();
require("../lib/mainconfig.php");
$msg_type = "nothing";

/* CHECK FOR MAINTENANCE */
if ($cfg_mt == 1) {
    die("Web is under maintenance.");
} else {

    /* CHECK USER SESSION */
    if (isset($_SESSION['user'])) {
        $sess_username = $_SESSION['user']['username'];
        $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
        $data_user = mysqli_fetch_assoc($check_user);
        if (mysqli_num_rows($check_user) == 0) {
            header("Location: " . $cfg_baseurl . "/logout/");
        } else if ($data_user['status'] == "Suspended") {
            header("Location: " . $cfg_baseurl . "/logout/");
        }

        /* DATA FOR DASHBOARD */
        $check_order = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username' AND status = 'Success' OR user = '$sess_username' AND status = 'Pending' OR user = '$sess_username' AND status = 'Processing' OR user = '$sess_username' AND status = 'In Progress'");
        $data_order = mysqli_fetch_assoc($check_order);
        $number_order = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username'"));
        $number_order_completed = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Success'"));
        $number_order_pending = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Pending' OR user = '$sess_username' AND status = 'Processing' OR user = '$sess_username' AND status = 'In Progress'"));
        $count_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users"));

        /* DATA FOR ORDERS STATISTICS CHART */
        $date_1 = date('Y-m-d', (strtotime('-5 day', strtotime($date))));
        $date_2 = date('Y-m-d', (strtotime('-4 day', strtotime($date))));
        $date_3 = date('Y-m-d', (strtotime('-3 day', strtotime($date))));
        $date_4 = date('Y-m-d', (strtotime('-2 day', strtotime($date))));
        $date_5 = date('Y-m-d', (strtotime('-1 day', strtotime($date))));
        $date_6 = $date;

        $count_c_date_1 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_1'"));
        $count_c_date_2 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_2'"));
        $count_c_date_3 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_3'"));
        $count_c_date_4 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_4'"));
        $count_c_date_5 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_5'"));
        $count_c_date_6 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_6'"));

        $count_p_date_1 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_1'"));
        $count_p_date_2 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_2'"));
        $count_p_date_3 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_3'"));
        $count_p_date_4 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_4'"));
        $count_p_date_5 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_5'"));
        $count_p_date_6 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_6'"));

        $check_order_today = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username' AND status = 'Success' AND date = '$date' OR user = '$sess_username' AND status = 'Pending' AND date = '$date' OR user = '$sess_username' AND status = 'Processing' AND date = '$date' OR user = '$sess_username' AND status = 'In Progress' AND date = '$date'");
        $data_order_today = mysqli_fetch_assoc($check_order_today);

        /* GENERAL WEB SETTINGS */
        $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
        $data_settings = mysqli_fetch_assoc($check_settings);

        $email = $data_user['email'];
        $hp = $data_user['nohp'];
        /* if ($email == "") {
    header("Location: ".$cfg_baseurl2."settings.php");
    } */
    } else {
        header("Location: home");
    }
    $title = "Dashboard";
    include("../lib/header.php");
    if (isset($_SESSION['user'])) {
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
    <?php echo $data_settings['seo_meta']; ?>
    <?php echo $data_settings['seo_analytics']; ?>

    <!-- favicon -->
    <link rel="icon" type="image/png" href="<?php echo $data_settings['link_fav']; ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo $data_settings['link_fav']; ?>">

    <!-- CSS Libraries-->
    <!-- bootstrap v4.6.0 -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!--
        theiconof v3.0
        https://www.theiconof.com/search
     -->
    <link rel="stylesheet" href="assets/css/icons.css">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="assets/css/remixicon.css">
    <!-- Swiper 6.4.11 -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
    <!-- Owl Carousel v2.3.4 -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- normalize.css v8.0.1 -->
    <link rel="stylesheet" href="assets/css/normalize.css">

    <!-- manifest meta -->
    <link rel="manifest" href="_manifest.json" />
    
</head>


<body class="bg-snow">

    <!-- Start em_loading -->
    <section class="em_loading" id="loaderPage">
        <div class="spinner_flash"></div>
    </section>
    <!-- End. em_loading -->

    <div id="wrapper">
        <div id="content">
            <!-- Start main_haeder -->
            <header class="main_haeder header-sticky multi_item header-white">
                <div class="em_menu_sidebar">
                    <img src="<?php echo $data_settings['link_logo_dark']; ?>" width="100px">
                </div>
                
                <div class="em_side_right">
                    <a href="notif" class="btn justify-content-center relative color_svg">
                        <svg id="Iconly_Two-tone_Notification" data-name="Iconly/Two-tone/Notification"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <g id="Notification" transform="translate(3.5 2)">
                                <path id="Path_425"
                                    d="M0,11.787v-.219A3.6,3.6,0,0,1,.6,9.75,4.87,4.87,0,0,0,1.8,7.436c0-.666,0-1.342.058-2.009C2.155,2.218,5.327,0,8.461,0h.078c3.134,0,6.306,2.218,6.617,5.427.058.666,0,1.342.049,2.009A4.955,4.955,0,0,0,16.4,9.759a3.506,3.506,0,0,1,.6,1.809v.209a3.566,3.566,0,0,1-.844,2.39A4.505,4.505,0,0,1,13.3,15.538a45.078,45.078,0,0,1-9.615,0A4.554,4.554,0,0,1,.835,14.167,3.6,3.6,0,0,1,0,11.787Z"
                                    transform="translate(0 0)" fill="none" stroke="#200e32" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"></path>
                                <path id="Path_421"
                                    d="M0,0A3.061,3.061,0,0,0,2.037,1.127,3.088,3.088,0,0,0,4.288.5,2.886,2.886,0,0,0,4.812,0"
                                    transform="translate(6.055 18.852)" fill="none" stroke="#200e32"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                    stroke-width="1.5" opacity="0.4"></path>
                            </g>
                        </svg>
                        <span class="flashCircle"></span>
                    </a>
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start banner__wallet -->
            <section class="banner__wallet">
                <div class="emhead d-flex align-items-center justify-content-between">
                    <div class="item__total">
                        <span>Sisa Saldo</span>
                        <?php
                                                    if ($data_user['balance'] == "0" or $data_user['balance'] < 0) {
                                                    ?>
                        <h2><?php echo rupiah($data_user['balance']); ?></h2>
                        <?php
                                        } ?>
                                        <?php
                                        if ($data_user['balance'] > 0) {
                                        ?>
                        <h2><?php echo rupiah($data_user['balance']); ?></h2>
                        <?php
                                        } ?>
                    </div>
                    <div class="side__right">
                        <a href="deposit" class="btn btn_balance">+ Topup Saldo</a>
                    </div>
                </div>
            </section>
            <!-- End. banner__wallet -->

            <main class="main_Wallet_index">
                <section class="np__bkOperationsService padding-25">
                    <div class="em__actions">
                        <a href="order_tiktok" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/tiktok.png" width="50" height="50">
                            </div>
                            <span>Tiktok</span>
                        </a>
                        <a href="order_facebook" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/facebook.png" width="50" height="50">
                            </div>
                            <span>Facebook</span>
                        </a>
                        <a href="order_instagram" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/instagram.png" width="50" height="50">
                            </div>
                            <span>Instagram</span>
                        </a>
                        <a href="order_youtube" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/youtube.png" width="50" height="50">
                            </div>
                            <span>Youtube</span>
                        </a>
                        <a href="order_twitter" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/twitter.png" width="50" height="50">
                            </div>
                            <span>Twitter</span>
                        </a>
                        
                    </div>
                    <br>
                    <div class="em__actions">
                        <a href="order_spotify" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/spotify.png" width="50" height="50">
                            </div>
                            <span>Spotify</span>
                        </a>
                        <a href="order_tinder" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/tinder.png" width="50" height="50">
                            </div>
                            <span>Tinder</span>
                        </a>
                        <a href="order_twitch" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/twitch.png" width="50" height="50">
                            </div>
                            <span>Twitch</span>
                        </a>
                        <a href="order_pinterest" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/pinterest.png" width="50" height="50">
                            </div>
                            <span>Pinterest</span>
                        </a>
                        <a href="order_discord" class="btn">
                            <div class="icon">
                                <img src="assets/img/sosmed/discord.png" width="50" height="50">
                            </div>
                            <span>Discord</span>
                        </a>
                    </div>
                </section>
                <section class="banner_swiper npSwiper__ads bg-snow np_Package_ac padding-t-10 mt-0 padding-b-40">
                    <div class="title d-flex justify-content-between align-items-center padding-l-20 padding-r-20">
                        <div>
                            <h3 class="size-18 weight-500 color-secondary m-0">Promo Pilihan</h3>
                            
                        </div>
                        <a href="#" class="d-block color-blue size-14 m-0 hover:color-blue">See all</a>
                    </div>
                    <!-- Swiper -->
                    <div class="owl-carousel owl-theme em-owlCentred em_owl_swipe">
                        <?php
                                                    $limit = 15; // EDIT LIMIT FOR NUMBER OF UPDATES
                                                    $check_news = mysqli_query($db, "SELECT * FROM slider ORDER BY id DESC LIMIT $limit");
                                                    $no = 1;
                                                    while ($data_news = mysqli_fetch_assoc($check_news)) {
                                                    ?>
                        <div class="item em_item">
                            <a href="<?php echo $data_news['url']; ?>" class="em_cover_img text-decoration-none">
                                <img src="<?php echo $data_news['gambar']; ?>" alt="">
                            </a>
                        </div>
                        <?php
                                                    $no++;
                                                    }
                                                    ?>
                    </div>
                </section>

                <section class="em_swiper_products emCoureses__grid padding-t-10 mt-0 margin-b-40">
                <!-- em_title_swiper -->
                <div class="em_title_swiper">
                    <div class="txt">
                        <h2>Live Nonton TV</h2>
                    </div>
                    <div class="item_link">
                        <a href="tv">View all</a>
                    </div>
                </div>
                <div class="em_bodyCarousel padding-t-20">
                    <div class="owl-carousel owl-theme owlThemeCorses">

                        <?php
                                                    $limit = 5; // EDIT LIMIT FOR NUMBER OF UPDATES
                                                    $check_news = mysqli_query($db, "SELECT * FROM livetv WHERE slider = 'Aktif' ORDER BY id DESC LIMIT $limit");
                                                    $no = 1;
                                                    while ($data_news = mysqli_fetch_assoc($check_news)) {
                                                    ?>
                        <!-- item -->
                        <div class="item">
                            <div class="em_itemCourse_grid">
                                <a href="viewtv.php?id_url=<?php echo $data_news['id_url']; ?>" class="card">
                                    <div class="cover_card">
                                        <img src="<?php echo $data_news['image']; ?>" class="card-img-top" alt="img">
                                        <div class="icon_play bg-primary">
                                            <i class="tio-play"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="head_card">
                                           
                                        </div>
                                        <h5 class="card-title">
                                           <?php echo $data_news['judul']; ?>
                                        </h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- item -->
                        <?php
                                                    $no++;
                                                    }
                                                    ?>
                    </div>
                </div>
            </section>

            

            </main>

            

        </div>
        <!-- Start em_main_footer -->
        <footer class="em_main_footer ouline_footer with__text">
            <div class="em_body_navigation -active-links">
                <div class="item_link">
                    <a href="<?php echo $cfg_baseurl; ?>/mobile" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/home.png" width="34" height="34">
                        </div>
                        <div class="txt__tile">Beranda</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="riwayatorder" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/riwayat.png" width="34" height="34">
                        </div>

                        <div class="txt__tile">Riwayat</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="deposit" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/wallet.png" width="34" height="34">
                        </div>
                        
                        <div class="txt__tile">Deposit</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="bantuan" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/chat.png" width="34" height="34">
                        </div>
                        
                        <div class="txt__tile">Bantuan</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="akun.php" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/user.png" width="34" height="34">

                        </div>
                        <div class="txt__tile">Akun</div>
                    </a>
                </div>
            </div>
        </footer>
        <!-- End. em_main_footer -->

        

        

    </div>

    <!-- jquery -->
    <script src="assets/js/jquery-3.6.0.js"></script>
    <!-- popper.min.js 1.16.1 -->
    <script src="assets/js/popper.min.js"></script>
    <!-- bootstrap.js v4.6.0 -->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Owl Carousel v2.3.4 -->
    <script src="assets/js/vendor/owl.carousel.min.js"></script>
    <!-- Swiper 6.4.11 -->
    <script src="assets/js/vendor/swiper-bundle.min.js"></script>
    <!-- sharer 0.4.0 -->
    <script src="assets/js/vendor/sharer.js"></script>
    <!-- short-and-sweet v1.0.2 - Accessible character counter for input elements -->
    <script src="assets/js/vendor/short-and-sweet.min.js"></script>
    <!-- jquery knob -->
    <script src="assets/js/vendor/jquery.knob.min.js"></script>
    <!-- main.js -->
    <script src="assets/js/main.js" defer></script>
    <!-- PWA app service registration and works js -->
    <script src="assets/js/pwa-services.js"></script>
</body>
</html>
<?php
    }
    
}
    ?>