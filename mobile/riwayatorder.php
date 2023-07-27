<?php
session_start();
require("../lib/mainconfig.php");

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
    $email = $data_user['email'];
    if ($email == "") {
        header("Location: " . $cfg_baseurl . "settings");
    }

    $title = "Order History";
    include("../lib/header.php");
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
    <title><?php echo $data_settings['web_name']; ?> | Riwayat Order</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords"
        content="<?php echo $data_settings['seo_keywords']; ?>" />

    <!-- favicon -->
    <link rel="icon" type="image/png" href="<?php echo $data_settings['link_fav']; ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo $data_settings['link_fav']; ?>">
    <?php echo $data_settings['seo_meta']; ?>
     <?php echo $data_settings['seo_analytics']; ?>

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


<body>

    <!-- Start em_loading -->
    <section class="em_loading" id="loaderPage">
        <div class="spinner_flash"></div>
    </section>
    <!-- End. em_loading -->

    <div id="wrapper">
        <div id="content">
            <!-- Start main_haeder -->
            <header class="main_haeder header-sticky multi_item">
                <div class="em_side_right">
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../mobile">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        Riwayat Pemesanan
                    </span>
                </div>
                <div class="em_side_right">
                    
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start emPage__activities -->
            <section class="emPage__activities _classic padding-t-60">
                <?php
                                        // start paging config
                                        $query_order = mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' ORDER BY id DESC");
                                        // end paging config
                                        while ($data_order = mysqli_fetch_assoc($query_order)) {
                                            if ($data_order['status'] == "Pending") {
                                                $label = "yellow";
                                            } else if ($data_order['status'] == "Processing") {
                                                $label = "blue";
                                            } else if ($data_order['status'] == "In Progress") {
                                                $label = "blue";
                                            } else if ($data_order['status'] == "Error") {
                                                $label = "red";
                                            } else if ($data_order['status'] == "Canceled") {
                                                $label = "red";
                                            } else if ($data_order['status'] == "Partial") {
                                                $label = "pink";
                                            } else if ($data_order['status'] == "Success") {
                                                $label = "blue";
                                            } else if ($data_order['status'] == "Completed") {
                                                $label = "blue";
                                            }
                                        ?>
                <div class="item__activiClassic border-b">
                    <div class="media">
                        <div class="icon bg-<?php echo $label; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"><path d="M3.5 11v3c0 3.771 0 5.657 1.172 6.828C5.843 22 7.729 22 11.5 22h1c3.771 0 5.657 0 6.828-1.172M20.5 11v3c0 1.17 0 2.158-.035 3"/><path d="M9.5 2h5m-5 0l-.652 6.517a3.167 3.167 0 1 0 6.304 0L14.5 2m-5 0H7.418c-.908 0-1.362 0-1.752.107a3 3 0 0 0-1.888 1.548M9.5 2l-.725 7.245a3.06 3.06 0 1 1-6.043-.904L2.8 8m11.7-6h2.082c.908 0 1.362 0 1.752.107a3 3 0 0 1 1.888 1.548c.181.36.27.806.448 1.696l.598 2.99a3.06 3.06 0 1 1-6.043.904L14.5 2Zm-5 19.5v-3c0-.935 0-1.402.201-1.75a1.5 1.5 0 0 1 .549-.549C10.598 16 11.065 16 12 16s1.402 0 1.75.201a1.5 1.5 0 0 1 .549.549c.201.348.201.815.201 1.75v3"/></g></svg>
                        </div>
                        <div class="media-body">
                            <div class="txt">
                                <h2><?php echo $data_order['service']; ?></h2>
                                <p>Pesanan #<?php echo $data_order['oid']; ?> <?php echo $data_order['status']; ?></p>
                                <span><?php
            $formattedDate = date("d M Y", strtotime($data_order['date']));
            $formattedTime = date("H:i", strtotime($data_order['time']));
            echo $formattedDate . " " . $formattedTime;
            ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="sideRight">
                        <i class="tio-chevron_right"></i>
                    </div>
                </div>
                <?php
                                        }
                                        ?>
                
            </section>
            <!-- End. emPage__activities -->
        </div>


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
            
        } else {
            header("Location: " . $cfg_baseurl);
        }
