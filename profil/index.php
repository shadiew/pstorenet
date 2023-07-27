<?php
session_start();
require("../lib/mainconfig.php");

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
  $page = "Settings";
  include("../lib/header.php");
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
            $msg_content = "API Key has been changed to $set_api_key";
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

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo $cfg_baseurl; ?>/assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Profile | <?php echo $data_settings['web_name']; ?></title>

    <meta name="description" content="" />

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
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/pages/page-user-view.css" />
    <!-- Helpers -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/config.js"></script>
    <?php echo $data_settings['seo_meta']; ?>
    <!--HEADER TAG END-->

    <!--GTAG TAG-->
    <?php echo $data_settings['seo_analytics']; ?>
    <!--GTAG TAG END-->
    <?php echo $data_settings['seo_chat']; ?>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php include("../lib/sidebar_user.php"); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
            <?php include("../lib/navbar_user.php"); ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              
              <div class="row">
                <!-- User Sidebar -->
                <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                  <!-- User Card -->
                  <div class="card mb-4">
                    <div class="card-body">
                      
                      <div class="info-container">
                        <ul class="list-unstyled">
                          <li class="mb-2">
                            <span class="fw-semibold me-1">Nama Lengkap:</span>
                            <span><?php echo $data_user['name']; ?></span>
                          </li>
                          <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Email:</span>
                            <span><?php echo $data_user['email']; ?></span>
                          </li>
                          <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Status:</span>
                            <span class="badge bg-label-success"><?php echo $data_user['status']; ?></span>
                          </li>
                          <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Role:</span>
                            <span><?php echo $data_user['level']; ?></span>
                          </li>
                          <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">IP Address:</span>
                            <span><?php echo $data_user['ip']; ?></span>
                          </li>
                          <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">API Key:</span>
                            <span><?php echo $data_user['api_key']; ?></span>
                          </li>
                          <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">No Hp:</span>
                            <span><?php echo $data_user['nohp']; ?></span>
                          </li>
                          
                        </ul>
                        <form class="form-horizontal" role="form" method="POST">
                        <div class="d-flex justify-content-center">
                          <button name="change_api"
                            type="submit" 
                            class="btn btn-primary me-3"
                            
                            > Update Api Keys </button>
                          
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>
                  <!-- /User Card -->
                  
                </div>
                <!--/ User Sidebar -->

                <!-- User Content -->
                <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                  

                  <!-- Project table -->
                  <div class="card mb-4">
                    <div class="card-body">
                      <form method="POST" autocomplete="off">
                        <div class="row g-3">
                              <div class="col-md-12">
                                <label class="form-label">Password Lama</label>
                                <input type="password" name="password" class="form-control" placeholder="*****" autofocus />
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Passoword Baru</label>
                                <input type="password" name="npassword" class="form-control" placeholder="*****" />
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Konfirmasi Passoword</label>
                                <input type="password" name="cnpassword" class="form-control" placeholder="*****" />
                              </div>
                              <div class="pt-4">
                                <button type="submit" name="change_pswd" class="btn btn-primary">Tambah</button>
                                
                              </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!-- /Project table -->

                  
                </div>
                <!--/ User Content -->
              </div>

              

              <!-- /Modal -->
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include("../lib/footer_user.php"); ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/moment/moment.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-edit-user.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/app-user-view.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/app-user-view-account.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
  <?php if ($msg_type == "error") { ?>
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '<?php echo $msg_content; ?>!',
      timer: 3000
    });
  <?php } elseif ($msg_type == "success") { ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?php echo $msg_content; ?>!',
      timer: 3000
    });
  <?php } ?>
</script>
  </body>
</html>
<?php
  
} else {
  header("Location: ".$cfg_baseurl);
}
?>