<?php
session_start();
require("../../lib/mainconfig.php");
require("../../lib/password.php"); // Target Bcrypt code

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);   
    $email = $data_user['email'];
    $demo = $data_user['status'];
    $hp = $data_user['nohp'];
    $nama = $data_user['name'];
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$cfg_baseurl."/logout/");
    } 
    $title = "Settings";
    include("../../lib/header.php");
    $msg_type = "nothing";

    if (isset($_POST['change_pswd'])) {
        $post_password = htmlspecialchars(trim($_POST['password']));
        $post_npassword = htmlspecialchars(trim($_POST['npassword']));
        $post_cnpassword = htmlspecialchars(trim($_POST['cnpassword']));
        if ($demo == "Demo") {
            $msg_type = "error";
            $msg_content = "Sorry this feature is not available for Demo users";
        } else if (empty($post_password) || empty($post_npassword) || empty($post_cnpassword)) {
            $msg_type = "error";
            $msg_content = "Please Fill In All Inputs.";
        } else if (!password_verify($post_password, $data_user['password'])) {
            $msg_type = "error";
            $msg_content = "The Password You Enter Is Wrong.";
        } else if (strlen($post_npassword) < 6) {
            $msg_type = "error";
            $msg_content = "New password is too short, at least 6 characters.";
        } else if ($post_cnpassword !== $post_npassword) {
            $msg_type = "error";
            $msg_content = "Confirm New Password Not Correct.";
        } else {
            $hashed_password = password_hash($post_npassword, PASSWORD_BCRYPT);
            $update_user = mysqli_query($db, "UPDATE users SET password = '$hashed_password' WHERE username = '$sess_username'");
            if ($update_user == TRUE) {
                $msg_type = "success";
                $msg_content = "Password has been changed.";
            } else {
                $msg_type = "error";
                $msg_content = "A System Error Occurred.";
            }
        }
    } else if (isset($_POST['change_api'])) {
        $set_api_key = random(20);
        $update_user = mysqli_query($db, "UPDATE users SET api_key = '$set_api_key' WHERE username = '$sess_username'");
        if ($update_user == TRUE) {
            $msg_type = "success";
            $msg_content = "API Key has been changed to <b>$set_api_key</b>.";
        } else {
            $msg_type = "error";
            $msg_content = "A System Error Occurred.";
        }
    } else if (isset($_POST['change_profile'])) {
        $post_email = htmlspecialchars(trim($_POST['emailn']));
        $post_password = htmlspecialchars(trim($_POST['password']));
        $post_nama = htmlspecialchars(trim($_POST['nama']));
        $check_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$post_email'");
        if ($demo == "Demo") {
            $msg_type = "error";
            $msg_content = "Sorry this feature is not available for Demo users.";
        } else if (empty($post_email) || empty($post_password) || empty($post_nama)) {
            $msg_type = "error";
            $msg_content = "Please Fill In All Inputs.";
        } else if (mysqli_num_rows($check_email) > 0 && ($post_email !== $data_user['email'])) {
            $msg_type = "error";
            $msg_content = "The Email You Enter is Already Registered.";
        } else if (!password_verify($post_password, $data_user['password'])) {
            $msg_type = "error";
            $msg_content = "Wrong Password Confirmation.";
        } else {
            $update_user = mysqli_query($db, "UPDATE users SET email = '$post_email' WHERE username = '$sess_username'");
            $update_user = mysqli_query($db, "UPDATE users SET name = '$post_nama' WHERE username = '$sess_username'");
            if ($update_user == TRUE) {
                $msg_type = "success";
                $msg_content = "Profile has been updated.";
            } else {
                $msg_type = "error";
                $msg_content = "A System Error Occurred.";
            }
        }
    }
    
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">

    <meta name="theme-color" content="#ffffff">
    <title><?php echo $data_settings['web_name']; ?> | Update User</title>
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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../akun">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        Personal Details
                    </span>
                </div>

            </header>
            <!-- End.main_haeder -->

            <!-- form -->
            <section class="padding-20">

                <form method="POST" class="padding-t-70">
                    <?php 
                    if ($msg_type == "success") {
                    ?>
                    <div class="alert alert-primary" role="alert">
                      <?php echo $msg_content; ?>
                    </div>
                    <?php
                    } else if ($msg_type == "error") {
                    ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $msg_content; ?>
                    </div>
                    <?php
                    } else if ($email == "") {
                    ?>
                    <div class="alert alert-warning" role="alert">
                      <?php echo $msg_content; ?>
                    </div>

<?php
                    }
                    ?>
                    <div class="margin-b-30">
                        <div class="form-group input-lined lined__iconed">
                            <div class="input_group">
                                <?php if ($nama == "")
                                                { ?>
                                <input type="text" class="form-control" name="nama"
                                    placeholder="Enter full name" >
                                        <?php } else { ?>
                                <input type="text" class="form-control" name="nama" value="<?php echo $data_user['name']; ?>">
                                    <?php } ?>
                                <div class="icon">
                                    <svg id="Iconly_Curved_Message" data-name="Iconly/Curved/Message"
                                        xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                        <g id="Message" transform="translate(2.248 2.614)">
                                            <path id="Stroke_1" data-name="Stroke 1"
                                                d="M10.222,0S7.279,3.532,5.127,3.532,0,0,0,0"
                                                transform="translate(3.613 5.653)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                            <path id="Stroke_3" data-name="Stroke 3"
                                                d="M0,8.357C0,2.089,2.183,0,8.73,0s8.73,2.089,8.73,8.357-2.183,8.357-8.73,8.357S0,14.624,0,8.357Z"
                                                transform="translate(0 0)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <label for="username">Full name</label>
                        </div>
                    </div>
                    <div class="margin-b-30">
                        <div class="form-group input-lined lined__iconed">
                            <div class="input_group">
                                <input type="email" class="form-control" name="emailn"
                                    placeholder="example@mail.com">
                                <div class="icon">
                                    <svg id="Iconly_Curved_Message" data-name="Iconly/Curved/Message"
                                        xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                        <g id="Message" transform="translate(2.248 2.614)">
                                            <path id="Stroke_1" data-name="Stroke 1"
                                                d="M10.222,0S7.279,3.532,5.127,3.532,0,0,0,0"
                                                transform="translate(3.613 5.653)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                            <path id="Stroke_3" data-name="Stroke 3"
                                                d="M0,8.357C0,2.089,2.183,0,8.73,0s8.73,2.089,8.73,8.357-2.183,8.357-8.73,8.357S0,14.624,0,8.357Z"
                                                transform="translate(0 0)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <label for="email">Email Address</label>
                        </div>
                    </div>
                    <div class="margin-b-30">
                        <div class="form-group input-lined lined__iconed">
                            <div class="input_group">
                                <input type="password" class="form-control" name="password"
                                    placeholder="***********" required="">
                                <div class="icon">
                                    <svg id="Iconly_Curved_Message" data-name="Iconly/Curved/Message"
                                        xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                        <g id="Message" transform="translate(2.248 2.614)">
                                            <path id="Stroke_1" data-name="Stroke 1"
                                                d="M10.222,0S7.279,3.532,5.127,3.532,0,0,0,0"
                                                transform="translate(3.613 5.653)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                            <path id="Stroke_3" data-name="Stroke 3"
                                                d="M0,8.357C0,2.089,2.183,0,8.73,0s8.73,2.089,8.73,8.357-2.183,8.357-8.73,8.357S0,14.624,0,8.357Z"
                                                transform="translate(0 0)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <label>Konfirmasi Password</label>
                        </div>
                    </div>
                    <div class="buttons__footer text-center">
                <div class="env-pb">
                    <button name="change_profile" type="submit" class="btn bg-primary rounded-pill btn__default">
                        <span class="color-white">Save Changes</span>
                        <div class="icon rounded-circle">
                            <i class="tio-chevron_right"></i>
                        </div>
                    </button>
                                    </div>
            </div>

                </form>
            </section>
            <!-- buttons__footer -->
            

        </div>


        <!-- Start searchMenu__hdr -->
        <section class="searchMenu__hdr">
            <form>
                <div class="form-group">
                    <div class="input_group">
                        <input type="search" class="form-control" placeholder="type something here...">
                        <i class="ri-search-2-line icon_serach"></i>
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn_meunSearch -close __removeMenu">
                <i class="tio-clear"></i>
            </button>
        </section>
        <!-- End. searchMenu__hdr -->

        <!-- Modal Buttons Share -->
        <div class="modal transition-bottom -inside screenFull defaultModal mdlladd__rate fade" id="mdllButtons_share"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-15">

                    <div class="modal-body rounded-15 p-0">
                        <div class="emBK__buttonsShare icon__share padding-20">
                            <button type="button" class="btn" data-sharer="facebook" data-hashtag="hashtag"
                                data-url="https://orinostudio.com/nepro/">
                                <div class="icon bg-facebook rounded-10">
                                    <i class="tio-facebook_square"></i>
                                </div>
                            </button>
                            <button type="button" class="btn" data-sharer="telegram" data-title="Checkout Nepro!"
                                data-url="https://orinostudio.com/nepro/" data-to="+44555-5555">
                                <div class="icon bg-telegram rounded-10">
                                    <i class="tio-telegram"></i>
                                </div>
                            </button>
                            <button type="button" class="btn" data-sharer="skype"
                                data-url="https://orinostudio.com/nepro/" data-title="Checkout Nepro!">
                                <div class="icon bg-skype rounded-10">
                                    <i class="tio-skype"></i>
                                </div>
                            </button>
                            <button type="button" class="btn" data-sharer="linkedin"
                                data-url="https://orinostudio.com/nepro/">
                                <div class="icon bg-linkedin rounded-10">
                                    <i class="tio-linkedin_square"></i>
                                </div>
                            </button>
                            <button type="button" class="btn" data-sharer="twitter" data-title="Checkout Nepro!"
                                data-hashtags="pwa, Nepro, template, mobile, app, shopping, ecommerce"
                                data-url="https://orinostudio.com/nepro/">
                                <div class="icon bg-twitter rounded-10">
                                    <i class="tio-twitter"></i>
                                </div>
                            </button>
                            <button type="button" class="btn" data-sharer="whatsapp" data-title="Checkout Nepro!"
                                data-url="https://orinostudio.com/nepro/">
                                <div class="icon bg-whatsapp rounded-10">
                                    <i class="tio-whatsapp_outlined"></i>
                                </div>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
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
    
} else {
    header("Location: ".$cfg_baseurl);
}
?>