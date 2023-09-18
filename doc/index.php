<?php
session_start();
require("../lib/mainconfig.php");

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
  $data_user = mysqli_fetch_assoc($check_user);
  if (mysqli_num_rows($check_user) == 0) {
    header("Location: ".$cfg_baseurl."/logout/");
  } else if ($data_user['status'] == "Suspended") {
    header("Location: ".$cfg_baseurl."/logout/");
  }
  $email = $data_user['email'];
  if ($email == "") {
  header("Location: ".$cfg_baseurl."settings");
  }
}

$title = "API Doc";
include("../lib/header.php");
$page = 'doc';
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

    <title>Dokumentasi API | <?php echo $data_settings['web_name']; ?></title>

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
              

              <div class="row">
                
                <div class="col-xl-12">
                  <h6 class="text-muted">Dokumentasi API</h6>
                  <div class="nav-align-left nav-tabs-shadow mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link active"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-left-home"
                          aria-controls="navs-left-home"
                          aria-selected="true">
                          URL
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-left-profile"
                          aria-controls="navs-left-profile"
                          aria-selected="false">
                          Tambah Pemesanan
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-left-messages"
                          aria-controls="navs-left-messages"
                          aria-selected="false">
                          Cek Pesanan
                        </button>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="navs-left-home">
                        
                        <table class="table table-bordered">
                            <tbody>
                                                <tr>
                                <td>HTTP Method</td>
                                <td>POST</td>
                              </tr>
                              <tr>
                                <td>Response format</td>
                                <td>JSON</td>
                              </tr>
                                                <tr>
                                <td>API URL</td>
                                <td><?php echo $cfg_baseurl; ?>/api/v1/</td>
                              </tr>
                                                <tr>
                                <td>API KEY</td>
                                <td><?php echo $data_user['api_key']; ?></td>
                              </tr>
                              
                            </tbody>
                           </table>

                        
                      </div>
                      <div class="tab-pane fade" id="navs-left-profile">
                        
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                              </tr>
                            </thead>
                            <tbody>
                                                <tr>
                                <td>key</td>
                                <td>Your API key</td>
                              </tr>
                              <tr>
                                <td>action</td>
                                <td>add</td>
                              </tr>
                              <tr>
                                <td>service</td>
                                <td>Service ID <a href="<?php echo $cfg_baseurl; ?>/harga">Check at price list</a></td>
                              </tr>
                              <tr>
                                <td>link</td>
                                <td>Link to page</td>
                              </tr>
                              <tr>
                                <td>quantity</td>
                                <td>Needed quantity</td>
                              </tr>
                            </tbody>
                           </table>
<br>
                        <div class="card">
                          <div class="card-body">
                            <pre>
IF ORDER SUCCESS

  {
    "order":"12345"
  }

IF ORDER FAIL

  {
    "error":"Incorrect request"
  }
</pre>
                            
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-left-messages">
                        
                        <table class="table table-bordered">
                <thead>
                                    <tr>
                    <th>Parameters</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                                    <tr>
                    <td>key</td>
                    <td>Your API key</td>
                  </tr>
                  <tr>
                    <td>action</td>
                    <td>status</td>
                  </tr>
                  <tr>
                    <td>order</td>
                    <td>Your order id</td>
                  </tr>
                </tbody>
                           </table>

                           <br>
                        <div class="card">
                          <div class="card-body">

                        <pre>
  IF CHECK STATUS SUCCESS
  {
    "charge":"10000",
    "start_count":"123",
    "status":"Success",
    "remains":"0"
  }
                            

  IF CHECK STATUS FAIL

  {
    "error":"Incorrect request"
  }
                            </pre>
                          </div>
                        </div>
                      </div>
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

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
  </body>
</html>
