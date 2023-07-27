<?php
session_start();
require("../lib/mainconfig.php");
$msg_type = "nothing";

/* CHECK FOR MAINTENANCE */
if ($cfg_mt == 1) {
    die("Web is under maintenance.");
} else {

    /* CHECK USER SESSION */
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
        }

        /* DATA FOR DASHBOARD */
        $check_order = mysqli_query($db, "SELECT SUM(jumlah_transfer) AS total FROM history_topup WHERE status = 'YES'");
        $data_order = mysqli_fetch_assoc($check_order);

        $number_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users"));

        $number_tickets = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tickets"));

        $number_order = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders"));

        $check_earnings = mysqli_query($db, "SELECT SUM(price - price_provider) AS total FROM orders WHERE status = 'Success'");
        $data_earnings = mysqli_fetch_assoc($check_earnings);

        $check_earnings_today = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date'");
        $data_earnings_today = mysqli_fetch_assoc($check_earnings_today);


        $number_order_completed = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE status = 'Success'"));
        $number_order_pending = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE status = 'Pending' OR  status = 'Processing' OR status = 'In Progress'"));

        /* DATA FOR ORDERS STATISTICS CHART */
        $date_1 = date('Y-m-d', (strtotime('-5 day', strtotime($date))));
        $date_2 = date('Y-m-d', (strtotime('-4 day', strtotime($date))));
        $date_3 = date('Y-m-d', (strtotime('-3 day', strtotime($date))));
        $date_4 = date('Y-m-d', (strtotime('-2 day', strtotime($date))));
        $date_5 = date('Y-m-d', (strtotime('-1 day', strtotime($date))));
        $date_6 = $date;

        $check_earning_date_1 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_1'");
        $check_earning_date_2 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_2'");
        $check_earning_date_3 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_3'");
        $check_earning_date_4 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_4'");
        $check_earning_date_5 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_5'");
        $check_earning_date_6 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_6'");

        $data_earnings_date_1 = mysqli_fetch_assoc($check_earning_date_1);
        $data_earnings_date_2 = mysqli_fetch_assoc($check_earning_date_2);
        $data_earnings_date_3 = mysqli_fetch_assoc($check_earning_date_3);
        $data_earnings_date_4 = mysqli_fetch_assoc($check_earning_date_4);
        $data_earnings_date_5 = mysqli_fetch_assoc($check_earning_date_5);
        $data_earnings_date_6 = mysqli_fetch_assoc($check_earning_date_6);

        /* GENERAL WEB SETTINGS */
        $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
        $data_settings = mysqli_fetch_assoc($check_settings);

        $email = $data_user['email'];
        $hp = $data_user['nohp'];
        /* if ($email == "") {
  header("Location: ".$cfg_baseurl2."settings.php");
  } */
    } else {
        header("Location: ../home");
    }

    $page = 'beranda'; // 
    


    $title = "beranda";
    include("../lib/header_admin.php");
    if (isset($_SESSION['user'])) {
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

    <title>Dashboard | <?php echo $data_settings['web_name']; ?></title>

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
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/config.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php
          include("../lib/sidebar_admin.php");
          ?>

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php
          include("../lib/navbar.php");
          ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div>
                  <div class="alert alert-primary" role="alert">
                    <marquee attribute_name = "attribute_value"....more attributes>
                      Layanan bantuan admin ke <a href="https://wa.me/6282221584446">WA +6282221584446</a> | Jangan gunakakan script sembarangan | Silahkan hubungi developer jika ingin tambah API operan atau ganti Design
                    </marquee>
                  </div>
                </div>
                <!-- View sales -->
                <div class="col-xl-4 mb-4 col-lg-5 col-12">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-7">
                        <div class="card-body text-nowrap">
                          <h5 class="card-title mb-0">Hai <?php echo $data_user['username']; ?>! 🎉</h5>
                          <p class="mb-2">Selamat Datang dihalaman Admin</p>
                          <h4 class="text-primary mb-1"><?php echo rupiah($data_order['total']); ?></h4>
                          <a href="deposit" class="btn btn-primary">Cek Riwayat</a>
                        </div>
                      </div>
                      <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="<?php echo $cfg_baseurl; ?>/assets/img/illustrations/card-advance-sale.png"
                            height="140"
                            alt="view sales" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- View sales -->

                <!-- Statistics -->
                <div class="col-xl-8 mb-4 col-lg-7 col-12">
                  <div class="card h-100">
                    <div class="card-header">
                      <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title mb-0">Data Statistik</h5>
                        <small class="text-muted">Updated Setiap Hari</small>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row gy-3">
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                              <i class="ti ti-chart-pie-2 ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo $number_order; ?></h5>
                              <small>Total Order</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2">
                              <i class="ti ti-users ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo $number_users; ?></h5>
                              <small>Pelanggan</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                              <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo $number_order_completed; ?></h5>
                              <small>Pesanan Selesai</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                              <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo rupiah($data_earnings_today['total']); ?></h5>
                              <small>Untung Hari Ini</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Statistics -->
              </div>
            </div>
            <!-- / Content -->

            <?php include("../lib/footer.php"); ?>

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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/dashboards-ecommerce.js"></script>
  </body>
</html>
    <?php
    }
    
}
    ?>