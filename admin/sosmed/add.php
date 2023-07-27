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
    $checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
    $datadb_provider = mysqli_fetch_assoc($checkdb_provider);

    $check_highest_sid = mysqli_query($db, "SELECT * FROM `services` ORDER BY `sid` DESC LIMIT 1");
      $highest_sid = mysqli_fetch_array($check_highest_sid);
        $post_sid = $highest_sid['sid'] + 1;

    if (isset($_POST['add'])) {
      $post_cat = htmlspecialchars($_POST['category']);
      $post_service = htmlspecialchars($_POST['service']);
      $post_note = mysqli_real_escape_string($db, htmlspecialchars($_POST['note']));
      $post_min = htmlspecialchars($_POST['min']);
      $post_max = htmlspecialchars($_POST['max']);
      $post_price = htmlspecialchars($_POST['price']);
      $post_price_provider = htmlspecialchars($_POST['price_provider']);
      $post_pid = htmlspecialchars($_POST['pid']);
      $post_provider = htmlspecialchars($_POST['provider']);

      $checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
      $datadb_service = mysqli_fetch_assoc($checkdb_service);
      $checkdb_pid = mysqli_query($db, "SELECT * FROM services WHERE pid = '$post_pid'");
      $pid = $datadb_service['pid'];
      
      $cat = mysqli_query($db, "SELECT * FROM service_cat WHERE name = '$post_cat'");
      $data_cat = mysqli_fetch_assoc($cat);
      $type = $data_cat['type'];
      if ( empty($post_cat) || empty($post_service) || empty($post_min) || empty($post_max) || empty($post_price)) {
        $msg_type = "error";
        $msg_content = "Please Fill In All Inputs.";
      } else if($post_provider != "MANUAL" AND (empty($post_price_provider) || empty($post_pid))){
        $msg_type = "error";
        $msg_content = "Please Fill In All Inputs.";
      } else if (mysqli_num_rows($checkdb_service) > 0) {
        $msg_type = "error";
        $msg_content = "Service ID $post_sid Sudah Terdaftar.";
      } else {
          $post_pid = !empty($post_pid) ? "$post_pid" : "0";
          $post_price_provider = !empty($post_price_provider) ? "$post_price_provider" : "0";

          $insert_service = mysqli_query($db, "INSERT INTO history_update (sid, service, rate, status, date) VALUES ('$post_sid', '$post_service', '$post_price', 'New service', '$date')");
          $insert_service = mysqli_query($db, "INSERT INTO services (sid, category, service, note, min, max, price, price_provider, status, pid, provider) VALUES ('$post_sid', '$post_cat', '$post_service', '$post_note', '$post_min', '$post_max', '$post_price', '$post_price_provider', 'Active', '$post_pid', '$post_provider')");
          if ($insert_service == TRUE) {
            $msg_type = "success";
            $msg_content = "Berhasil ditambahkan";
          } else {
            $msg_type = "error";
            $msg_content = "A System Error Occurred. ".mysqli_error($db);
          }
      }
    }
  $title = "Add Services";
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

    <title>Tambah Layanan Sosmed | <?php echo $data_settings['web_name']; ?></title>

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
                      <h5 class="mb-0">Tambah Layanan</h5>
                      
                    </div>
                    <div class="card-body">
                      <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Service ID</label>
                              <input type="number" name="sid" value="<?php echo $post_sid; ?>" disabled class="form-control" placeholder="John" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">ID API Layanan</label>
                              <input type="number" name="pid" step="0.0001" class="form-control" placeholder="53212" />
                            </div>
                          </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Nama Layanan</label>
                              <input type="text" name="service" class="form-control" placeholder="Instagram Follower" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">Kategori</label>
                              <select class="form-control" name="category">
                                <?php
                                $check_cat = mysqli_query($db, "SELECT * FROM service_cat WHERE status = 'Active' ORDER BY name ASC");
                                while ($data_cat = mysqli_fetch_assoc($check_cat)) {
                                ?>
                                <option value="<?php echo $data_cat['name']; ?>"><?php echo $data_cat['name']; ?></option>
                                <?php
                                }
                                ?>
                              </select>
                            </div>
                        </div>

                        

                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Min Order</label>
                              <input type="number" class="form-control" name="min"  placeholder="100" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="formtabs-last-name">Max Order</label>
                              <input type="number" name="max" class="form-control" placeholder="1000" />
                            </div>
                          </div>

                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Harga/1K</label>
                              <input type="number" name="price" class="form-control"step="0.0001" value="<?php echo $datadb_service['price']; ?>" />
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Harga API/1K</label>
                              <input type="text" name="price_provider" class="form-control" placeholder="Rp.100.000" />
                            </div>
                          </div>

                          <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">API Layanan</label>
                              <select class="form-control" id="name" name="provider">
                                        <option value="<?php
                                        $checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
                                        $datadb_provider = mysqli_fetch_assoc($checkdb_provider);
                                        echo $datadb_provider['code']; ?>">Selected [<?php echo $datadb_provider['code']; ?>]</option>
                                        <?php
                                        while ($datadb_providerLoop = mysqli_fetch_assoc($checkdb_provider)) {
                                        ?>
                                        <option value="<?php echo $datadb_providerLoop['code']; ?>"><?php echo $datadb_providerLoop['code']; ?></option>
                                        <?php
                                        }
                                        ?>
                              </select>
                            </div>
                            
                          </div>

                          <div class="mb-3">
                          <label class="form-label" for="basic-default-message">Catatan</label>
                          <textarea class="form-control" 
                            name="note"
                            class="form-control"
                            placeholder="Catatan informasi layanan sosmed" 
                            ></textarea>
                        </div>
                        
                        <div class="pt-4">
                          <button type="submit" name="add" class="btn btn-primary">Tambah</button>
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
  header("Location: ".$cfg_baseurl);
}
?>