<?php
session_start();
require("../../lib/mainconfig.php");

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

  $title = "Order History";
  include("../../lib/header.php");
  $page = 'order';
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

    <title>Riwayat Pemesanan | <?php echo $data_settings['web_name']; ?></title>

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
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    
    <!-- Row Group CSS -->
    

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

        <?php
          include("../../lib/sidebar_user.php");
          ?>

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php
          include("../../lib/navbar_user.php");
          ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              
            
              <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive pt-0">
                  <table id="datasosmed" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Waktu</th>
                        <th>Layanan</th>
                        <th>Target</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    // start paging config
                    $query_order = mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' ORDER BY id DESC");
                    // end paging config
                    while ($data_order = mysqli_fetch_assoc($query_order)) {
                      if ($data_order['status'] == "Pending") {
                        $label = "warning";
                      } else if ($data_order['status'] == "Processing") {
                        $label = "info";
                      } else if ($data_order['status'] == "In Progress") {
                        $label = "info";
                      } else if ($data_order['status'] == "Error") {
                        $label = "danger";
                      } else if ($data_order['status'] == "Canceled") {
                        $label = "danger";
                      } else if ($data_order['status'] == "Partial") {
                        $label = "danger";
                      } else if ($data_order['status'] == "Success") {
                        $label = "success";
                      } else if ($data_order['status'] == "Completed") {
                        $label = "success";
                      }
                    ?>
                        <tr>
                            <td><?php echo $data_order['oid']; ?></td>
                            <td><?php 
                                $date = date('d M Y', strtotime($data_news['date']));
                                $time = date('H:i', strtotime($data_news['time']));
                                echo $date . ' ' . $time;
                                ?></td>
                            <td><?php echo substr($data_order['service'], 0, 20) ?></td>
                            
                            <td><?php echo substr($data_order['link'], 0, 20) ?></td>
                            <td><?php echo number_format($data_order['quantity'], 0, ',', '.'); ?></td>
                            <td><?php echo rupiah($data_order['price']); ?></td>
                            <td><span class="badge bg-<?php echo $label; ?>"><?php echo $data_order['status']; ?></span></td>
                            <td>
                              
                              <button type="button" class="btn rounded-pill btn-primary" disabled="true"><i class="ti ti-eye"></i></button>
                              

                            </td>
                            
                        </tr>
                        <?php
              }
              ?>
                      </tbody>

                      <tfoot>
                        <tr>
                          <th>ID</th>
                        <th>Waktu</th>
                        <th>Layanan</th>
                        <th>Target</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                      </tfoot>
                  </table>
                </div>
              </div>
              

            </div>
            <!-- / Content -->

            <?php
            include("../../lib/footer_user.php");
            ?>

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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <!-- Flat Picker -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/moment/moment.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/tables-datatables-basic.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
      $(document).ready(function () {
    $('#datasosmed').DataTable();
});
</script>

  </body>
</html>
<?php
     
    } else {
      header("Location: " . $cfg_baseurl);
    }
