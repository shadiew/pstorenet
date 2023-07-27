<?php
session_start();
require("../lib/mainconfig.php");

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$cfg_baseurl."/logout/");
    }
    $email = $data_user['email'];
    if ($email == "") {
    header("Location: ".$cfg_baseurl."settings");
    }
    
    $title = "News & Updates";
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
    <title><?php echo $data_settings['web_name']; ?> | Notifikasi</title>
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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="<?php echo $cfg_baseurl; ?>/mobile">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        Activities
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
                                        $query_list = "SELECT * FROM news ORDER BY id DESC"; // edit
                                        $records_per_page = 5; // edit

                                        $starting_position = 0;
                                        if(isset($_GET["page_no"])) {
                                            $starting_position = ($_GET["page_no"]-1) * $records_per_page;
                                        }
                                        $new_query = $query_list." LIMIT $starting_position, $records_per_page";
                                        $new_query = mysqli_query($db, $new_query);
                                        // end paging config
                                                $no = 1;
                                                while ($data_news = mysqli_fetch_assoc($new_query)) {
                                                    if($data_news['status'] == "INFO") {
                                                        $label = "blue";
                                                        $label2 = "INFO";
                                                    } else if($data_news['status'] == "NEW SERVICE") {
                                                        $label = "pink";
                                                        $label2 = "NEW SERVICE";
                                                    } else if($data_news['status'] == "SERVICE") {
                                                        $label = "blue";
                                                        $label2 = "SERVICE";                                                        
                                                    } else if($data_news['status'] == "MAINTENANCE") {
                                                        $label = "red";
                                                        $label2 = "MAINTENANCE";                                                                                                        
                                                    } else if($data_news['status'] == "UPDATE") {
                                                        $label = "yellow";
                                                        $label2 = "UPDATE";                     
                                                    }
                                        ?>
                <div class="item__activiClassic border-b">
                    <div class="media">
                        <div class="icon bg-<?php echo $label; ?>">
                            <svg class="color-secondary" id="Iconly_Two-tone_Notification"
                            data-name="Iconly/Two-tone/Notification" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24">
                            <g id="Notification" transform="translate(3.5 2)">
                                <path id="Path_425"
                                    d="M0,11.787v-.219A3.6,3.6,0,0,1,.6,9.75,4.87,4.87,0,0,0,1.8,7.436c0-.666,0-1.342.058-2.009C2.155,2.218,5.327,0,8.461,0h.078c3.134,0,6.306,2.218,6.617,5.427.058.666,0,1.342.049,2.009A4.955,4.955,0,0,0,16.4,9.759a3.506,3.506,0,0,1,.6,1.809v.209a3.566,3.566,0,0,1-.844,2.39A4.505,4.505,0,0,1,13.3,15.538a45.078,45.078,0,0,1-9.615,0A4.554,4.554,0,0,1,.835,14.167,3.6,3.6,0,0,1,0,11.787Z"
                                    transform="translate(0 0)" fill="none" stroke="#200e32" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                <path id="Path_421"
                                    d="M0,0A3.061,3.061,0,0,0,2.037,1.127,3.088,3.088,0,0,0,4.288.5,2.886,2.886,0,0,0,4.812,0"
                                    transform="translate(6.055 18.852)" fill="none" stroke="#200e32"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                    stroke-width="1.5" opacity="0.4" />
                            </g>
                        </svg>

                        </div>
                        <div class="media-body">
                            <div class="txt">
                                <h2><?php echo $data_news['status']; ?></h2>
                                <p><?php echo nl2br($data_news['content']); ?></p>
                                <span><?php echo $data_news['date']; ?> <?php echo $data_news['time']; ?></span>
                            </div>
                        </div>
                    </div>
                    <a data-toggle="modal"
                                        data-target="#mdllContent-basic<?php echo $data_news['id']; ?>">
                    <div class="sideRight">
                        <i class="tio-chevron_right"></i>
                    </div>
                </a>
                </div>
                <!-- Modal Content -->
        <div class="modal transition-bottom screenFull defaultModal mdlladd__rate fade" id="mdllContent-basic<?php echo $data_news['id']; ?>"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable height-full">
                <div class="modal-content rounded-0">
                    <div class="modal-header padding-l-20 padding-r-20 justify-content-center">
                        <div class="itemProduct_sm">
                            <h1 class="size-18 weight-600 color-secondary m-0">Detail Konten</h1>
                        </div>
                        <div class="absolute right-0 padding-r-20">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="tio-clear"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="padding-t-20">
                            <p class="size-15 color-text">
                                <?php echo nl2br($data_news['content']); ?>
                            </p>
                            
                        </div>
                    </div>
                    <div class="modal-footer pt-3">
                        <a type="button" 
                            class="btn w-100 bg-primary m-0 color-white h-52 d-flex align-items-center rounded-10 justify-content-center" data-dismiss="modal" aria-label="Close">
                            Tutup
                        </a>
                    </div>
                </div>
            </div>
        </div>
                <?php
                                        $no++;
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
    header("Location: ".$cfg_baseurl);
}