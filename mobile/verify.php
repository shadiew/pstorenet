<?php
    session_start();
    require_once('../lib/mainconfig.php');
    date_default_timezone_set("Asia/Jakarta");

    if (isset($_SESSION['user'])) {
        $customer_name = $_SESSION['user']['name'];
        $customer_email = $_SESSION['user']['email'];
        $customer_phone = $_SESSION['user']['nohp'];
        $customer_id = $_SESSION['user']['id'];
    }

    function tanggal_indo($tanggal, $cetak_hari = false)
    {
        $hari = array(
            1 =>    'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $split_jam = explode(' ', $tanggal);
        $split       = explode('-', $split_jam[0]);
        $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo . ' ' . $split_jam[1] . ' WIB';
        }
        return $tgl_indo . ' ' . $split_jam[1] . ' WIB';
    }

    // MAKE PAYMENT TO TRIPAY

    if (isset($_POST['deposit'])) {
        $amount = $_POST['amount'];
        $method = $_POST['method'];
    }

    // 1. Create Signature
    $privateKey = $tripay_private_key;
    $merchantCode = $tripay_merchant_code;
    $apiKey = $tripay_api_key;

    // Invoice Code
    $checkDeposit = mysqli_query($db, "SELECT * FROM deposits ORDER BY ID DESC LIMIT 1");
    $a = mysqli_fetch_assoc($checkDeposit);
    $depositId = $a['id'] + 1;
    $merchantRef = 'DPO' . '-' . $depositId . date("ymd"); //DPO-9210313
    $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

    // 2. Collect The Data
    $data = [
        'method'            => $method,
        'merchant_ref'      => $merchantRef,
        'amount'            => $amount,
        'customer_name'     => $customer_name,
        'customer_email'    => $customer_email,
        'customer_phone'    => $customer_phone,
        'order_items'       => [
            [
                'sku'       => 'Deposit',
                'name'      => 'Deposit Rp. ' . rupiah($amount),
                'price'     => $amount,
                'quantity'  => 1
            ]
        ],
        'callback_url'      => $cfg_baseurl . '/tripay/callback.php',
        'return_url'        => $cfg_baseurl . '/tripay/redirect.php',
        'expired_time'      => (time() + (24 * 60 * 60)), // 24 jam
        'signature'         => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey)
    ];


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_FRESH_CONNECT     => true,
        CURLOPT_URL               => "https://tripay.co.id/api/transaction/create",
        CURLOPT_RETURNTRANSFER    => true,
        CURLOPT_HEADER            => false,
        CURLOPT_HTTPHEADER        => array(
            "Authorization: Bearer " . $apiKey
        ),
        CURLOPT_FAILONERROR       => false,
        CURLOPT_POST              => true,
        CURLOPT_POSTFIELDS        => http_build_query($data)
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $decodedResponse = json_decode($response, true);
    $payment_gateway_reference = $decodedResponse["data"]["reference"];
    $instructions = json_encode($decodedResponse["data"]["instructions"]);
    $checkout_url = $decodedResponse["data"]["checkout_url"];
    $qr_string = $decodedResponse["data"]["qr_string"];
    $qr_url = $decodedResponse["data"]["qr_url"];
    $expired_time = $decodedResponse["data"]["expired_time"];
    $pay_code = $decodedResponse["data"]["pay_code"];
    $total_payment = $decodedResponse["data"]["amount"];

    // var_dump($decodedResponse);
    // die();

    // 3. If Success Redirect To Tripay Checkout URL And Input to Database Deposits
    if (!empty($err)) {
        // Input To Database

        $insert_deposit = mysqli_query(
            $db,
            "INSERT INTO 
        deposits (
            invoice_number, payment_gateway_reference, code, user, method, note, quantity, balance, status, instructions, checkout_url, qr_string, qr_url, expired_time, created_at) 
        VALUES (
            '$merchantRef', '$payment_gateway_reference', '$method', '$customer_id', 'TRIPAY', '', '1', '$amount', 'Error','$instructions','$checkout_url', '$qr_string', '$qr_url', '$expired_time', NOW())"
        );
        echo $err;
        echo "<br><b>Please Contact Admin!<b><br><a href='" . $cfg_baseurl . "'>Go To Home</a>";
    } else {
        // Input To Database
        $insert_deposit = mysqli_query(
            $db,
            "INSERT INTO 
        deposits (
            invoice_number, payment_gateway_reference, code, user, method, note, quantity, balance, status, instructions, checkout_url, qr_string, qr_url, expired_time, created_at) 
        VALUES (
            '$merchantRef', '$payment_gateway_reference', '$method', '$customer_id', 'TRIPAY', '', '1', '$amount', 'Pending','$instructions','$checkout_url', '$qr_string', '$qr_url', '$expired_time', NOW())"
        );
        $checkoutUrl = $decodedResponse["data"]["checkout_url"];
        // var_dump($response);
        // echo "<br>";
        // var_dump($decodedResponse);
        // header("Location: $checkoutUrl");
        // exit();

    ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">

    <meta name="theme-color" content="#ffffff">
    <title>Deposit Instant</title>
    
    <!-- CSS Libraries-->
    <!-- bootstrap v4.6.0 -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!--
        theiconof v3.0
        https://www.theiconof.com/search
     -->
    <link rel="stylesheet" href="assets/css/icons.css">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="assets/css/remixicon.css">
    <!-- Swiper 6.4.11 -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
    <!-- Owl Carousel v2.3.4 -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- normalize.css v8.0.1 -->
    <link rel="stylesheet" href="assets/css/normalize.css">

    <!-- manifest meta -->
    <link rel="manifest" href="_manifest.json" />
    
    <style>
#snackbar {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  left: 50%;
  bottom: 30px;
  font-size: 17px;
}

#snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;} 
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;} 
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
</style>
</head>


<body>

    <!-- Start em_loading -->
    <section class="em_loading" id="loaderPage">
        <div class="spinner_flash"></div>
    </section>
    <!-- End. em_loading -->

    <div id="wrapper">
        <div id="content">
            <!-- Start main_haeder -->
            <header class="main_haeder header-sticky multi_item">
                <div class="em_side_right">
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../mobile/riwayatdeposit">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        Invoice Details
                    </span>
                </div>
                <div class="em_side_right">
                    
                </div>
            </header>
            <!-- End.main_haeder -->

            <section class="emPage__invoiceDetails">
                <div class="emhead__invoice">
                    <div class="brand__id">
                        <div class="brand">
                            <img src="assets/img/logo-b.svg" alt="">
                        </div>
                        <div class="date_ticket">
                            <span class="id color-secondary">#<?php echo $payment_gateway_reference; ?></span>
                            <span class="date color-text"><?php echo tanggal_indo(date('Y-m-d h:i', $created_at), true); ?></span>
                        </div>
                    </div>
                </div>
                <div class="embody__invoice">
                    <div class="about__sent">
                        <div class="billed__to">
                            <h2>Billed To:</h2>
                            <p class="username"><?php echo $decodedResponse["data"]["customer_name"]; ?></p>
                            <p>
                                <?php echo $decodedResponse["data"]["customer_phone"]; ?>
                                <br>
                                <?php echo $decodedResponse["data"]["customer_email"]; ?>
                                
                            </p>
                        </div>
                        <div class="pay__to">
                            
                        </div>
                    </div>
                    <div class="emtable__Details">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Pembayaran</th>
                                    
                                    <th scope="col">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="name_pt"><?php echo $decodedResponse["data"]["payment_name"]; ?></span>
                                    </td>
                                    
                                    <td><?php echo $total_payment; ?></td>
                                </tr>
                                

                            </tbody>
                        </table>
                        <div class="footer__detailsTable">
                            <div class="title_total">
                                Amount Total
                            </div>
                            <div class="detailsTotaly">
                                <div class="signature">
                                    <?php
                                        if ($method == 'QRIS') {
                                            echo "
                                            <div class='mb-3'>
                                            <div class='payment__infoSubtitle'>
                                                <small style='font-style: italic;'>* Klik untuk memperbesar kode QR</small>
                                                <a class='fancybox' data-toggle='modal' data-target='#exampleModal'>
                                                    <img src=" . $qr_url . " style='width:100%;max-width:170px !important;cursor:zoom-in'>
                                                </a>
                                            </div>
                                        </div>
                                        ";
                                        };
                                        ?>
                                </div>
                                <div class="txtDetails">
                                    <h3><?php echo $total_payment; ?></h3>
                                    <p>(Biaya Admin Rp.0)</p>
                                    <span>Due Date: <?php echo tanggal_indo(date('Y-m-d h:i', $expired_time), true); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="emfooter__invoice">

                    <div class="form-group">
                            <label>Kode Bayar</label>
                            <div class="input_group">
                                <input type="text" class="form-control" value="<?php echo $pay_code; ?>" id="myInput" disabled>
        <!-- The button used to copy the text -->
        <button id="myInput" onclick="myFunction()" class="btn bg-primary rounded-10 btn__default ml-3"><span class="color-white">Copy Kode Bayar<i class="fa fa-coffee"></i></span></button>
                                
                            </div>
                        </div>
                </div>
                <div id="snackbar">Kode Bayar Anda Berhasil disalin</div>
            </section>

        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="align-items: center;">
                    <img src="<?php echo $qr_url; ?>" style="width: 450px;">
                </div>
            </div>
        </div>

    </div>

    <!-- jquery -->
    <script src="assets/js/jquery-3.6.0.js"></script>
    <!-- popper.min.js 1.16.1 -->
    <script src="assets/js/popper.min.js"></script>
    <!-- bootstrap.js v4.6.0 -->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Owl Carousel v2.3.4 -->
    <script src="assets/js/vendor/owl.carousel.min.js"></script>
    <!-- Swiper 6.4.11 -->
    <script src="assets/js/vendor/swiper-bundle.min.js"></script>
    <!-- sharer 0.4.0 -->
    <script src="assets/js/vendor/sharer.js"></script>
    <!-- short-and-sweet v1.0.2 - Accessible character counter for input elements -->
    <script src="assets/js/vendor/short-and-sweet.min.js"></script>
    <!-- jquery knob -->
    <script src="assets/js/vendor/jquery.knob.min.js"></script>
    <!-- main.js -->
    <script src="assets/js/main.js" defer></script>
    <!-- PWA app service registration and works js -->
    <script src="assets/js/pwa-services.js"></script>
    
    <script type="text/javascript">
       function myFunction() {
          // Get the text field
          var copyText = document.getElementById("myInput");

          // Select the text field
          copyText.select();
          copyText.setSelectionRange(0, 99999); // For mobile devices

          // Copy the text inside the text field
          navigator.clipboard.writeText(copyText.value);
          
          var x = document.getElementById("snackbar");
          x.className = "show";
          setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
          
          
        }
    </script>

</body>

</html>
<?php
    }

    // RESPONSE EXAMPLE
    // {
    // "success": true,
    // "message": "",
    // "data": {
    // "reference": "DEV-T30280000007864GCQJK",
    // "merchant_ref": "DPO-9210313",
    // "payment_selection_type": "static",
    // "payment_method": "ALFAMART",
    // "payment_name": "Alfamart",
    // "customer_name": "sadiwantoro",
    // "customer_email": "sadiwantoro@yahoo.com",
    // "customer_phone": "082221584446",
    // "callback_url": "http://localhost/sosmed/tripay/callback.php",
    // "return_url": "http://localhost/sosmed/tripay/redirect.php",
    // "amount": 91250
    // "fee": 1250,
    // "is_customer_fee": 1,
    // "amount_received": 90000,
    // "pay_code": "324364698920072",
    // "pay_url": null,
    // "checkout_url": "https://payment.tripay.co.id/checkout/DEV-T30280000007864GCQJK",
    // "status": "UNPAID",
    // "expired_time": 1615714877,
    // "order_items": [
    // {
    // "sku": "Deposit",
    // "name": "Deposit Rp. 90000",
    // "price": 90000,
    // "quantity": 1,
    // "subtotal": 90000
    // }
    // ],
    // "instructions": [
    // {
    // "title": "Pembayaran via ALFAMART",
    // "steps": [
    // "Datang ke Alfamart",
    // "Sampaikan ke kasir ingin melakukan pembayaran Plasamall",
    // "Berikan kode bayar (324364698920072</b>) ke kasir",
    // "Bayar sesuai jumlah yang diinfokan oleh kasir",
    // "Simpan struk bukti pembayaran Anda"
    // ]
    // }
    // ]
    // }
    // }

    ?>