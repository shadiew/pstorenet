<?php
session_start();
require("../../lib/mainconfig.php");

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

/* NEW TICKET HANDLER */
if (isset($_POST['submit'])) {
        $post_subject = htmlspecialchars($_POST['subject']);
        $post_message = htmlspecialchars($_POST['message']);

        $antibug2 = (false === strpbrk($post_message, "#$^*[]';{}|<>~")) ? 'Allowed' : "Allowed";
        $antibug = (false === strpbrk($post_subject, "#$^*[]';{}|<>~")) ? 'Allowed' : "Allowed";
          $ip = $_SERVER['REMOTE_ADDR'];
        if (empty($post_subject) || empty($post_message)) {
            $msg_type = "error";
            $msg_content = "<b>Failed :</b> Please fill all input.";
        } else if (strlen($post_subject) > 200) {
            $msg_type = "error";
            $msg_content = "<b>Failed :</b> Maximum subject is 200 characters.";
        } else if ($antibug == "Disallowed" OR $antibug2 == "Disallowed") {
                    $msg_type = "error";
                    $msg_content = "The Character You Input Is Not Allowed.";
        } else if (strlen($post_message) > 500) {
            $msg_type = "error";
            $msg_content = "<b>Failed :</b> Maximum message is 500 characters.";
        } else if (strlen($post_message) < 20) {
            $msg_type = "error";
            $msg_content = "<b>Failed :</b> Minimum Message Is 20 Characters.";
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
            $insert_ticket = mysqli_query($db, "INSERT INTO tickets (user, subject, message, datetime, last_update, status, ip) VALUES ('$sess_username', '$post_subject', '$post_message', '$date $time', '$date $time', 'Pending', '$ip')");
            if ($insert_ticket == TRUE) {
                $msg_type = "success";
                $msg_content = "<b>Success :</b> Ticket Has Been Sent.";
            } else {
                $msg_type = "error";
                $msg_content = "<b>Failed :</b> System error.";
            }
        }
}   
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
$title = "Ticket Support";
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
    <title><?php echo $data_settings['web_name']; ?> | Bantuan</title>
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
            <header class="header_tab">
                <div class="main_haeder multi_item">
                    <div class="em_side_right">
                        <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../">
                            <i class="tio-chevron_left size-24 color-text"></i>
                            <span class="color-text size-14">Back</span>
                        </a>
                    </div>
                    <div class="title_page">
                        <span class="page_name">
                            Layanan Bantuan
                        </span>
                    </div>
                    <div class="em_side_right">
                    <button class="btn rounded-circle share-button bg-transparent" data-toggle="modal"
                        data-target="#mdllContent-form">
                        <i class="ri-add-circle-fill"></i>
                    </button>

                </div>
                </div>

                <!-- Search -->
                
                <!-- End. Search -->

            </header>
            <!-- End.main_haeder -->

            <!-- Start emPage__chat -->
            <section class="emPage__chat">
                <div class="block__people">
                    <?php
                                                    // start paging config
                                                    $query_parent = "SELECT * FROM tickets WHERE user = '$sess_username' ORDER BY id DESC"; // edit
                                                    $records_per_page = 10; // edit

                                                    $starting_position = 0;
                                                    if(isset($_GET["page_no"])) {
                                                        $starting_position = ($_GET["page_no"]-1) * $records_per_page;
                                                    }
                                                    $new_query = $query_parent." LIMIT $starting_position, $records_per_page";
                                                    $new_query = mysqli_query($db, $new_query);
                                                    $now_records = mysqli_num_rows($new_query);
                                                    // end paging config

                                                    if ($now_records == 0) {
                                                    ?><?php
                                                    } else {
                                                        while ($data_show = mysqli_fetch_assoc($new_query)) {
                                                            if($data_show['status'] == "Closed") {
                                                                $label = "danger";
                                                            } else if($data_show['status'] == "Responded") {
                                                                $label = "success";
                                                            } else if($data_show['status'] == "Waiting") {
                                                                $label = "info";
                                                            } else {
                                                                $label = "warning";
                                                            }
                                                    ?>
                    <a href="open/?id=<?php echo $data_show['id']; ?>" class="item_user read_sms">
                        <div class="media">
                            <div class="img_user">
                                <img src="../assets/img/persons/avatar.png" alt="">
                            </div>
                            <div class="media-body my-auto">
                                <div class="userneme">
                                    <h2><?php echo $data_show['subject']; ?> <?php if($data_show['seen_user'] == 0) { ?><b>( NEW! )</b><?php } ?></h2>
                                    <p class="color-text">
                                        <?php echo nl2br(substr($data_show['message'], 0, 50)); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="sideRight">
                            <span class="time"><?php echo $data_show['last_update']; ?></span>
                        </div>
                    </a>
                    <?php
                                                        }
                                                    }
                                                    ?>
                </div>
            </section>
            <!-- End. emPage__chat -->

        </div>

        <div class="modal transition-bottom screenFull defaultModal mdlladd__rate fade" id="mdllContent-form"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable height-full">
                <div class="modal-content rounded-0">
                    <div class="modal-header padding-l-20 padding-r-20 justify-content-center">
                        <div class="itemProduct_sm">
                            <h1 class="size-18 weight-600 color-secondary m-0">Buat Tiket Baru</h1>
                        </div>
                        <div class="absolute right-0 padding-r-20">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="tio-clear"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="padding-t-20">
                            <div class="em__signTypeOne">
                                
                                <div class="em__body px-0">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group with_icon">
                                            <label>Subjek</label>
                                            <div class="input_group">
                                                <input type="text" class="form-control" name="subject" placeholder="Subjek">
                                                <div class="icon">
                                                    <svg id="Iconly_Two-tone_Message"
                                                        data-name="Iconly/Two-tone/Message"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24">
                                                        <g id="Message" transform="translate(2 3)">
                                                            <path id="Path_445"
                                                                d="M11.314,0,7.048,3.434a2.223,2.223,0,0,1-2.746,0L0,0"
                                                                transform="translate(3.954 5.561)" fill="none"
                                                                stroke="#200e32" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-miterlimit="10"
                                                                stroke-width="1.5" opacity="0.4" />
                                                            <path id="Rectangle_511"
                                                                d="M4.888,0h9.428A4.957,4.957,0,0,1,17.9,1.59a5.017,5.017,0,0,1,1.326,3.7v6.528a5.017,5.017,0,0,1-1.326,3.7,4.957,4.957,0,0,1-3.58,1.59H4.888C1.968,17.116,0,14.741,0,11.822V5.294C0,2.375,1.968,0,4.888,0Z"
                                                                transform="translate(0 0)" fill="none" stroke="#200e32"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-miterlimit="10" stroke-width="1.5" />
                                                        </g>
                                                    </svg>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group with_icon">
                                            <label>Pesan Kamu</label>
                                            <div class="input_group">
                                                <textarea class="form-control" name="message" placeholder="Message" rows="3"></textarea>
                                                <div class="icon">
                                                    <svg id="Iconly_Two-tone_Message"
                                                        data-name="Iconly/Two-tone/Message"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24">
                                                        <g id="Message" transform="translate(2 3)">
                                                            <path id="Path_445"
                                                                d="M11.314,0,7.048,3.434a2.223,2.223,0,0,1-2.746,0L0,0"
                                                                transform="translate(3.954 5.561)" fill="none"
                                                                stroke="#200e32" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-miterlimit="10"
                                                                stroke-width="1.5" opacity="0.4" />
                                                            <path id="Rectangle_511"
                                                                d="M4.888,0h9.428A4.957,4.957,0,0,1,17.9,1.59a5.017,5.017,0,0,1,1.326,3.7v6.528a5.017,5.017,0,0,1-1.326,3.7,4.957,4.957,0,0,1-3.58,1.59H4.888C1.968,17.116,0,14.741,0,11.822V5.294C0,2.375,1.968,0,4.888,0Z"
                                                                transform="translate(0 0)" fill="none" stroke="#200e32"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-miterlimit="10" stroke-width="1.5" />
                                                        </g>
                                                    </svg>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer pt-3">
                                            <button name="submit" type="submit" 
                                                class="btn w-100 bg-primary m-0 color-white h-55 d-flex align-items-center rounded-10 justify-content-center">
                                                Kirim Pesan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
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
    
} else {
    header("Location: ".$cfg_baseurl);
}
?>