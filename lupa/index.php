<?php
session_start();
require("../lib/mainconfig.php");

$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
$msg_type = "nothing";

$connection = mysqli_connect($db_server, $db_user, $db_password, $db_name);

// Memastikan koneksi berhasil
if (!$connection) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$msg_type = "";
$msg_content = "";

// Menghandle aksi reset password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);
    $confirmPassword = sanitizeInput($_POST["confirm_password"]);

    // Memeriksa apakah username dan email cocok
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        // Memeriksa apakah password dan konfirmasi password cocok
        if ($password === $confirmPassword) {
            // Memeriksa panjang minimal password (misalnya minimal 6 karakter)
            if (strlen($password) >= 6) {
                // Enkripsi password menggunakan Bcrypt
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Mengupdate password baru berdasarkan username dan email
                $query = "UPDATE users SET password = '$hashedPassword' WHERE username = '$username' AND email = '$email'";
                $result = mysqli_query($connection, $query);

                if ($result) {
                    // Password berhasil direset
                    $msg_type = "success";
                    $msg_content = "Password berhasil direset.";
                } else {
                    // Password gagal direset
                    $msg_type = "error";
                    $msg_content = "Gagal mereset password.";
                }
            } else {
                // Password tidak memenuhi panjang minimal
                $msg_type = "error";
                $msg_content = "Password harus memiliki minimal 6 karakter.";
            }
        } else {
            // Password dan konfirmasi password tidak cocok
            $msg_type = "error";
            $msg_content = "Password dan konfirmasi password tidak cocok.";
        }
    } else {
        // Username dan email tidak cocok
        $msg_type = "error";
        $msg_content = "Username dan email tidak cocok.";
    }
}

// Menutup koneksi database
mysqli_close($connection);

// Fungsi untuk membersihkan input dari karakter khusus
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
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

    <title><?php echo $data_settings['web_name']; ?> | Reset Password</title>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--!
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
  -->

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
              <h4 class="mb-1 pt-2">Reset Password <?php echo $data_settings['web_name']; ?>! 👋</h4>
              
              

              <form class="mb-3" method="POST">
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    name="username"
                    placeholder="Enter your username"
                    autofocus />
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input
                    type="text"
                    class="form-control"
                    
                    name="email"
                    placeholder="Enter your email"
                    autofocus />
                </div>
                <div class="mb-3">
                  <labelclass="form-label">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    
                    name="password"
                    placeholder="Password"
                    autofocus />
                </div>
                <div class="mb-3">
                  <label class="form-label">Konfirmasi Password</label>
                  <input
                    type="password"
                    class="form-control"
                    
                    name="confirm_password"
                    placeholder="Password"
                    autofocus />
                </div>
                
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" value="Reset Password" name="submit" type="submit">Reset Password</button>
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
    <script>
        <?php if ($msg_type == "error") { ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $msg_content; ?>',
                timer: 3000
            });
        <?php } else if ($msg_type == "success") { ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $msg_content; ?>',
                timer: 3000
            }).then(function() {
                setTimeout(function() {
                    window.location.href = "../login/";
                }, 3000);
            });
        <?php } ?>
    </script>
  </body>
</html>
