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
		if (isset($_GET['poid'])) {
			$post_poid = $_GET['poid'];
			$checkdb_order = mysqli_query($db, "SELECT * FROM orders WHERE poid = '$post_poid'");
			$datadb_order = mysqli_fetch_assoc($checkdb_order);
			if (mysqli_num_rows($checkdb_order) == 0) {
				header("Location: ".$cfg_baseurl."/admin/order/");
			} else if ($datadb_order['status'] == "Canceled" || $datadb_order['status'] == "Error" || $datadb_order['status'] == "Partial") {
				header("Location: ".$cfg_baseurl."/admin/order/");
			} else {
				if (isset($_POST['edit'])) {
					$post_status = $_POST['status'];
					if ($post_status == "") {
						$msg_type = "error";
						$msg_content = "Input Error Occurred.";
					} else {
						$update_order = mysqli_query($db, "UPDATE orders SET status = '$post_status' WHERE poid = '$post_poid'");
						if ($update_order == TRUE) {
							$msg_type = "success";
							$msg_content = "Pesanan berhasil diupdate";
						} else {
							$msg_type = "error";
							$msg_content = "Terjadi Kesalahan Input.";
						}
					}
				}
				$checkdb_order = mysqli_query($db, "SELECT * FROM orders WHERE poid = '$post_poid'");
				$datadb_order = mysqli_fetch_assoc($checkdb_order);
				$title = "Change Order";
				include("../../lib/header_admin.php");
                $page = 'pembelian';
                $pages = 'transaksi';
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

    <title>Detail Pesanan | <?php echo $data_settings['web_name']; ?></title>

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
              

<div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
              <div class="card invoice-preview-card">
                <div class="card-body">
                  <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                    <div class="mb-xl-0 mb-4">
                      <div class="d-flex svg-illustration mb-4 gap-2 align-items-center">
                        <svg width="32" height="22" viewbox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0"/>
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616"/>
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0"/>
                        </svg>
                        <span class="app-brand-text fw-bold fs-4"><?php echo $data_settings['web_name']; ?></span>
                      </div>
                      <p class="mb-2">Provider: <?php echo $datadb_order['provider']; ?></p>
                      <p class="mb-2">Harga Provider: <?php echo rupiah($datadb_order['price_provider']); ?></p>
                      <p class="mb-0">Status: <span class="badge rounded-pill bg-label-warning"><?php echo $datadb_order['status']; ?></span></p>
                    </div>
                    <div>
                      <h4 class="fw-medium mb-2">INVOICE #<?php echo $datadb_order['poid']; ?></h4>
                      <div class="mb-2 pt-1">
                        <span>Date :</span>
                        <span class="fw-medium"><?php echo $datadb_order['date']; ?></span>
                      </div>
                      <div class="pt-1">
                        <span>Time :</span>
                        <span class="fw-medium"><?php echo $datadb_order['time']; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
               
                
                <div class="table-responsive border-top">
                  <table class="table m-0">
                  <thead>
                  <tr>
                    <th>Item</th>
                    <th>Target/Username/link</th>
                    <th>Jumlah</th>
                    <th>Remains</th>
                    <th>Price</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td class="text-nowrap"><?php echo $datadb_order['service']; ?></td>
                    <td class="text-nowrap"><?php echo $datadb_order['link']; ?></td>
                    <td><?php echo $datadb_order['quantity']; ?></td>
                    <td><?php echo $datadb_order['remains']; ?></td>
                    <td><?php echo rupiah($datadb_order['price']); ?></td>
                  </tr>
                  
                  <tr>
                    <td colspan="3" class="align-top px-4 py-4">
                      
                    </td>
                    <td class="text-end pe-3 py-4">
                      <p class="mb-2 pt-3">Harga Jual:</p>
                      <p class="mb-2">Harga Server:</p>
                      <p class="mb-2">Tax:</p>
                    </td>
                    <td class="ps-2 py-4">
                      <p class="fw-medium mb-2 pt-3"><?php echo rupiah($datadb_order['price']); ?></p>
                      <p class="fw-medium mb-2"><?php echo rupiah($datadb_order['price_provider']); ?></p>
                      <p class="fw-medium mb-2">Rp.0</p>
                    </td>
                  </tr>
                  </tbody>
                  </table>
                </div>
                <div class="card-body mx-3">
                  <div class="row">
                    <div class="col-12">
                      <span class="fw-medium">Note:</span>
                      <span>Ini hanya berlaku untuk admin saja, user tidak dapat melihat data lengkap transaksi ini.!</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Invoice -->
            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
              <div class="card">
                <div class="card-body">
                  <a href="../pembelian" class="btn btn-primary d-grid w-100 mb-2">
                  <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-send ti-xs me-2"></i>Kembali</span>
                  </a>
                  
                </div>
              </div>
            </div>
            <!-- /Invoice Actions -->
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
			header("Location: ".$cfg_baseurl."/admin/pembelian/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>