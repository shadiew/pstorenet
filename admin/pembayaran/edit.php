<?php
session_start();
require("../../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
  $data_user = mysqli_fetch_assoc($check_user);
  if (mysqli_num_rows($check_user) == 0) {
    header("Location: ".$cfg_baseurl."/logout/");
  } else if ($data_user['status'] == "Suspended") {
    header("Location: ".$cfg_baseurl."/logout/");
  } else if ($data_user['level'] != "Developers") {
    header("Location: ".$cfg_baseurl);
  } else {
    if (isset($_GET['id'])) {
      $post_id = $_GET['id'];
      $checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_id'");
      $datadb_service = mysqli_fetch_assoc($checkdb_service);
      if (mysqli_num_rows($checkdb_service) == 0) {
        header("Location: ".$cfg_baseurl."/admin/depo/");
      } else {
        if (isset($_POST['edit'])) {
          
          $post_Active = htmlspecialchars($_POST['Active']);
          $post_id = htmlspecialchars($_POST['id']);
          
          if (empty($post_Active)) {
            $msg_type = "error";
            $msg_content = "Please Fill In All Inputs.";
          } else if ($post_Active == null) {
            $msg_type = "error";
            $msg_content = "Input Error Occurred.";
          } else {
            $update_service = mysqli_query($db, "UPDATE deposit_method SET Active = '$post_Active' where id='$post_id'");
            if ($update_service == TRUE) {
              $msg_type = "success";
              $msg_content = "Berhasil diperbaharui";
            } else {
              $msg_type = "error";
              $msg_content = "A System Error Occurred.";
            }
          }
        }
        $checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_id'");
        $datadb_service = mysqli_fetch_assoc($checkdb_service);
        $title = "Change Deposit Method";
        include("../../lib/header_admin.php");
        $page = 'pembayaran';
?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Edit Metode Pembayaran | <?php echo $data_settings['web_name']; ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/select2/select2.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php
          include("../../lib/sidebar_admin.php");
          ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php
          include("../../lib/navbar.php");
          ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
                 
            <div class="container-xxl flex-grow-1 container-p-y">
              <?php 
                if ($msg_type == "success") {
                ?>   
              <div class="card-body">
                <div class="alert alert-warning" role="alert"><?php echo $msg_content; ?></div>
              </div>
              <?php
                } else if ($msg_type == "error") {
                ?>
              <div class="card-body">
                <div class="alert alert-danger" role="alert"><?php echo $msg_content; ?></div>
              </div>
              <?php
                }
                ?>

              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Ubah Metode Pembayaran</h5>
                      
                    </div>
                    <div class="card-body">
                      <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label"> ID</label>
                              <input type="number" readonly class="form-control" name="id" value="<?php echo $datadb_service['id']; ?>" readonly />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">Status</label>
                              <select class="form-control" id="Active" name="Active">
                            <option value="<?php echo $datadb_service['Active']; ?>">Selected [<?php echo $datadb_service['Active']; ?>]</option>
                            <option value="YES">YES</option>
                            <option value="NO">NO</option>
                          </select>
                            </div>
                          </div>

                          <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Nama</label>
                              <input type="tex" value="<?php echo $datadb_service['name']; ?>" class="form-control">
                            </div>
                          </div>

                        
                        
                        <div class="pt-4">
                          <button type="submit" name="edit" class="btn btn-primary">Update</button>
                          <a href="../pembayaran" class="btn btn-info">Kembali</a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Informasi Wesbite</h5>
                      
                    </div>
                    <div class="card-body">
                      <p>
                       Silahkan sesuaikan data yang akan diubah sesuai kebutuhan anda, data edit masih dapat di rubah kembali selama masih tersimpan dalam database!
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include("../../lib/footer.php"); ?>
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
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="../../assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="../../assets/vendor/libs/moment/moment.js"></script>
    <script src="../../assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="../../assets/vendor/libs/select2/select2.js"></script>

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../assets/js/form-layouts.js"></script>
  </body>
</html>
<?php
        
      }
    } else {
      header("Location: ".$cfg_baseurl."/admin/pembayaran/");
    }
  }
} else {
  header("Location: ".$cfg_baseurl);
}
?>