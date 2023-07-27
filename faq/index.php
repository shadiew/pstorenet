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
  
  $title = "FAQ";
  include("../lib/header.php");
  $page = 'faq';
  ?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>FAQ  | <?php echo $data_settings['web_name']; ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/typeahead-js/typeahead.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-faq.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php
              include("../lib/sidebar_user.php");
            ?>
        
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
<?php
              include("../lib/navbar_user.php");
            ?>
          

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="faq-header d-flex flex-column justify-content-center align-items-center rounded">
                <h3 class="text-center">Hallo, Ada yang bisa saya bantu?</h3>
                
              </div>

              <div class="row mt-4">
                <!-- Navigation -->
                <div class="col-lg-3 col-md-4 col-12 mb-md-0 mb-3">
                  <div class="d-flex justify-content-between flex-column mb-2 mb-md-0">
                    <ul class="nav nav-align-left nav-pills flex-column">
                      <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#payment">
                          <i class="ti ti-credit-card me-1 ti-sm"></i>
                          <span class="align-middle fw-semibold">Payment</span>
                        </button>
                      </li>
                      <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#delivery">
                          <i class="ti ti-briefcase me-1 ti-sm"></i>
                          <span class="align-middle fw-semibold">Delivery</span>
                        </button>
                      </li>
                      <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancellation">
                          <i class="ti ti-rotate-clockwise-2 me-1 ti-sm"></i>
                          <span class="align-middle fw-semibold">Cancellation & Return</span>
                        </button>
                      </li>
                      <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#orders">
                          <i class="ti ti-box me-1 ti-sm"></i>
                          <span class="align-middle fw-semibold">My Orders</span>
                        </button>
                      </li>
                      <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#product">
                          <i class="ti ti-settings me-1 ti-sm"></i>
                          <span class="align-middle fw-semibold">Product & Services</span>
                        </button>
                      </li>
                    </ul>
                    <div class="d-none d-md-block">
                      <div class="mt-4">
                        <img
                          src="../assets/img/illustrations/girl-sitting-with-laptop.png"
                          class="img-fluid"
                          width="270"
                          alt="FAQ Image" />
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /Navigation -->

                <!-- FAQ's -->
                <div class="col-lg-9 col-md-8 col-12">
                  <div class="tab-content py-0">
                    <div class="tab-pane fade show active" id="payment" role="tabpanel">
                      <div class="d-flex mb-3 gap-3">
                        <div>
                          <span class="badge bg-label-primary rounded-2 p-2">
                            <i class="ti ti-credit-card ti-lg"></i>
                          </span>
                        </div>
                        <div>
                          <h4 class="mb-0">
                            <span class="align-middle">Payment</span>
                          </h4>
                          <small>Get help with payment</small>
                        </div>
                      </div>
                      <div id="accordionPayment" class="accordion">
                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button"
                              type="button"
                              data-bs-toggle="collapse"
                              aria-expanded="true"
                              data-bs-target="#accordionPayment-1"
                              aria-controls="accordionPayment-1">
                              Bagaimana saya menambahkan saldo akun?
                            </button>
                          </h2>

                          <div id="accordionPayment-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                              Silahkan klik deposit instan, dan pilih metode pembayaran yang anda sukai. Isi nomimal yang anda ingikan, klik deposit dan bayarkan tagihan sesuai yang tertera
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionPayment-2"
                              aria-controls="accordionPayment-2">
                              Berapa lama saldo akun masuk?
                            </button>
                          </h2>
                          <div id="accordionPayment-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              Kami menggunakan sistem otomatis dari berbagai macam jenis bank, dan semua sudah cepat. tanpa harus menunggu admin, sistem akan otomatis masuk kesaldo akun anda!
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionPayment-3"
                              aria-controls="accordionPayment-3">
                              Bagaimana jika saya tidak dapat membayarkan?
                            </button>
                          </h2>
                          <div id="accordionPayment-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              Jika tagihan tidak dibayarkan, maka akan otomatis ditutup oleh sistem, jika anda bingung membayarkan, silahkan hubungi admin
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionPayment-4"
                              aria-controls="accordionPayment-4">
                              Bagaimana jika deposit expired?
                            </button>
                          </h2>
                          <div id="accordionPayment-4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              Agar tidak expired, silahkan di bayarkan segera. jika expired silahkan buat deposit akun baru
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionPayment-5"
                              aria-controls="accordionPayment-5">
                              Does my subscription automatically renew?
                            </button>
                          </h2>
                          <div id="accordionPayment-5" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              No, This is not subscription based item.Pastry pudding cookie toffee bonbon jujubes
                              jujubes powder topping. Jelly beans gummi bears sweet roll bonbon muffin liquorice. Wafer
                              lollipop sesame snaps.
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="delivery" role="tabpanel">
                      <div class="d-flex mb-3 gap-3">
                        <div>
                          <span class="badge bg-label-primary rounded-2 p-2">
                            <i class="ti ti-briefcase ti-lg"></i>
                          </span>
                        </div>
                        <div>
                          <h4 class="mb-0">
                            <span class="align-middle">Delivery</span>
                          </h4>
                          <small>Lorem ipsum, dolor sit amet.</small>
                        </div>
                      </div>
                      <div id="accordionDelivery" class="accordion">
                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button"
                              type="button"
                              data-bs-toggle="collapse"
                              aria-expanded="true"
                              data-bs-target="#accordionDelivery-1"
                              aria-controls="accordionDelivery-1">
                              How would you ship my order?
                            </button>
                          </h2>

                          <div id="accordionDelivery-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                              For large products, we deliver your product via a third party logistics company offering
                              you the “room of choice” scheduled delivery service. For small products, we offer free
                              parcel delivery.
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionDelivery-2"
                              aria-controls="accordionDelivery-2">
                              What is the delivery cost of my order?
                            </button>
                          </h2>
                          <div id="accordionDelivery-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              The cost of scheduled delivery is $69 or $99 per order, depending on the destination
                              postal code. The parcel delivery is free.
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionDelivery-4"
                              aria-controls="accordionDelivery-4">
                              What to do if my product arrives damaged?
                            </button>
                          </h2>
                          <div id="accordionDelivery-4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              We will promptly replace any product that is damaged in transit. Just contact our
                              <a href="javascript:void(0);">support team</a>, to notify us of the situation within 48
                              hours of product arrival.
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="cancellation" role="tabpanel">
                      <div class="d-flex mb-3 gap-3">
                        <div>
                          <span class="badge bg-label-primary rounded-2 p-2">
                            <i class="ti ti-rotate-clockwise-2 ti-lg"></i>
                          </span>
                        </div>
                        <div>
                          <h4 class="mb-0"><span class="align-middle">Cancellation & Return</span></h4>
                          <small>Lorem ipsum, dolor sit amet.</small>
                        </div>
                      </div>
                      <div id="accordionCancellation" class="accordion">
                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button"
                              type="button"
                              data-bs-toggle="collapse"
                              aria-expanded="true"
                              data-bs-target="#accordionCancellation-1"
                              aria-controls="accordionCancellation-1">
                              Can I cancel my order?
                            </button>
                          </h2>

                          <div id="accordionCancellation-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                              <p>
                                Scheduled delivery orders can be cancelled 72 hours prior to your selected delivery date
                                for full refund.
                              </p>
                              <p class="mb-0">
                                Parcel delivery orders cannot be cancelled, however a free return label can be provided
                                upon request.
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionCancellation-2"
                              aria-controls="accordionCancellation-2">
                              Can I return my product?
                            </button>
                          </h2>
                          <div id="accordionCancellation-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              You can return your product within 15 days of delivery, by contacting our
                              <a href="javascript:void(0);">support team</a>, All merchandise returned must be in the
                              original packaging with all original items.
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              aria-controls="accordionCancellation-3"
                              data-bs-target="#accordionCancellation-3">
                              Where can I view status of return?
                            </button>
                          </h2>
                          <div id="accordionCancellation-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              <p>Locate the item from Your <a href="javascript:void(0);">Orders</a></p>
                              <p class="mb-0">Select <strong>Return/Refund</strong> status</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                      <div class="d-flex mb-3 gap-3">
                        <div>
                          <span class="badge bg-label-primary rounded-2 p-2">
                            <i class="ti ti-box ti-lg"></i>
                          </span>
                        </div>
                        <div>
                          <h4 class="mb-0">
                            <span class="align-middle">My Orders</span>
                          </h4>
                          <small>Lorem ipsum, dolor sit amet.</small>
                        </div>
                      </div>
                      <div id="accordionOrders" class="accordion">
                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button"
                              type="button"
                              data-bs-toggle="collapse"
                              aria-expanded="true"
                              data-bs-target="#accordionOrders-1"
                              aria-controls="accordionOrders-1">
                              Has my order been successful?
                            </button>
                          </h2>

                          <div id="accordionOrders-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                              <p>
                                All successful order transactions will receive an order confirmation email once the
                                order has been processed. If you have not received your order confirmation email within
                                24 hours, check your junk email or spam folder.
                              </p>
                              <p class="mb-0">
                                Alternatively, log in to your account to check your order summary. If you do not have a
                                account, you can contact our Customer Care Team on <strong>1-000-000-000</strong>.
                              </p>
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionOrders-2"
                              aria-controls="accordionOrders-2">
                              My Promotion Code is not working, what can I do?
                            </button>
                          </h2>
                          <div id="accordionOrders-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              If you are having issues with a promotion code, please contact us at
                              <strong>1 000 000 000</strong> for assistance.
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionOrders-3"
                              aria-controls="accordionOrders-3">
                              How do I track my Orders?
                            </button>
                          </h2>
                          <div id="accordionOrders-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              <p>
                                If you have an account just sign into your account from
                                <a href="javascript:void(0);">here</a> and select <strong>“My Orders”</strong>.
                              </p>
                              <p class="mb-0">
                                If you have a a guest account track your order from
                                <a href="javascript:void(0);">here</a> using the order number and the email address.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="product" role="tabpanel">
                      <div class="d-flex mb-3 gap-3">
                        <div>
                          <span class="badge bg-label-primary rounded-2 p-2">
                            <i class="ti ti-camera ti-lg"></i>
                          </span>
                        </div>
                        <div>
                          <h4 class="mb-0">
                            <span class="align-middle">Product & Services</span>
                          </h4>
                          <small>Lorem ipsum, dolor sit amet.</small>
                        </div>
                      </div>
                      <div id="accordionProduct" class="accordion">
                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button"
                              type="button"
                              data-bs-toggle="collapse"
                              aria-expanded="true"
                              data-bs-target="#accordionProduct-1"
                              aria-controls="accordionProduct-1">
                              Will I be notified once my order has shipped?
                            </button>
                          </h2>

                          <div id="accordionProduct-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                              Yes, We will send you an email once your order has been shipped. This email will contain
                              tracking and order information.
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionProduct-2"
                              aria-controls="accordionProduct-2">
                              Where can I find warranty information?
                            </button>
                          </h2>
                          <div id="accordionProduct-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              We are committed to quality products. For information on warranty period and warranty
                              services, visit our Warranty section <a href="javascript:void(0);">here</a>.
                            </div>
                          </div>
                        </div>

                        <div class="card accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#accordionProduct-3"
                              aria-controls="accordionProduct-3">
                              How can I purchase additional warranty coverage?
                            </button>
                          </h2>
                          <div id="accordionProduct-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                              For the peace of your mind, we offer extended warranty plans that add additional year(s)
                              of protection to the standard manufacturer’s warranty provided by us. To purchase or find
                              out more about the extended warranty program, visit Extended Warranty section
                              <a href="javascript:void(0);">here</a>.
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /FAQ's -->
              </div>

              <!-- Contact -->
              <div class="row mt-5">
                <div class="col-12 text-center mb-4">
                  <div class="badge bg-label-primary">Question?</div>
                  <h4 class="my-2">You still have a question?</h4>
                  <p>If you can't find question in our FAQ, you can contact us. We'll answer you shortly!</p>
                </div>
              </div>
              <div class="row text-center justify-content-center gap-sm-0 gap-3">
                <div class="col-sm-6">
                  <div class="py-3 rounded bg-faq-section text-center">
                    <span class="badge bg-label-primary my-3 rounded-2 p-2">
                      <i class="ti ti-phone ti-md"></i>
                    </span>
                    <h4 class="mb-2"><a class="text-body" href="tel:+(810)25482568">+ (810) 2548 2568</a></h4>
                    <p>We are always happy to help</p>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="py-3 rounded bg-faq-section text-center">
                    <span class="badge bg-label-primary my-3 rounded-2 p-2">
                      <i class="ti ti-mail ti-md"></i>
                    </span>
                    <h4 class="mb-2"><a class="text-body" href="mailto:help@help.com">help@help.com</a></h4>
                    <p>Best way to get a quick answer</p>
                  </div>
                </div>
              </div>
              <!-- /Contact -->
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php
              include("../lib/footer_user.php");
            ?>
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
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
  </body>
</html>
<?php
  
} else {
  header("Location: ".$cfg_baseurl);
}