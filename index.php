<?php
session_start();
require("lib/mainconfig.php");
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
        }

        /* DATA FOR DASHBOARD */
        $check_order = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username' AND status = 'Success' OR user = '$sess_username' AND status = 'Pending' OR user = '$sess_username' AND status = 'Processing' OR user = '$sess_username' AND status = 'In Progress'");
        $data_order = mysqli_fetch_assoc($check_order);
        $number_order = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username'"));
        $number_order_completed = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Success'"));
        $number_order_error = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Error'"));
        $number_order_partial = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Partial'"));
        $number_order_canceled = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Canceled'"));
        $number_order_pending = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND status = 'Pending' OR user = '$sess_username' AND status = 'Processing' OR user = '$sess_username' AND status = 'In Progress'"));
        $count_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users"));

        $count_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE status = 'Active'"));
        $user_total_aktif = $count_users - $number_order_canceled;



        $count_c_date_1 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_1'"));
        $count_c_date_2 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_2'"));
        $count_c_date_3 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_3'"));
        $count_c_date_4 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_4'"));
        $count_c_date_5 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_5'"));
        $count_c_date_6 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = 'Success' OR status = 'Completed') AND date = '$date_6'"));

        $count_p_date_1 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_1'"));
        $count_p_date_2 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_2'"));
        $count_p_date_3 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_3'"));
        $count_p_date_4 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_4'"));
        $count_p_date_5 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_5'"));
        $count_p_date_6 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' AND (status = !'Success' OR status = !'Completed') AND date = '$date_6'"));

        $check_order_today = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username' AND status = 'Success' AND date = '$date' OR user = '$sess_username' AND status = 'Pending' AND date = '$date' OR user = '$sess_username' AND status = 'Processing' AND date = '$date' OR user = '$sess_username' AND status = 'In Progress' AND date = '$date'");
        $data_order_today = mysqli_fetch_assoc($check_order_today);

        // Query untuk mendapatkan pesanan terbaru
        $query_latest_orders = mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username' ORDER BY id DESC LIMIT 5");

        /* GENERAL WEB SETTINGS */
        $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
        $data_settings = mysqli_fetch_assoc($check_settings);

        $email = $data_user['email'];
        $hp = $data_user['nohp'];
        /* if ($email == "") {
	header("Location: ".$cfg_baseurl2."settings.php");
	} */
    } else {
        header("Location: welcome");
    }
    $page = 'dashboard';
    $title = "Dashboard";
    include("lib/header.php");
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
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/apex-charts/apex-charts.css" />
    
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/swiper/swiper.css" />
    

    <!-- Page CSS -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/pages/cards-advance.css" />
    <!-- Helpers -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/config.js"></script>
    <!--HEADER TAG-->
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
        <?php include("lib/sidebar_user.php"); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <?php include("lib/navbar_user.php"); ?>
          

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Earning Reports -->
                <div class="col-xl-4 mb-4 col-lg-5 col-12">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-7">
                        <div class="card-body text-nowrap">
                          <h5 class="card-title mb-0">Hai, <?php echo $data_user['username']; ?>!🎉</h5>
                          <p class="mb-2">Sisa Saldo Kamu</p>
                          <?php
                                        if ($data_user['balance'] == "0" or $data_user['balance'] < 0) {
                                        ?>
                          <h4 class="text-primary mb-1"><?php echo rupiah($data_user['balance']); ?></h4>
                          <?php
                                        } ?>
                                        <?php
                                        if ($data_user['balance'] > 0) {
                                        ?>
                          <h4 class="text-primary mb-1"><?php echo rupiah($data_user['balance']); ?></h4>
                          <?php
                                        } ?>
                          <a href="tripay" class="btn btn-primary">Topup Sekarang</a>
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
                        <h5 class="card-title mb-0">Statistics</h5>
                        <small class="text-muted">Updated 1 month ago</small>
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
                              <h5 class="mb-0"><?php echo $user_total_aktif; ?></h5>
                              <small>User Aktif</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2">
                              <i class="ti ti-users ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo $number_order_pending; ?></h5>
                              <small>Pending</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                              <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo $number_order; ?></h5>
                              <small>Pemesanan</small>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-6">
                          <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                              <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <div class="card-info">
                              <h5 class="mb-0"><?php echo rupiah($data_order['total'], 4); ?></h5>
                              <small>Pengeluaran</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                      <h5 class="card-title m-0 me-2">Riwayat Pemesanan</h5>
                      
                    </div>
                    <div class="card-body pb-0">
                          <ul class="timeline ms-1 mb-0">
                            <?php
                                        $count = 1;
                                        while ($row = mysqli_fetch_assoc($query_latest_orders)) {
                                            $order_service = $row['service'];
                                            $order_date = $row['date'];
                                            $order_time = $row['time'];
                                            $order_status = $row['status'];
                                            $order_total = $row['price'];
                                            $order_oid = $row['oid'];

                                            $label = "";
                                            if ($order_status == "Pending") {
                                                $label = "warning";
                                            } else if ($order_status == "Processing") {
                                                $label = "info";
                                            } else if ($order_status == "In Progress") {
                                                $label = "secondary";
                                            } else if ($order_status == "Error") {
                                                $label = "danger";
                                            } else if ($order_status == "Canceled") {
                                                $label = "danger";
                                            } else if ($order_status == "Partial") {
                                                $label = "warning";
                                            } else if ($order_status == "Success") {
                                                $label = "primary";
                                            }
                                            ?>
                            <li class="timeline-item timeline-item-transparent ps-4">
                              <span class="timeline-point timeline-point-<?php echo $label; ?>"></span>
                              <div class="timeline-event">
                                <div class="timeline-header">
                                  <h6 class="mb-0"><?php
                                                    $short_order_service = strlen($order_service) > 25 ? substr($order_service, 0, 25) . "..." : $order_service;
                                                    echo $short_order_service;
                                                    ?></h6>
                                  <small class="text-muted"><?php
                                                    $jsDate = date_create($order_date);
                                                    echo date_format($jsDate, 'd M Y');
                                                    ?>
                                                    <?php
                                                      $jsTime = date_create($order_time);
                                                      echo date_format($jsTime, 'H:i');
                                                      ?></small>
                                </div>
                                <p class="mb-0">Pesanan #<?php echo $order_oid; ?> <?php echo $order_status; ?></p>
                              </div>
                            </li>
                            <?php
                                            $count++;
                                        }
                                        ?>
                          </ul>
                        </div>
                  </div>
                </div>
                <!--/ Last Transaction -->

                <!-- Activity Timeline -->
                <div class="col-lg-6 col-md-12 mb-4">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between">
                      <h5 class="card-title m-0 me-2 pt-1 mb-2">News Update</h5>
                    </div>
                    <div class="card-body pb-0">
                      <ul class="timeline ms-1 mb-0">
                        <?php
                    // start paging config
                    $query_list = "SELECT * FROM news ORDER BY id DESC"; // edit
                    $records_per_page = 5; // edit

                    $starting_position = 0;
                    if(isset($_GET["page_no"])) {
                      $starting_position = ($_GET["page_no"]-1) * $records_per_page;
                    }
                    $new_query = $query_list." LIMIT $starting_position, $records_per_page";
                    $new_query = mysqli_query($db, $new_query);
                    // end paging config
                        $no = 1;
                        while ($data_news = mysqli_fetch_assoc($new_query)) {
                          if($data_news['status'] == "INFO") {
                            $label = "info";
                            $label2 = "INFO";
                          } else if($data_news['status'] == "NEW SERVICE") {
                            $label = "success";
                            $label2 = "NEW SERVICE";
                          } else if($data_news['status'] == "SERVICE") {
                            $label = "success";
                            $label2 = "SERVICE";                            
                          } else if($data_news['status'] == "MAINTENANCE") {
                            $label = "danger";
                            $label2 = "MAINTENANCE";                                                    
                          } else if($data_news['status'] == "UPDATE") {
                            $label = "warning";
                            $label2 = "UPDATE";           
                          }
                    ?>
                        <li class="timeline-item timeline-item-transparent ps-4">
                          <span class="timeline-point timeline-point-<?php echo $label; ?>"></span>
                          <div class="timeline-event">
                            <div class="timeline-header">
                              <h6 class="mb-0"><?php echo $data_news['status']; ?></h6>
                              <small class="text-muted"><?php echo $data_news['date']; ?> <?php echo $data_news['time']; ?></small>
                            </div>
                            <p class="mb-2">Oleh Admin</p>
                            <div class="d-flex flex-wrap">
                              <div class="avatar me-2">
                                <img src="<?php echo $data_news['gambar']; ?>" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div class="ms-1">
                                <h6 class="mb-0"><?php echo nl2br(substr($data_news['content'], 0, 50)); ?></h6>
                                <span>Lihat Full <a href="news">Disini</a></span>
                              </div>
                            </div>
                          </div>
                        </li>
                        <?php
                    $no++;
                    }
                    ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
             <?php include("lib/footer_user.php"); ?>
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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/swiper/swiper.js"></script>
    

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/dashboards-analytics.js"></script>
  </body>
</html>



    <?php
    }
    
}
    ?>