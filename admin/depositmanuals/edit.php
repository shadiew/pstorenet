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
		if (isset($_GET['id'])) {
			$post_id = $_GET['id'];
			$checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_id'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				header("Location: ".$cfg_baseurl."/admin/depo/");
			} else {
				if (isset($_POST['edit'])) {
					$post_name = htmlspecialchars($_POST['name']);
          $post_code = htmlspecialchars($_POST['code']);
					$post_data = htmlspecialchars($_POST['data']);
					$post_note = htmlspecialchars($_POST['note']);
					$post_rate = htmlspecialchars($_POST['rate']);
					$post_kategori = htmlspecialchars($_POST['kategori']);
					$post_Active = htmlspecialchars($_POST['Active']);
					$post_id = htmlspecialchars($_POST['id']);
					
					if (empty($post_name) || empty($post_data) || empty($post_note)|| empty($post_rate)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else if ($post_name == null) {
						$msg_type = "error";
						$msg_content = "Input Error Occurred.";
					} else {
						$update_service = mysqli_query($db, "UPDATE deposit_method SET name = '$post_name', code = '$post_code', data = '$post_data', note = '$post_note', rate = '0', Active = '$post_Active' where id='$post_id'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "Deposit Method Changed.";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.";
						}
					}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_id'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				$page = 'depositmanuals';
				include("../../lib/header_admin.php");
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

    <title>Edit Pembayaran Manual | <?php echo $data_settings['web_name']; ?></title>

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
                      <h5 class="mb-0">Tambah Pembayaran Manual</h5>
                      
                    </div>
                    <div class="card-body">
                      <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Nama BANK</label>
                              <input type="text" name="name" class="form-control" placeholder="BANK Central Asia" value="<?php echo $datadb_service['name']; ?>" autofocus/>
                            </div>
                            <div class="col-md-12">
                              <label class="form-label">Kode</label>
                              <input type="text" name="code" class="form-control" placeholder="BNI" value="<?php echo $datadb_service['code']; ?>"/>
                            </div>
                            <div class="col-md-12">
                              <label class="form-label">No./Rekening</label>
                              <input type="text" name="data" class="form-control" placeholder="082221584446" value="<?php echo $datadb_service['data']; ?>"/>
                            </div>
                            <div class="col-md-12">
                              <label class="form-label">Pemilik Rekening</label>
                              <input type="text" name="note" class="form-control" placeholder="Sadiwantoro" value="<?php echo $datadb_service['note']; ?>"/>
                            </div>
                            
                            <div class="col-md-12">
                              <label class="form-label">Status</label>
                              <select class="form-control" id="Active" name="Active">
                                <option value="<?php echo $datadb_service['Active']; ?>">Selected [<?php echo $datadb_service['Active']; ?>]</option>
                                <option value="YES">Aktif</option>
                                <option value="NO">Tidak Aktif</option>
                              </select>
                            </div>
                          </div>
                        <div class="pt-4">
                          <button type="submit" name="edit" class="btn btn-primary">Tambah</button>
                          <a href="../depositmanuals" class="btn btn-warning">Kembali</a>
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
                       Data yang dimasukan dapat diubah atau dihapus, silahkan sesuaikan data diisi dengan kebutuhan manajamen anda!
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
			header("Location: ".$cfg_baseurl."/admin/depositmanuals/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>