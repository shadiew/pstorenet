<?php
session_start();
require("../lib/mainconfig.php");
$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
$msg_type = "nothing";

// Valid email domains
$allowed_domains = array("gmail.com", "yahoo.com", "outlook.com");

if (isset($_POST['signup'])) {
    $post_email = htmlspecialchars(trim($_POST['email']));
    $post_name = htmlspecialchars(trim($_POST['name']));
    $post_username = htmlspecialchars(trim($_POST['username']));
    $post_nohp = htmlspecialchars(trim($_POST['nohp']));
    $post_password = htmlspecialchars(trim($_POST['password']));
    $post_confirm = htmlspecialchars(trim($_POST['confirm']));

    $post_username = str_replace(' ', '', $post_username);

    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
    $check_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$post_email'");
    $check_nohp = mysqli_query($db, "SELECT * FROM users WHERE nohp = '$post_nohp'");
    $ip = $_SERVER['REMOTE_ADDR'];

    // Add prefix '62' to the phone number if it doesn't start with it
    if (!startsWith($post_nohp, '62')) {
        $post_nohp = '62' . $post_nohp;
    }

    if (empty($post_email) || empty($post_username) || empty($post_name) || empty($post_nohp) || empty($post_password)) {
        $msg_type = "error";
        $msg_content = "Mohon untuk mengisi semua kolom.";
    } else if (mysqli_num_rows($check_email) > 0) {
        $msg_type = "error";
        $msg_content = "Email kamu sudah terdaftar.";
    } else if (mysqli_num_rows($check_user) > 0) {
        $msg_type = "error";
        $msg_content = "Username sudah digunakan";
    } else if (strlen($post_password) < 5) {
        $msg_type = "error";
        $msg_content = "Minimal Karakter password 5";
    } else if ($post_password <> $post_confirm) {
        $msg_type = "error";
        $msg_content = "Password tidak Sama.";
    } else if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{5,}$/", $post_password)) {
        $msg_type = "error";
        $msg_content = "Password Wajib Mengandung Huruf Besar, Huruf kecil, angka, dan karakter khusus";
    } else if (!isValidEmailDomain($post_email, $allowed_domains)) {
        $msg_type = "error";
        $msg_content = "Email hanya diperbolehkan dengan domain Gmail, Yahoo, atau Outlook.";
    } else {
        $post_apikey = random(20);
        $post_kunci = random(5);
        $ip = $_SERVER['REMOTE_ADDR'];
        $hashed_password = password_hash($post_password, PASSWORD_DEFAULT);
        $insert_user = mysqli_query($db, "INSERT INTO users (email, name, username, password, nohp, balance, level, registered, status, api_key, uplink, otp, point, ip) VALUES ('$post_email', '$post_name', '$post_username', '$hashed_password', '$post_nohp', '0', 'Member', '$date $time', 'Active', '$post_apikey', 'Server', '$post_kunci', '0', '$ip')");
        if ($insert_user == true) {
            $msg_type = "success";
            $msg_content = "Pendaftaran akun berhasil. Silahkan Login.<br><br>Terimakasih anda telah mempercayakan kami sebagai mitra Jasa sosial media anda!";
            $receiverNumber = $post_nohp;
            $message = "Pendaftaran akun berhasil. Silahkan Login.\n\nTerimakasih anda telah mempercayakan kami sebagai mitra Jasa sosial media anda!";

            $wa_sender = mysqli_query($db, "SELECT * FROM wa_sender LIMIT 1");
            $data_wa_sender = mysqli_fetch_assoc($wa_sender);
            $appkey = $data_wa_sender['appkey'];
            $authkey = $data_wa_sender['authkey'];
            $url = $data_wa_sender['url'];
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
              'appkey' => $appkey,
              'authkey' => $authkey,
              'to' => $receiverNumber,
              'message' => $message,
              'sandbox' => 'false'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           
        } else {
            $msg_type = "error";
            $msg_content = "Terjadi kesalahan Sistem.";
        }
    }
}

/**
 * Check if a string starts with a specific prefix.
 *
 * @param string $haystack The string to search in.
 * @param string $needle The prefix to check.
 * @return bool Whether the string starts with the prefix or not.
 */
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * Check if an email address has a valid domain.
 *
 * @param string $email The email address to check.
 * @param array $allowedDomains Array of allowed domain names.
 * @return bool Whether the email has a valid domain or not.
 */
function isValidEmailDomain($email, $allowedDomains)
{
    $domain = strtolower(substr(strrchr($email, "@"), 1));
    return in_array($domain, $allowedDomains);
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
    <title><?php echo $data_settings['web_name']; ?> | Daftar</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>"/>
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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="login">
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
                    
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start em__signTypeOne -->
            <section class="em__signTypeOne typeTwo np__account padding-t-70">
                <div class="em_titleSign">
                    <h1>Let's Get Started!</h1>
                    <p>Register the email address to continue</p>
                </div>
                <div class="em__body">
                    <form method="POST" class="padding-t-40">
                        <div class="margin-b-30">
                            <div class="form-group input-lined lined__iconed">
                                <div class="input_group">
                                    <input type="text" class="form-control"  placeholder="Enter full name"
                                        required="" name="name">
                                    <div class="icon">
                                        <svg id="Iconly_Curved_Profile" data-name="Iconly/Curved/Profile"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <g id="Profile" transform="translate(5 2.4)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M6.845,7.3C3.153,7.3,0,6.726,0,4.425S3.133,0,6.845,0c3.692,0,6.845,2.1,6.845,4.4S10.556,7.3,6.845,7.3Z"
                                                    transform="translate(0 11.962)" fill="none" stroke="#000"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3"
                                                    d="M4.387,8.774a4.372,4.372,0,1,0-.031,0Z"
                                                    transform="translate(2.45 0)" fill="none" stroke="#000"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <label for="name">Nama Lengkap</label>
                            </div>
                        </div>
                        <div class="margin-b-30">
                            <div class="form-group input-lined lined__iconed">
                                <div class="input_group">
                                    <input type="username" class="form-control"  placeholder="Enter Username"
                                        required="" name="username">
                                    <div class="icon">
                                        <svg id="Iconly_Curved_Profile" data-name="Iconly/Curved/Profile"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <g id="Profile" transform="translate(5 2.4)">
                                                <path id="Stroke_1" data-name="Stroke 1"
                                                    d="M6.845,7.3C3.153,7.3,0,6.726,0,4.425S3.133,0,6.845,0c3.692,0,6.845,2.1,6.845,4.4S10.556,7.3,6.845,7.3Z"
                                                    transform="translate(0 11.962)" fill="none" stroke="#000"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                                <path id="Stroke_3" data-name="Stroke 3"
                                                    d="M4.387,8.774a4.372,4.372,0,1,0-.031,0Z"
                                                    transform="translate(2.45 0)" fill="none" stroke="#000"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-miterlimit="10" stroke-width="1.5" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <label for="username">Username</label>
                            </div>
                        </div>
                        <div class="margin-b-30">
                            <div class="form-group input-lined lined__iconed">
                                <div class="input_group">
                                    <input type="email" class="form-control"  name="email" placeholder="example@mail.com"
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
                                <label for="email">Email Address</label>
                            </div>
                        </div>

                        <div class="margin-b-30">
                            <div class="form-group input-lined lined__iconed">
                                <div class="input_group">
                                    <input type="number" class="form-control" name="nohp" placeholder="628xx"
                                        required="">
                                    <div class="icon">
                                        <svg id="Iconly_Bulk_Calling" data-name="Iconly/Bulk/Calling"
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                        <g id="Group" transform="translate(11.165 1.667)">
                                            <g id="Calling" transform="translate(0 0)">
                                                <path id="Fill_1" data-name="Fill 1"
                                                    d="M.85.013A.717.717,0,0,0,.578,1.421,2.885,2.885,0,0,1,2.864,3.713h0a.716.716,0,0,0,.7.58A.778.778,0,0,0,3.7,4.281a.721.721,0,0,0,.564-.843A4.312,4.312,0,0,0,.85.013"
                                                    transform="translate(0 2.896)" fill="#556fff" opacity="0.4" />
                                                <path id="Fill_3" data-name="Fill 3"
                                                    d="M.795.007A.681.681,0,0,0,.273.153.719.719,0,0,0,.635,1.431a5.76,5.76,0,0,1,5.1,5.116.714.714,0,0,0,.709.637.666.666,0,0,0,.081,0,.706.706,0,0,0,.479-.265.714.714,0,0,0,.151-.527A7.18,7.18,0,0,0,.795.007"
                                                    transform="translate(0.003 0)" fill="#556fff" opacity="0.4" />
                                            </g>
                                        </g>
                                        <g id="Call" transform="translate(1.667 2.5)">
                                            <path id="Stroke_1" data-name="Stroke 1"
                                                d="M7.526,8.31c3.324,3.323,4.078-.521,6.195,1.594,2.041,2.04,3.213,2.449.628,5.033-.324.26-2.381,3.391-9.612-3.838S.635,1.81.895,1.487C3.487-1.105,3.888.074,5.929,2.114,8.045,4.23,4.2,4.987,7.526,8.31Z"
                                                transform="translate(0 0)" fill="#556fff" />
                                        </g>
                                    </svg>
                                    </div>
                                </div>
                                <label for="nohp">No. Whatsapp</label>
                            </div>
                        </div>

                        <div class="form-group input-lined lined__iconed" id="show_hide_password">
                            <div class="input_group">
                                <input type="password"  name="password" class="form-control" placeholder="enter your password"
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

                            <label for="password">Password</label>
                        </div>

                        <div class="form-group input-lined lined__iconed" id="show_hide_password">
                            <div class="input_group">
                                <input type="password"  name="confirm" class="form-control" placeholder="enter your password"
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

                            <label for="confirm">Password</label>
                        </div>

                        
                        <div class="buttons__footer text-center">
                            <button type="submit" value="signup" name="signup" class="btn bg-primary rounded-pill btn__default">
                                <span class="color-white">Sign Up</span>
                                <div class="icon rounded-circle">
                                    <i class="tio-chevron_right"></i>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
                
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
      timer: 30000
    });
  <?php } else if ($msg_type == "success") { ?>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: '<?php echo $msg_content; ?>',
      timer: 30000
    });
  <?php } ?>
</script>
</body>
</html>