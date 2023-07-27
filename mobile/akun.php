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
        header("Location: mobile/login");
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
    <title><?php echo $data_settings['web_name']; ?></title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>" />

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
            <header class="main_haeder header-sticky multi_item">
                <div class="em_menu_sidebar">
                    <div class="em_profile_user">
                        <div class="media">
                            <a href="page-profile.html">
                                <!-- You can use an image -->
                                <!-- <img class="_imgUser" src="assets/img/person.png" alt=""> -->
                                <div class="letter bg-yellow">
                                    <span><?php echo substr($data_user['username'], 0, 1) ?></span>
                                </div>
                            </a>
                            <div class="media-body">
                                <div class="txt">
                                    <h3><?php echo $data_user['username']; ?></h3>
                                    <p>+<?php echo $data_user['nohp']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="em_side_right">
                    <a href="<?php echo $cfg_baseurl; ?>/mobile/keluar"
                        class="link__forgot d-block size-14 color-primary text-decoration-none hover:color-secondary transition-all">
                        Sign out
                    </a>
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start box__dashboard -->
            <section class="box__dashboard">
                <div class="group">
                    <a href="riwayatorder" class="btn item_link">
                        <div class="icon bg-primary">
                            <svg id="Iconly_Curved_Folder" data-name="Iconly/Curved/Folder"
                                xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
                                <g id="Folder" transform="translate(1.979 1.979)">
                                    <path id="Stroke_1" data-name="Stroke 1" d="M0,.476H7.594"
                                        transform="translate(3.805 9.083)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_2" data-name="Stroke 2"
                                        d="M0,4.195A3.755,3.755,0,0,1,2.867.216,6.4,6.4,0,0,1,7.356.6c1.186.639.846,1.583,2.065,2.276s3.18-.348,4.461,1.034C15.223,5.359,15.216,7.581,15.216,9c0,5.38-3.014,5.807-7.608,5.807S0,14.431,0,9Z"
                                        transform="translate(0)" fill="none" stroke="#fff" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                </g>
                            </svg>
                        </div>
                        <div class="txt">
                            <p>Total Pesanan</p>
                            <span><?php echo $number_order; ?></span>
                        </div>
                    </a>
                    <a href="#" class="btn item_link">
                        <div class="icon bg-yellow">
                            <svg id="Iconly_Curved_Bookmark" data-name="Iconly/Curved/Bookmark"
                                xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
                                <g id="Bookmark" transform="translate(3.563 1.979)">
                                    <path id="Stroke_1" data-name="Stroke 1" d="M0,.5H5.427"
                                        transform="translate(3.2 4.818)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_2" data-name="Stroke 2"
                                        d="M5.914,0C.858,0,0,.738,0,6.673c0,6.644-.124,8.369,1.139,8.369s3.325-2.917,4.771-2.917,3.509,2.917,4.771,2.917,1.139-1.724,1.139-8.369C11.825.738,10.97,0,5.914,0Z"
                                        transform="translate(0)" fill="none" stroke="#fff" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                </g>
                            </svg>
                        </div>
                        <div class="txt">
                            <p>Pengeluaran Harian</p>
                            <span><?php echo rupiah($data_order_today['total']); ?></span>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="#" class="btn item_link">
                        <div class="icon bg-green">
                            <svg id="Iconly_Curved_Wallet" data-name="Iconly/Curved/Wallet"
                                xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
                                <g id="Wallet" transform="translate(2.149 2.94)">
                                    <path id="Stroke_1" data-name="Stroke 1"
                                        d="M5.106,4.059H2.029A2.029,2.029,0,0,1,2.029,0H5.082"
                                        transform="translate(9.506 4.619)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_3" data-name="Stroke 3" d="M.563.476H.328"
                                        transform="translate(11.318 6.126)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_5" data-name="Stroke 5" d="M0,.476H3.214"
                                        transform="translate(3.873 3.031)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_7" data-name="Stroke 7"
                                        d="M0,6.76C0,1.69,1.84,0,7.363,0s7.363,1.69,7.363,6.76-1.84,6.76-7.363,6.76S0,11.83,0,6.76Z"
                                        transform="translate(0)" fill="none" stroke="#fff" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                </g>
                            </svg>

                        </div>
                        <div class="txt">
                            <p>Saldo Saya</p>
                            <?php
                                if ($data_user['balance'] == "0" or $data_user['balance'] < 0) {
                                    ?>
                            <span><?php echo rupiah($data_user['balance']); ?></span>
                            <?php
                                } ?>
                                <?php
                                if ($data_user['balance'] > 0) {
                                    ?>
                             <span><?php echo rupiah($data_user['balance']); ?></span>
                             <?php
                                } ?>       
                        </div>
                    </a>
                    <a href="#" class="btn item_link">
                        <div class="icon bg-red">
                            <svg id="Iconly_Curved_Paper_Plus" data-name="Iconly/Curved/Paper Plus"
                                xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
                                <g id="Paper_Plus" data-name="Paper Plus" transform="translate(2.89 2.177)">
                                    <path id="Stroke_1" data-name="Stroke 1"
                                        d="M13.07,4.394,8.582.119A14.408,14.408,0,0,0,6.642,0C1.663,0,0,1.837,0,7.323s1.663,7.323,6.642,7.323,6.65-1.829,6.65-7.323A16.661,16.661,0,0,0,13.07,4.394Z"
                                        transform="translate(0)" fill="none" stroke="#fff" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                    <path id="Stroke_3" data-name="Stroke 3"
                                        d="M0,0V2.107A2.662,2.662,0,0,0,2.663,4.769H5"
                                        transform="translate(8.141 0.065)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_5" data-name="Stroke 5" d="M3.879.5H0"
                                        transform="translate(4.562 7.599)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                    <path id="Stroke_7" data-name="Stroke 7" d="M.5,3.879V0"
                                        transform="translate(6.002 6.16)" fill="none" stroke="#fff"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                        stroke-width="1.5" />
                                </g>
                            </svg>
                        </div>
                        <div class="txt">
                            <p>Pengeluaran</p>
                            <span><?php echo rupiah($data_order['total']); ?></span>
                        </div>
                    </a>
                </div>
            </section>

            <section>
                <div class="padding-20">
                    <span class="size-12 text-uppercase color-text d-block">Account</span>
                </div>

                <div class="em__pkLink emBlock__border bg-white border-t-0">
                    <ul class="nav__list mb-0">
                        <li>
                            <a href="updateuser" class="item-link">
                                <div class="group">
                                    <div class="icon bg-primary">
                                        <svg id="Iconly_Curved_Profile" data-name="Iconly/Curved/Profile"
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            viewBox="0 0 19 19">
                                            <g id="Profile" transform="translate(3.958 1.9)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M5.419,5.779C2.5,5.779,0,5.324,0,3.5S2.48,0,5.419,0c2.923,0,5.419,1.665,5.419,3.487S8.357,5.779,5.419,5.779Z"
                                                    transform="translate(0 9.47)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3"
                                                    d="M3.473,6.946a3.461,3.461,0,1,0-.024,0Z"
                                                    transform="translate(1.94)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="path__name">Personal Details</span>
                                </div>
                                <div class="group">
                                    <span class="short__name"></span>
                                    <i class="tio-chevron_right -arrwo"></i>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="gantipassword" class="item-link">
                                <div class="group">
                                    <div class="icon bg-turquoise">
                                        <svg id="Iconly_Curved_Lock" data-name="Iconly/Curved/Lock"
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            viewBox="0 0 19 19">
                                            <g id="Lock" transform="translate(3.365 2.177)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M7.221,5.267v-1.7A3.611,3.611,0,0,0,0,3.55V5.267"
                                                    transform="translate(2.454 0)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3" d="M.5,0V1.758"
                                                    transform="translate(5.564 9.03)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_5" data-name="Stroke 5"
                                                    d="M6.064,0C1.516,0,0,1.241,0,4.965S1.516,9.93,6.064,9.93s6.065-1.241,6.065-4.965S10.612,0,6.064,0Z"
                                                    transform="translate(0 4.809)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="path__name">Ganti Password</span>
                                </div>
                                <div class="group">
                                    <span class="short__name"></span>
                                    <i class="tio-chevron_right -arrwo"></i>
                                </div>
                            </a>
                        </li>
                        
                        <li>
                            <a href="riwayatdeposit" class="item-link">
                                <div class="group">
                                    <div class="icon bg-purple">
                                        <svg id="Iconly_Curved_Paper" data-name="Iconly/Curved/Paper"
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            viewBox="0 0 19 19">
                                            <g id="Paper" transform="translate(2.889 2.177)">
                                                <path id="Stroke_1" data-name="Stroke 1" d="M4.275.5H0"
                                                    transform="translate(4.161 9.554)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_2" data-name="Stroke 2" d="M2.657.5H0"
                                                    transform="translate(4.16 6.378)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3"
                                                    d="M13.07,4.394,8.582.119A14.408,14.408,0,0,0,6.642,0C1.663,0,0,1.837,0,7.323s1.663,7.323,6.642,7.323,6.65-1.829,6.65-7.323A16.661,16.661,0,0,0,13.07,4.394Z"
                                                    transform="translate(0)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_5" data-name="Stroke 5"
                                                    d="M0,0V2.107A2.662,2.662,0,0,0,2.663,4.769H5"
                                                    transform="translate(8.142 0.065)" fill="none" stroke="#fff"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="path__name">Histori Deposit</span>
                                </div>
                                <div class="group">
                                    <span class="short__name"></span>
                                    <i class="tio-chevron_right -arrwo"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="padding-20">
                    <span class="size-12 text-uppercase color-text d-block">Artikel</span>
                </div>

                <div class="em__pkLink emBlock__border bg-white margin-b-10 border-t-0">
                    <ul class="nav__list mb-0">
                        <li>
                            <a href="blog" class="btn item-link">
                                <div class="group">
                                    <div class="icon bg-yellow">
                                        <img src="assets/img/icon/blog.png" width="19" height="19">
                                    </div>
                                    <span class="path__name">Blog</span>
                                </div>
                                <div class="group">
                                    <span class="short__name"></span>
                                    <i class="tio-chevron_right -arrwo"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            

                

            </section>

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
                    <a href="<?php echo $cfg_baseurl; ?>/mobile/riwayatorder" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/riwayat.png" width="34" height="34">
                        </div>

                        <div class="txt__tile">Riwayat</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="<?php echo $cfg_baseurl; ?>/mobile/deposit" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/wallet.png" width="34" height="34">
                        </div>
                        
                        <div class="txt__tile">Deposit</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="<?php echo $cfg_baseurl; ?>/mobile/bantuan" class="btn btn_navLink">
                        <div class="icon_current">
                            <img src="<?php echo $cfg_baseurl; ?>/mobile/assets/img/tab/chat.png" width="34" height="34">
                        </div>
                        
                        <div class="txt__tile">Bantuan</div>
                    </a>
                </div>
                <div class="item_link">
                    <a href="<?php echo $cfg_baseurl; ?>/mobile/akun" class="btn btn_navLink">
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