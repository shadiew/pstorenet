<?php
session_start();
require("../lib/mainconfig.php");


if (!isset($_SESSION)) {
    session_start();
}
/* CLEARING POST DATA IF EXISTS*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    unset($_POST);
    header("Location: " . $_SERVER[REQUEST_URI]);
    exit;
}

if (@$_SESSION['postdata']) {
    $_POST = $_SESSION['postdata'];
    unset($_SESSION['postdata']);
}
//clear

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $sess_id = $_SESSION['user']['id'];
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
    $title = "Deposit History";
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
    <title><?php echo $data_settings['web_name']; ?> | Riwayat Deposit</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords"
        content="<?php echo $data_settings['seo_keywords']; ?>" />

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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../mobile">
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
            <section class="emPage__activities _creative padding-t-70">

                

                <div class="group">
                    <div class="title_group">
                        <span>Payment</span>
                    </div>
                    <?php
                                        // start paging config
                                        $query_order = mysqli_query($db, "SELECT * FROM deposits WHERE user = '$sess_id' ORDER BY id DESC");
                                        // end paging config

                                        while ($data_order = mysqli_fetch_assoc($query_order)) {
                                            if ($data_order['status'] == "Error") {
                                                $label = "danger";
                                                $label2 = "Error";
                                            } else if ($data_order['status'] == "Pending") {
                                                $label = "warning";
                                                $label2 = "Pending";
                                            } else if ($data_order['status'] == "Success") {
                                                $label = "success";
                                                $label2 = "Success";
                                            } else if ($data_order['status'] == "Expired") {
                                                $label = "secondary";
                                                $label2 = "Expired";
                                            }
                                        ?>
                                            <?php $no = $no + 1; ?>
                    <div class="item__activiClassic">
                        <div class="media">
                            <div class="icon bg-green bg-opacity-10">
                                <img src="../assets/img/tripay/<?php echo $data_order['code']; ?>.webp"  height="20px">

                            </div>
                            <div class="media-body my-auto">
                                <div class="txt">
                                    <h2>Deposit <?php echo $data_order['code']; ?> <?php echo $label2; ?></h2>
                                    <p><?php echo $data_order['created_at']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                                                    if ($data_order['status'] == "Expired" || $data_order['status'] == "Error" || $data_order['status'] == "Success") { ?>
                        <div class="sideRight">
                            <button class="btn btn-xs bg-<?php echo $label; ?> rounded-pill color-text-white"><img src="assets/img/tab/wallet.png" height="20px"></button>
                        </div>
                        <?php
                                                    } else { ?>
                        <div class="sideRight">
                            <button class="btn btn-xs bg-<?php echo $label; ?> rounded-pill" onclick="showInstruction(<?php echo $data_order['id']; ?>)"> <img src="assets/img/tab/riwayat.png" height="20px"> </button>
                        </div>
                        <?php
                                                    }
                                                    ?>
                    </div>
                    <?php
                                        }
                                        ?>
                </div>
<div class="modal fade" id="instructionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" id="exampleModalLabel">Cara Pembayaran</h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="data" style="margin: 10px;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                
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
    <script>
        function showInstruction(id) {
            if (id == "") {
                document.getElementById("data").innerHTML = "";
                return;
            } else {
                $('#instructionsModal').modal()
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("data").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "instruction.php?id=" + id, true);
                xmlhttp.send();
            }
        }
    </script>
</body>

</html>
<?php
                
            } else {
                header("Location: " . $cfg_baseurl);
            } ?>