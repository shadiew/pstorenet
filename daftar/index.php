<?php
session_start();
require("../lib/mainconfig.php");
require("../lib/password.php"); // Menyertakan file library password_compat

$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
$msg_type = "nothing";

if (isset($_POST['signup'])) {
    $post_email = htmlspecialchars(trim($_POST['email']));
    $post_name = htmlspecialchars(trim($_POST['name']));
    $post_username = htmlspecialchars(trim($_POST['username']));
    $post_username = str_replace(' ', '', $post_username); // Menghapus spasi pada username
    $post_nohp = htmlspecialchars(trim($_POST['nohp']));
    $post_password = htmlspecialchars(trim($_POST['password']));
    $post_confirm = htmlspecialchars(trim($_POST['confirm']));

    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
    $check_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$post_email'");
    $check_nohp = mysqli_query($db, "SELECT * FROM users WHERE nohp = '$post_nohp'");
    $ip = $_SERVER['REMOTE_ADDR'];

    // Blokir penggunaan email yang tidak diizinkan
    $allowed_domains = array("gmail.com", "yahoo.com", "outlook.com");
    $email_domain = substr(strrchr($post_email, "@"), 1);
    if (!in_array($email_domain, $allowed_domains)) {
        $msg_type = "error";
        $msg_content = "Invalid email domain. Only @gmail.com, @yahoo.com, and @outlook.com are allowed.";
    } elseif (empty($post_email) || empty($post_username) || empty($post_name) || empty($post_nohp) || empty($post_password)) {
        $msg_type = "error";
        $msg_content = "Input To Fill All.";
    } else if (mysqli_num_rows($check_email) > 0) {
        $msg_type = "error";
        $msg_content = "The Email You Enter is Registered.";
    } else if (mysqli_num_rows($check_user) > 0) {
        $msg_type = "error";
        $msg_content = "The username you entered is already registered.";
    } else if (strlen($post_password) < 5) {
        $msg_type = "error";
        $msg_content = "Minimum 5 characters password.";
    } else if ($post_password !== $post_confirm) {
        $msg_type = "error";
        $msg_content = "Password is not the same.";
    } else {
        $post_apikey = random(20);
        $post_kunci = random(5);
        $ip = $_SERVER['REMOTE_ADDR'];

        // Hash the password using bcrypt
        $hashed_password = password_hash($post_password, PASSWORD_BCRYPT);

        $insert_user = mysqli_query($db, "INSERT INTO users (email, name, username, password, nohp, balance, level, registered, status, api_key, uplink, otp, point, ip) VALUES ('$post_email', '$post_name', '$post_username', '$hashed_password', '$post_nohp', '0', 'Member', '$date $time', 'Active', '$post_apikey', 'Server', '$post_kunci', '0', '$ip')");

        if ($insert_user) {
            $msg_type = "success";
            $msg_content = "Account Registered, Please Enter.";
        } else {
            $msg_type = "error";
            $msg_content = "A System Error Occurred.";
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
  data-assets-path="../assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Halaman Pendaftaran</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="../assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Register Card -->
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
                  <span class="app-brand-text demo text-body fw-bold ms-1">Vuexy</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1 pt-2">Adventure starts here 🚀</h4>
              <p class="mb-4">Make your app management easy and fun!</p>
              
              <form class="mb-3" method="POST" autocomplete="off">

                <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-first-name">Nama Lengkap</label>
                      <input type="text" name="name" class="form-control" placeholder="Jhon Dhoe" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-last-name">Username</label>
                      <input type="text" name="username" class="form-control" placeholder="jhondoe" />
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="multicol-first-name">No. Whatsapp</label>
                      <input type="number" minlength="8" maxlength="15" name="nohp" class="form-control" placeholder="628xxxx" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-last-name">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="hallojhondhoe@mail.com" />
                    </div>

                    <div class="col-md-6">
                      <div class="form-password-toggle">
                        <label class="form-label" for="multicol-password">Password</label>
                        <div class="input-group input-group-merge">
                          <input
                            type="password"
                            name="password"
                            id="multicol-password"
                            class="form-control"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="multicol-password2" />
                          <span class="input-group-text cursor-pointer" id="multicol-password2"
                            ><i class="ti ti-eye-off"></i
                          ></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-password-toggle">
                        <label class="form-label" for="multicol-confirm-password">Confirm Password</label>
                        <div class="input-group input-group-merge">
                          <input
                            type="password"
                            name="confirm"
                            id="multicol-confirm-password"
                            class="form-control"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="multicol-confirm-password2" />
                          <span class="input-group-text cursor-pointer" id="multicol-confirm-password2"
                            ><i class="ti ti-eye-off"></i
                          ></span>
                        </div>
                      </div>
                    </div>



                </div>
                <div class="pt-4">
                  <button name="signup" value="signup" class="btn btn-primary d-grid w-100">Sign up</button>
                </div>
              </form>

              <p class="text-center">
                <span>Already have an account?</span>
                <a href="../login">
                  <span>Sign in instead</span>
                </a>
              </p>

              
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="../assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="../assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-auth.js"></script>
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
