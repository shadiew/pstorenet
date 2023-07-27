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
    if (isset($_POST['delete'])) {
      $post_sid = $_POST['sid'];
      $checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
      if (mysqli_num_rows($checkdb_service) == 0) {
        $msg_type = "error";
        $msg_content = "Service Cannot Be Found.";
      } else {
        $delete_user = mysqli_query($db, "DELETE FROM services WHERE sid = '$post_sid'");
        if ($delete_user == TRUE) {
          $msg_type = "success";
          $msg_content = "Service Deleted.";
        }
      }
    }
  $title = "List of services";
  include("../../lib/header_admin.php");
  $page = 'sosmed';
  $pages = 'sosmeds';
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

    <title>Data Layanan Sosmed | <?php echo $data_settings['web_name']; ?></title>

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
                 
              <div>
              <a href="add" class="btn rounded-pill btn-primary">
                  <span class="ti-xs ti ti-layout-grid-add"></span>Tambah Layanan
              </a>
            </div>
            <br>
              <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive pt-0">
                  <table id="datasosmed" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Layanan</th>
                        <th>Min. Order</th>
                        <th>Max Order</th>
                        <th>Harga</th>
                        <th>Harga Service</th>
                        <th>PID</th>
                        <th>SID</th>
                        <th>API</th>
                        <th>Aksi</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php
              // start paging config
              $query_list = mysqli_query($db, "SELECT * FROM services"); // edit
              //$query_list1 = mysqli_query($db, "SELECT * FROM services ORDER BY sid ASC"); // edit
              // end paging config
              while ($data_show = mysqli_fetch_assoc($query_list)) {
              ?>
                        <tr>
                          <td>#<?php echo $data_show['id']; ?></td>
                            <td><?php echo $data_show['category']; ?></td>
                            <td><?php echo $data_show['service']; ?></td>
                            
                            <td><?php echo number_format($data_show['min']); ?></td>
                            <td><?php echo number_format($data_show['max']); ?></td>
                            <td><?php echo rupiah($data_show['price']); ?></td>
                            <td>$<?php echo $data_show['price_provider']; ?></td>
                            <td><?php echo $data_show['pid']; ?></td>
                            <td><?php echo $data_show['sid']; ?></td>
                            <td><?php echo $data_show['provider']; ?></td>
                            <td>
                              <div class="demo-inline-spacing">
                              <a href="delete.php?sid=<?php echo $data_show['sid']; ?>" class="btn btn-icon btn-danger btn-sm">
                                <span class="ti ti-trash"></span>
                              </a>
                              <a href="detail.php?sid=<?php echo $data_show['sid']; ?>" class="btn btn-icon btn-primary btn-sm">
                                <span class="ti ti-eye"></span>
                              </a>

                              <a href="edit.php?sid=<?php echo $data_show['sid']; ?>" class="btn btn-icon btn-info btn-sm">
                                <span class="ti ti-edit"></span>
                              </a>
                            </div>

                            </td>
                        </tr>
                        <?php
              }
              ?>
                      </tbody>

                      <tfoot>
                        <tr>
                          <th>ID</th>
                        <th>Kategori</th>
                        <th>Layanan</th>
                        <th>Min. Order</th>
                        <th>Max Order</th>
                        <th>Harga</th>
                        <th>Harga Service</th>
                        <th>PID</th>
                        <th>SID</th>
                        <th>API</th>
                        <th>Aksi</th>
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
    <script>
      $(document).ready(function () {
    $('#datasosmed').DataTable();
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