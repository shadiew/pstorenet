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
                header("Location: ".$cfg_baseurl);
            }
        }
    }    
}
?>




<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo $cfg_baseurl; ?>/assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php echo $data_settings['web_name']; ?> | Login</title>

    <meta name="description" content="<?php echo $data_settings['web_description']; ?>" />
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $data_settings['link_fav']; ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/config.js"></script>
    <?php echo $data_settings['seo_analytics']; ?>
    <script type="text/javascript">
    if (screen.width <= 699) {
      window.location = "<?php echo $cfg_baseurl; ?>/mobile/login";
    } else {
      var userAgent = navigator.userAgent.toLowerCase();
      if (userAgent.indexOf("iphone") !== -1 || userAgent.indexOf("ipod") !== -1) {
        window.location.replace("<?php echo $cfg_baseurl; ?>/mobile/login");
      }
    }
  </script>

  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="#7367F0" />
                      <path
                        opacity="0.06"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                        fill="#161616" />
                      <path
                        opacity="0.06"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                        fill="#161616" />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#7367F0" />
                    </svg>
                  </span>
                  <span class="app-brand-text demo text-body fw-bold ms-1"><?php echo $data_settings['web_name']; ?></span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1 pt-2">Welcome to <?php echo $data_settings['web_name']; ?>! 👋</h4>
              
              

              <form class="mb-3" method="POST" autocomplete="off">
                <div class="mb-3">
                  <label for="email" class="form-label">Username atau Email</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="username"
                    placeholder="Enter your username or email"
                    autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="../lupa">
                      <small>Forgot Password?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" name="login" type="submit">Sign in</button>
                </div>
              </form>

              <p class="text-center">
                <span>New on our platform?</span>
                <a href="../daftar">
                  <span>Create an account</span>
                </a>
              </p>

              
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/pages-auth.js"></script>
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
