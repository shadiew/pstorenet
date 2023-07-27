<?php
session_start();
require("../../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
  $data_user = mysqli_fetch_assoc($check_user);
  if (mysqli_num_rows($check_user) == 0) {
    header("Location: " . $cfg_baseurl . "/logout/");
  } else if ($data_user['status'] == "Suspended") {
    header("Location: " . $cfg_baseurl . "/logout/");
  } else if ($data_user['level'] != "Developers") {
    header("Location: " . $cfg_baseurl);
  } else {

    $title = "Order List";
    include("../../lib/header_admin.php");

    // widget
    $check_worder = mysqli_query($db, "SELECT SUM(price) AS total FROM orders");
    $data_worder = mysqli_fetch_assoc($check_worder);
    $check_worder = mysqli_query($db, "SELECT * FROM orders");
    $count_worder = mysqli_num_rows($check_worder);

    $check_worder_success = mysqli_query($db, "SELECT SUM(price) AS total FROM profit WHERE status = 'Success'");
    $data_worder_success = mysqli_fetch_assoc($check_worder_success);
    $check_worder_success = mysqli_query($db, "SELECT * FROM orders");
    $count_worder_success = mysqli_num_rows($check_worder_success);

    $check_worder_provider = mysqli_query($db, "SELECT SUM(price_provider) AS total FROM profit WHERE status = 'Success'");
    $data_worder_provider = mysqli_fetch_assoc($check_worder_provider);
    $check_worder_provider = mysqli_query($db, "SELECT * FROM orders");
    $count_worder_provider = mysqli_num_rows($check_worder_provider);

    $pesanan = $data_worder_success['total'];
    $pusat = $data_worder_provider['total'];
    $keuntungan = $pesanan - $pusat;

    $page = 'pembelian';
    $pages = 'transaksi';
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

    <title>Riwayat Pembelian | <?php echo $data_settings['web_name']; ?></title>

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
          include("../../lib/sidebar_admin.php");
          ?>

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
              <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive pt-0">
                  <table id="datauser" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>OID</th>
                        <th>Username</th>
                        <th>Waktu</th>
                        <th>Layanan</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Harga API</th>
                        <th>API</th>
                        <th>OID Provider</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // start paging config
                      $query_list = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC"); // edit
                      // end paging config
                      while ($data_show = mysqli_fetch_assoc($query_list)) {
                        if ($data_show['status'] == "Pending") {
                          $label = "warning";
                        } else if ($data_show['status'] == "Processing") {
                          $label = "info";
                        } else if ($data_show['status'] == "In Progress") {
                          $label = "warning";
                        } else if ($data_show['status'] == "Error") {
                          $label = "danger";
                        } else if ($data_show['status'] == "Canceled") {
                          $label = "danger";
                        } else if ($data_show['status'] == "Partial") {
                          $label = "danger";
                        } else if ($data_show['status'] == "Success") {
                          $label = "primary";
                        } else if ($data_show['status'] == "Completed") {
                          $label = "primary";
                        }
                      ?>
                        <tr>
                            <td><?php echo $data_show['oid']; ?></td>
                            <td><?php echo $data_show['user']; ?></td>
                            <td><?php echo $data_show['date']; ?> <?php echo $data_show['time']; ?></td>
                            <td><?php echo substr($data_show['service'], 0, 20)?></td>
                            <td><?php echo rupiah($data_show['quantity']); ?></td>
                          <td><?php echo rupiah($data_show['price']); ?></td>
                          <td><?php echo rupiah($data_show['price_provider']); ?></td>
                          <td><?php if (empty($data_show['provider'])) {
                              echo "MANUAL";
                            } else {
                              echo $data_show['provider'];
                            } ?></td>
                          <td><?php echo $data_show['poid']; ?></td>
                          <td>
                            <div class="demo-inline-spacing">
                              <span class="badge rounded-pill bg-<?php echo $label; ?>"><?php echo $data_show['status']; ?></span>
                            </div>
                          </td>
                          <td><a href="edit.php?poid=<?php echo $data_show['poid']; ?>" class="btn btn-xs rounded-pill btn-label-primary">Edit</a>
                            <a href="detail.php?poid=<?php echo $data_show['poid']; ?>" class="btn btn-xs rounded-pill btn-label-warning">Detail</a>
                          </td>
                        </tr>
                        <?php
              }
              ?>
                      </tbody>

                      <tfoot>
                        <tr>
                        <th>OID</th>
                        <th>Username</th>
                        <th>Waktu</th>
                        <th>Layanan</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Harga API</th>
                        <th>API</th>
                        <th>OID Provider</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </tfoot>
                  </table>
                </div>
              </div>
              

              
            </div>
            <!-- / Content -->

            <?php
            include("../../lib/footer.php");
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
    $('#datauser').DataTable();
});
</script>
  </body>
</html>
<?php

  }
} else {
  header("Location: ".$cfg_baseurl);
}
?>