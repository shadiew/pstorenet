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
    if (isset($_GET['sid'])) {
      $post_sid = $_GET['sid'];
      $checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
      $datadb_service = mysqli_fetch_assoc($checkdb_service);
      if (mysqli_num_rows($checkdb_service) == 0) {
        header("Location: ".$cfg_baseurl."admin/service/");
      } else {
        if (isset($_POST['edit'])) {
          $post_cat = htmlspecialchars($_POST['category']);
          $post_service = htmlspecialchars($_POST['service']);
          $post_note = mysqli_real_escape_string($db, htmlspecialchars($_POST['note']));
          $post_min = htmlspecialchars($_POST['min']);
          $post_max = htmlspecialchars($_POST['max']);
          $post_price = htmlspecialchars($_POST['price']);
          $post_price_provider = htmlspecialchars($_POST['price_provider']);
          $post_pid = htmlspecialchars($_POST['pid']);
          $post_provider = htmlspecialchars($_POST['provider']);
          $post_status = htmlspecialchars($_POST['status']);
          $post_update = htmlspecialchars($_POST['update']);

          if(empty($post_provider)){
            $post_provider = "MANUAL";
          }
          if ( empty($post_cat) || empty($post_service) || empty($post_min) || empty($post_max) || empty($post_price)) {
            $msg_type = "error";
            $msg_content = "Please Fill In All Inputs.";
          } else if($post_provider != "MANUAL" AND (empty($post_price_provider) || empty($post_pid))){
            $msg_type = "error";
            $msg_content = "Please Fill In All Inputs.";
          } else if ($post_status != "Active" AND $post_status != "Not active") {
            $msg_type = "error";
            $msg_content = "Input Error Occurred.";
          } else {
            $post_pid = !empty($post_pid) ? "$post_pid" : "0";
            $post_price_provider = !empty($post_price_provider) ? "$post_price_provider" : "0";

            $update_service = mysqli_query($db, "UPDATE services SET category = '$post_cat', service = '$post_service', note = '$post_note', min = '$post_min', max = '$post_max', price = '$post_price', price_provider = '$post_price_provider', status = '$post_status', pid = '$post_pid', provider = '$post_provider' WHERE sid = '$post_sid'");
            if ($update_service == TRUE) {
              $msg_type = "success";
              $msg_content = "<b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_service<br /><b>Category:</b> $post_cat<br /><b>Note:</b> $post_note<br /><b>Min:</b> $post_min<br /><b>Max:</b> $post_max<br /><b>Price/1000:</b> Rp.$post_price<br /><b>Price Provider/1000:</b> Rp.$post_price_provider<br /><b>Provider ID:</b> $post_pid<br /><b>Status:</b> $post_status";
            } else {
              $msg_type = "error";
              $msg_content = "A System Error Occurred.";
            }
          }
        }
        $checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
        $datadb_service = mysqli_fetch_assoc($checkdb_service);
        $title = "Change Service";
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
  data-assets-path="../../assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Edit Layanan Sosmed | <?php echo $data_settings['web_name']; ?></title>

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
              

              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Ubah Layanan Layanan</h5>
                      
                    </div>
                    <div class="card-body">
                      <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Service ID</label>
                              <input type="number" value="<?php echo $datadb_service['sid']; ?>" readonly class="form-control" placeholder="John" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">ID API</label>
                              <input type="number" name="pid" class="form-control" placeholder="53212" value="<?php echo $datadb_service['pid']; ?>" />
                            </div>
                          </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Nama Layanan</label>
                              <input type="text" name="service" class="form-control" placeholder="Instagram Follower" value="<?php echo $datadb_service['service']; ?>" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">Kategori</label>
                              <select class="form-control" name="category">
                                <option value="<?php echo $datadb_service['category']; ?>"><?php echo $datadb_service['category']; ?> (Selected)</option>
                                <?php
                                $check_cat = mysqli_query($db, "SELECT * FROM service_cat ORDER BY name ASC");
                                while ($data_cat = mysqli_fetch_assoc($check_cat)) {
                                ?>
                                <option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
                                <?php
                                }
                                ?>
                              </select>
                            </div>
                        </div>

                        

                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Min Order</label>
                              <input type="number" class="form-control" name="min" value="<?php echo $datadb_service['min']; ?>" placeholder="100" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">Max Order</label>
                              <input type="number" name="max" class="form-control" value="<?php echo $datadb_service['max']; ?>" placeholder="1000" />
                            </div>
                          </div>

                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Harga/1K</label>
                              <input type="number" name="price" class="form-control"step="0.0001" value="<?php echo $datadb_service['price']; ?>" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Harga API/1K</label>
                              <input type="text" name="price_provider" class="form-control" value="<?php echo $datadb_service['price_provider']; ?>" placeholder="Rp.100.000" />
                            </div>
                          </div>

                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">API Layanan</label>
                              <select class="form-control" id="name" name="provider">
                                      <option value="<?php
                                      $checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
                                      $datadb_provider = mysqli_fetch_assoc($checkdb_provider);
                                      echo $datadb_service['provider']; ?>">Selected [<?php echo $datadb_service['provider']; ?>]</option>
                                      <?php
                                      $checkdb_providerLoop = mysqli_query($db, "SELECT * FROM provider");
                                      while ($datadb_providerLoop = mysqli_fetch_assoc($checkdb_providerLoop)) {
                                      ?>
                                      <option value="<?php echo $datadb_providerLoop['code']; ?>"><?php echo $datadb_providerLoop['code']; ?></option>
                                      <?php
                                      }
                                      ?>
                             </select>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">Status</label>
                              <select class="form-control" name="status">
                                <option value="<?php echo $datadb_service['status']; ?>"><?php echo $datadb_service['status']; ?> (Selected)</option>
                                <option value="Active">Aktif</option>
                                <option value="Not active">Tidak Aktif</option>
                              </select>
                            </div>
                          </div>

                          <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Catatan</label>
                          <textarea class="form-control" 
                            name="note"
                            class="form-control"
                            placeholder="Catatan informasi layanan sosmed" 
                            ><?php echo $datadb_service['note']; ?></textarea>
                        </div>
                        
                        <div class="pt-4">
                          <button type="submit" name="edit" class="btn btn-primary">Update</button>
                          <a href="../sosmed" class="btn btn-info">Kembali</a>
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
        
      }
    } else {
      header("Location: ".$cfg_baseurl."/admin/sosmed/");
    }
  }
} else {
  header("Location: ".$cfg_baseurl);
}
?>