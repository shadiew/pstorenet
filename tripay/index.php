<?php
session_start();
require("../lib/mainconfig.php");

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
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


  $check_paket = mysqli_query($db, "SELECT * FROM deposit_method WHERE Active = 'YES' AND name_method = 'TRIPAY'");
  $payment_method = array();

  if (mysqli_num_rows($check_paket) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($check_paket)) {
      array_push($payment_method, $row);
    }
  }

  /* GENERAL WEB SETTINGS */
  $title = "Deposit";
  $page = 'tripay';
  include("../lib/header.php");
  $msg_type = "nothing";
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

    <title><?php echo $data_settings['web_name']; ?> | Tripay</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $cfg_baseurl; ?>/assets/img/favicon/favicon.ico" />

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
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/config.js"></script>
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
            	<?php
									if ($msg_type == "success") {
									?>
              <div class="alert alert-primary" role="alert">Berhasil! Pesanan Anda Sedang Diproses Server</div>
              <?php
									} else if ($msg_type == "error") {
									?>
							<div class="alert alert-danger" role="alert">Error! <?php echo $msg_content; ?></div>
							<?php
									}
									?>
              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">🔥 Deposit Instant 🔥</h5>
                    </div>
                    <div class="card-body">
                      <form method="POST" name="tripayForm" autocomplete="off" action="verify.php">
                        
                        <div class="mb-3">
                          <label class="form-label">Jumlah</label>
                          <input class="form-control" placeholder="Rp." id="amount_field" required type="number" name="amount" min="10000" autofocus/>
                        </div>

                        <div class="col mt-2">
                        	<?php
                      $check_paket = mysqli_query($db, "SELECT * FROM deposit_method WHERE Active = 'YES' AND name_method = 'TRIPAY' ORDER BY id DESC");
                      while ($data_paket = mysqli_fetch_assoc($check_paket)) {
                      ?>
                              <div class="form-check form-check-inline">
                                <input
                                  value="<?php echo $data_paket['code']; ?>"
                                  id="<?php echo $data_paket['code']; ?>"
                                  name="method"
                                  class="form-check-input"
                                  type="radio"
                                  />
                                <label class="form-check-label" for="<?php echo $data_paket['code']; ?>"
                                  ><?php echo $data_paket['name']; ?>  <img height="25px" src="../assets/img/tripay/<?php echo $data_paket['code']; ?>.webp"></label
                                >
                              </div>
                              <br>
                              <?php
                      }
                      ?>
                        </div>
                        <br>

                        
                        
                        <button type="submit" value="Deposit" name="deposit"  class="btn btn-primary">Deposit Sekarang</button>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Informasi Website</h5>
                      
                    </div>
                    <div class="card-body">
                      <div id="information"></div>
                    </div>
                  </div>
                </div>
              </div>

             
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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/moment/moment.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/form-layouts.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      var paymentMethod = <?php echo json_encode($payment_method) ?>;
      var selectedPayment;
      $('input:radio[name="method"]')
        .change(function() {
          var str = "";
          str += $(this).val();
          selectedPayment = paymentMethod.filter(function(payment) {
            return payment.code == str;
          });
          if (selectedPayment.length > 0) {
            $("#information").html("<span>" + selectedPayment[0]?.note + "</span><br><b>Admin Fee: " + selectedPayment[0]?.rate + "</b><hr><br><h5>Auto Approval</h5>");
          } else {
            $("#information").html("<span>Please Select Payment</span>");
          }
        })
        .change();
    });
  </script>
	
  </body>
</html>



<?php
  
} else {
  header("Location: " . $cfg_baseurl);
}
?>