<?php
session_start();
require("../../lib/mainconfig.php");


if (!isset($_SESSION)) {
  session_start();
}
/* CLEARING POST DATA IF EXISTS*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['postdata'] = $_POST;
  unset($_POST);
  header("Location: " . $_SERVER[REQUEST_URI]);
  exit;
}

if (@$_SESSION['postdata']) {
  $_POST = $_SESSION['postdata'];
  unset($_SESSION['postdata']);
}
//clear

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $sess_id = $_SESSION['user']['id'];
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
  $title = "Deposit History";
  include("../../lib/header.php");
  $page = 'deposit';
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

    <title>Riwayat Deposit | <?php echo $data_settings['web_name']; ?></title>

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
                        <th>No</th>
                        <th>No. Invoice</th>
                        <th>Waktu</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                    // start paging config
                    $query_order = mysqli_query($db, "SELECT * FROM deposits WHERE user = '$sess_id' ORDER BY id DESC");
                    // end paging config

                    while ($data_order = mysqli_fetch_assoc($query_order)) {
                      if ($data_order['status'] == "Error") {
                        $label = "danger";
                        $label2 = "Error";
                      } else if ($data_order['status'] == "Pending") {
                        $label = "warning";
                        $label2 = "Pending";
                      } else if ($data_order['status'] == "Success") {
                        $label = "primary";
                        $label2 = "Success";
                      } else if ($data_order['status'] == "Expired") {
                        $label = "secondary";
                        $label2 = "Expired";
                      }
                    ?>
                      <?php $no = $no + 1; ?>
                        <tr>
                          <td><?php echo $no ?></td>
                            <td><?php echo $data_order['invoice_number']; ?></td>
                            <td><?php echo $data_order['created_at']; ?></td>
                            
                            <td><?php echo $data_order['code']; ?></td>
                            <td><?php echo rupiah($data_order['balance']); ?></td>
                            <td><span class="badge bg-<?php echo $label; ?>"><?php echo $label2; ?></span></td>
                            <td>
                              <?php
                                if ($data_order['status'] == "Expired" || $data_order['status'] == "Error" || $data_order['status'] == "Success") {
                                    ?>
                              <button type="button" class="btn rounded-pill btn-danger" disabled="true"><i class="ti ti-eye-off"></i> Bayar</button>
                              <?php
                          } else { ?>
                            <button class="btn rounded-pill btn-warning datasosmed_bayar"><i class="ti ti-eye"></i>Bayar <input type="hidden" class="datasosmed_bayar_id" value="<?php echo $data_order['id']; ?>"/></button>
                              <?php
                          }
                          ?>

                            </td>
                            
                        </tr>
                        <?php
              }
              ?>
                      </tbody>

                      <tfoot>
                        <tr>
                          <th>No</th>
                        <th>No. Invoice</th>
                        <th>Waktu</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                      </tfoot>
                  </table>
                </div>
              </div>
              
              <!-- Modal -->
              <div class="modal fade" id="instructionsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="instructionsModal">Pembayaran</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div id="data" style="margin: 10px;"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                      
                    </div>
                  </div>
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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/menu.js"></script>
    
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/bs-stepper/bs-stepper.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>
    

    <!-- Page JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-add-new-cc.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-add-new-address.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-edit-user.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-enable-otp.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-share-project.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-create-app.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/modal-two-factor-auth.js"></script>
    <script>
        $(document).ready(function() {
            $('#datasosmed').DataTable();
        });
    </script>
    <script>
      $('.datasosmed_bayar').click(function(){
        var element = $(this).closest('tr');
        var id = element.find(".datasosmed_bayar_id").val();
        
        if (id == "") {
          document.getElementById("data").innerHTML = "";
          return;
        } else {
          $('#instructionsModal').modal('show');
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("data").innerHTML = this.responseText;
            }
          };
          xmlhttp.open("GET", "instruction.php?id=" + id, true);
          xmlhttp.send();
        }
      });
    </script>
  </body>
</html>
<?php
       
      } else {
        header("Location: " . $cfg_baseurl);
      } ?>