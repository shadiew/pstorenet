<?php
session_start();
require("../lib/mainconfig.php");
require("../lib/password.php");

$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
$msg_type = "nothing";

if (isset($_POST['login'])) {
    $post_username = mysqli_real_escape_string($db, trim($_POST['username']));
    $post_password = mysqli_real_escape_string($db, trim($_POST['password']));
    $ip = $_SERVER['REMOTE_ADDR'];
    if (empty($post_username) || empty($post_password)) {
        $msg_type = "error";
        $msg_content = "Please Fill In All Inputs.";
    } else {
        $post_username = htmlspecialchars($post_username);
        $post_password = htmlspecialchars($post_password);
        
        $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username' OR email = '$post_username'");
        if (mysqli_num_rows($check_user) == 0) {
            $msg_type = "error";
            $msg_content = "The username or email you entered is not registered.";
        } else {
            $data_user = mysqli_fetch_assoc($check_user);
            if ($data_user['level'] == "Developers" && !password_verify($post_password, $data_user['password'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
                $msg_type = "error";
                $msg_content = "The Password You Enter Is Wrong.";
                
                // Block user after 5 failed attempts for 60 seconds
                $failed_attempts = $data_user['failed_attempts'] + 1;
                if ($failed_attempts >= 5) {
                    $block_time = time() + 60; // Set block time to current time + 60 seconds
                    mysqli_query($db, "UPDATE users SET failed_attempts = '$failed_attempts', block_time = '$block_time' WHERE id = '".$data_user['id']."'");
                    $msg_content .= " Your account has been blocked due to too many failed login attempts. Please try again after 60 seconds.";
                } else {
                    mysqli_query($db, "UPDATE users SET failed_attempts = '$failed_attempts' WHERE id = '".$data_user['id']."'");
                }
            } else if (!password_verify($post_password, $data_user['password'])) {
                $msg_type = "error";
                $msg_content = "The Password You Enter Is Wrong.";
                
                // Block user after 5 failed attempts for 60 seconds
                $failed_attempts = $data_user['failed_attempts'] + 1;
                if ($failed_attempts >= 5) {
                    $block_time = time() + 60; // Set block time to current time + 60 seconds
                    mysqli_query($db, "UPDATE users SET failed_attempts = '$failed_attempts', block_time = '$block_time' WHERE id = '".$data_user['id']."'");
                    $msg_content .= " Your account has been blocked due to too many failed login attempts. Please try again after 60 seconds.";
                } else {
                    mysqli_query($db, "UPDATE users SET failed_attempts = '$failed_attempts' WHERE id = '".$data_user['id']."'");
                }
            } else if ($data_user['status'] == "Suspended") {
                $msg_type = "error";
                $msg_content = "Account Suspended.";

            } else if ($data_user['status'] == "Not Active") {
                $msg_type = "error";
                $msg_content = "Akun anda telah kami non-aktifkan karena tidak pernah login. silahkan hubungi admin.";

            } else {
                // Reset failed attempts and block time on successful login
                mysqli_query($db, "UPDATE users SET failed_attempts = '0', block_time = '0', last_login = CURRENT_TIMESTAMP WHERE id = '".$data_user['id']."'");

                $_SESSION['user'] = $data_user;
                header("Location: ".$cfg_baseurl."/mobile/");
            }
        }
    }    
}
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
    <title><?php echo $data_settings['web_name']; ?> | Login</title>
    
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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="<?php echo $cfg_baseurl; ?>/mobile/home">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        <!-- title here.. -->
                    </span>
                </div>
                <div class="em_side_right">
                    <a href="lupa"
                        class="link__forgot d-block size-14 color-text text-decoration-none hover:color-secondary transition-all">
                        Forgot Password?</a>
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start em__signTypeOne -->
            <section class="em__signTypeOne typeTwo np__account padding-t-70">
                <form method="POST" class="padding-t-10" autocomplete="off">
                <div class="em_titleSign">
                    <h1>Welcome Back!</h1>
                    <p>Enter your credentials to continue</p>
                    
                </div>
                <div class="em__body">
                    
                        <div class="margin-b-30">
                            <div class="form-group input-lined lined__iconed">
                                <div class="input_group">
                                    <input type="username" name="username" class="form-control" id="username" placeholder="sadiwantoro"
                                        required="">
                                    <div class="icon">
                                        <svg id="Iconly_Curved_Message" data-name="Iconly/Curved/Message"
                                            xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 22 22">
                                            <g id="Message" transform="translate(2.248 2.614)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M10.222,0S7.279,3.532,5.127,3.532,0,0,0,0"
                                                    transform="translate(3.613 5.653)" fill="none" stroke="#9498ac"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3"
                                                    d="M0,8.357C0,2.089,2.183,0,8.73,0s8.73,2.089,8.73,8.357-2.183,8.357-8.73,8.357S0,14.624,0,8.357Z"
                                                    transform="translate(0 0)" fill="none" stroke="#9498ac"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <label for="email">Email Atau Username</label>
                            </div>
                        </div>

                        <div class="form-group input-lined lined__iconed" id="show_hide_password">
                            <div class="input_group">
                                <input type="password" id="pass" name="password" class="form-control" placeholder="enter your password"
                                    required="">
                                <div class="icon">
                                    <svg id="Iconly_Curved_Password" data-name="Iconly/Curved/Password"
                                        xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                        <g id="Password" transform="translate(2.521 2.521)">
                                            <path id="Stroke_1" data-name="Stroke 1"
                                                d="M3.4,1.7A1.7,1.7,0,1,1,1.7,0h0A1.7,1.7,0,0,1,3.4,1.7Z"
                                                transform="translate(3.882 6.781)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                            <path id="Stroke_3" data-name="Stroke 3" d="M0,0H5.792V1.7"
                                                transform="translate(7.28 8.479)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                            <path id="Stroke_5" data-name="Stroke 5" d="M.5,1.7V0"
                                                transform="translate(9.979 8.479)" fill="none" stroke="#9498ac"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                stroke-width="1.5" />
                                            <path id="Stroke_7" data-name="Stroke 7"
                                                d="M0,8.479C0,2.12,2.12,0,8.479,0s8.479,2.12,8.479,8.479-2.12,8.479-8.479,8.479S0,14.838,0,8.479Z"
                                                fill="none" stroke="#9498ac" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" />
                                        </g>
                                    </svg>
                                </div>
                                <button type="button" class="btn hide_show icon_password">
                                    <i class="tio-hidden_outlined"></i>
                                </button>
                            </div>

                            <label for="pass">Password</label>
                        </div>

                   
                </div>
                <div class="buttons__footer text-center">
                    <button class="btn bg-primary rounded-pill btn__default" type="submit" name="login">
                        <span class="color-white">Sign in</span>
                        <div class="icon rounded-circle">
                            <i class="tio-chevron_right"></i>
                        </div>
                    </button>
                    <a href="daftar" class="btn border-snow rounded-pill btn__default">
                        <span class="color-secondary size-14">I don't have an account</span>
                    </a>
                </div>
                 </form>
            </section>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    <?php if ($msg_type == "error") { ?>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?php echo $msg_content; ?>!',
        timer: 3000
      });
    <?php } ?>
</script>
</body>
</html>