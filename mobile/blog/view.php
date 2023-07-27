<?php
session_start();
require("../../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['level'] != "Developers") {
        header("Location: ".$cfg_baseurl);
    } else {
        if (isset($_GET['url'])) {
            $post_url = $_GET['url'];
            $check_target = mysqli_query($db, "SELECT * FROM blog WHERE url = '$post_url'");
            $data_target = mysqli_fetch_assoc($check_target);
            if (mysqli_num_rows($check_target) == 0) {
                header("Location: ".$cfg_baseurl."admin/service/");
            } else {
                $title = "Service Details";
                include("../../lib/header.php");
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
    <title><?php echo $data_settings['web_name']; ?> | Blog</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>" />
    <?php echo $data_settings['seo_meta']; ?>
    <?php echo $data_settings['seo_analytics']; ?>

    <!-- favicon -->
    <link rel="icon" type="image/png" href="<?php echo $data_settings['link_fav']; ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo $data_settings['link_fav']; ?>">

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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../blog">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        <!-- Something here.. -->
                    </span>
                </div>
                <div class="em_side_right">
                    
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start emPage__detailsBlog -->
            <section class="emPage__detailsBlog">
                <div class="emheader_cover">
                    <div class="cover">
                        <img src="<?php echo $data_target['image']; ?>" alt="">
                        <span class="item_category">Software</span>
                    </div>
                    <div class="title">
                        <h1 class="head_art"><?php echo $data_target['judul']; ?></h1>
                        <div class="item__auther emBlock__border">
                            <div class="item_person">
                                <img src="../assets/img/persons/0654.jpg" alt="">
                                <h2>Hobert Blais</h2>
                            </div>
                            <div class="sideRight">
                                <div class="time">
                                    <div class="icon">
                                        <svg id="Iconly_Curved_Time_Square" data-name="Iconly/Curved/Time Square"
                                            xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 15 15">
                                            <g id="Time_Square" data-name="Time Square"
                                                transform="translate(1.719 1.719)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M0,5.781c0,4.336,1.446,5.781,5.781,5.781s5.781-1.446,5.781-5.781S10.117,0,5.781,0,0,1.446,0,5.781Z"
                                                    fill="none" stroke="#cbcdd8" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3" d="M2.119,3.99,0,2.726V0"
                                                    transform="translate(5.781 3.053)" fill="none" stroke="#cbcdd8"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                    <span><?php echo $data_target['waktu']; ?></span>
                                </div>
                                <div class="view margin-l-10">
                                    <div class="icon">
                                        <svg id="Iconly_Curved_Show" data-name="Iconly/Curved/Show"
                                            xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 15 15">
                                            <g id="Show" transform="translate(1.719 2.969)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M3.952,1.976A1.976,1.976,0,1,1,1.976,0,1.977,1.977,0,0,1,3.952,1.976Z"
                                                    transform="translate(3.806 2.588)" fill="none" stroke="#cbcdd8"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3"
                                                    d="M0,4.564c0,2.05,2.589,4.564,5.782,4.564s5.782-2.512,5.782-4.564S8.976,0,5.782,0,0,2.514,0,4.564Z"
                                                    fill="none" stroke="#cbcdd8" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                    <span><?php echo $data_target['tanggal']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="embody__content">
                    
                        <?php echo $data_target['konten']; ?>
                    
                    
                </div>
            </section>
            <!-- End. emPage__detailsBlog -->

            


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
    <script src="../assets/js/main.js" defer></script>
    <!-- PWA app service registration and works js -->
    <script src="../assets/js/pwa-services.js"></script>
</body>
</html>
<?php
                
            }
        } else {
            header("Location: ".$cfg_baseurl."/mobile/blog/");
        }
    }
} else {
    header("Location: ".$cfg_baseurl);
}
?>