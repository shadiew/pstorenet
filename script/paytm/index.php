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

	/* GENERAL WEB SETTINGS */
	$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
	$data_settings = mysqli_fetch_assoc($check_settings);
	if($data_settings['pay_paytm_on'] == "OFF"){
		header("Location: ".$cfg_baseurl);
	}
	$title = "Deposit";
	include("../lib/header.php");
	$msg_type = "nothing";

	/* PAYTM SUBMISSION HANDLER */
	if (isset($_POST['submit'])) {
		$post_quantity = (int)$_POST['quantity'];
		$transid0 = $_POST['transid'];
		$transid = filter_var($transid0, FILTER_SANITIZE_NUMBER_INT);
        $rate_usd_to_inr2 = "72";
        $rate_usd_to_inr = strval($rate_usd_to_inr2);
		
			    $quantity = $post_quantity;
				$provider = "PAYTM";
				$namebankmethod = "Paytm";
				$balance_amount = $post_quantity/$rate_usd_to_inr; //converted money

		?>

			<!-- CLEAR POST DATA ON REFRESH -->
			<script>history.pushState({}, "", "")</script>

		<?php
				
		$demo = $data_user['status'];
		if ($demo == "Demo") {
			$msg_type = "error";
			$msg_content = "Sorry this feature is not available for Demo users.";
		}
		else if (empty($post_quantity) || empty($transid)) {
			$msg_type = "error";
			$msg_content = "Please fill in all inputs first.";
		} else if ($post_quantity < 5) {
			$msg_type = "error";
			$msg_content = "Minimum deposit is Rs.5";
		} else {
			$resultpop = mysqli_query($db, "SELECT * FROM `history_topup` WHERE `nopengirim` = '$transid' AND `provider` = 'PAYTM' AND `status` = 'YES'");
			if( mysqli_num_rows($resultpop) > 0) {
				$msg_type = "error";
				$msg_content = "Transaction ID is already used.";
			}
			else{
			
			/* SUCCESSFULLY ADDING PAYTM REQUEST */
				
				/* GENERATING DEPO ID */
				$check_highest_id = mysqli_query($db, "SELECT * FROM `history_topup` ORDER BY `id_depo` DESC LIMIT 1");
				$highest_id = mysqli_fetch_array($check_highest_id);
				$id_depo = $highest_id['id_depo'] + 1;

				$insert_topup = mysqli_query($db, "INSERT INTO history_topup(provider, amount, jumlah_transfer, username, user, norek_tujuan_trf, nopengirim, date, time, status, type, id_depo, top_ten, name_method) VALUES ('$provider','$balance_amount','$quantity','$sess_username','$sess_username','--','$transid','$date','$time','NO','WEB','$id_depo','ON', '$namebankmethod')");
				if ($insert_topup == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Request for paytm balance received.</b><br /><b>Total Transfer:</b> Rs ".$quantity."<br /><b>Obtained Balance: $</b>".number_format($balance_amount,3);
					$msg_depo = "Please make sure you have made the payment of Rs. ".$quantity."</b></span><br /><span class='color-red'>If the transfer amount does not match then the system will not process your deposit request.</span><br>
					<hr>
					If you have transferred please wait for your balance to increase.<br>
					If the balance is not entered more than 5 minutes, please contact the admin.<br>
					Please also check the status of the deposit in Deposit History.";

				//CALLING PAYTM CRON

				file_get_contents($cfg_baseurl . '/paytm/verify.php');

				//CALLING PAYTM CRON

				} else {
					$msg_type = "error";
					$msg_content = "System error.";
				}
				
			}
		}
	}
	
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-inr"></i>
                        Paytm Deposit
                    </h4>
                </div>
            </div>
        </div>
    </header>

    <div class="animatedParent animateOnce">
        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body b-b">
						<h4>Paytm Deposit</h4>
                            <form  name='autoSumForm' role="form" method="POST">

								<!-- MESSAGE NOTIFICATION SYSTEM -->
								<?php 
								if ($msg_type == "success") {
								?>
								<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
								</button>
								<strong>Success!</strong> <?php echo $msg_content; ?>
								</div>
								
								<div class="alert alert-info alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
								</button>
								<strong>Info!</strong> <?php echo $msg_depo; ?>
								</div>
								<?php
								} else if ($msg_type == "error") {
								?>
								<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
								</button>
								<strong>Failed!</strong> <?php echo $msg_content; ?>
								</div>
								<?php
								}
								?>

								<div class="card">
									<div class="card-body b-b"><h3>Instructions</h3>
									<hr>
									<b>Step 1.</b>:
									<ul>
										<li>Open this <a href="<?php echo $data_settings['pay_paytm']; ?>" rel="im-checkout" data-behaviour="remote" data-style="light" data-text="Checkout With Paytm" target="_blank">link</a> or click the button below to pay.</li>
										<li><a href="<?php echo $data_settings['pay_paytm']; ?>"  target="_blank" rel="im-checkout" data-behaviour="remote" data-style="light" data-text="Checkout With Paytm" class="paytm-box"> <div class="paytm-image-box">Pay with<img src="../assets/img/basic/paytmLogo.png" class="paytm-image"></div></a></li>
										</ul>
									<b>Step 2.</b>
									<ul>
										<li>Enter the 18 Digits Paytm Order ID and Amount shown after the successfull payment.</li>
										<li>If an error occurs when depositing, Please report by Live Chat or Raising a ticket.</li>
										<br>
										<li><b>*Minimum amount to deposit is Rs.1(INR)</b></li>
										<li><b>*Don't add amount in decimals. Example (Rs 10.5)</b></li>
									</ul>
								</div></div><br>

								<!-- PAYTM DEPOSIT FORM FIELDS -->
								<div class="form-group">
                                    <label for="sms" class="col-form-label">Order ID</label>
                                    <input required type="number" name="transid" pattern="[0-9]{10}" min="0" step="1" class="form-control" placeholder="Order ID">
                                </div>
								<div class="form-group">
                                    <label for="sms" class="col-form-label">Deposit Amount (INR)</label>
                                    <input required type="number" name="quantity" pattern="[0-9]{10}" min="<?php echo $data_settings['pay_paytm_min']; ?>" step="1" class="form-control" placeholder="Rs">
                                </div>
                                <button type="submit" class="btn btn-primary" name="submit">Deposit</button>
                            </form>
                        </div>
                    </div>
                </div><br>
                <div class="col-md-5">
					<!-- INFORMATION TAB -->
                    <div class="card">
                    <div class="card-body b-b"><h3>Information</h3>
                    <hr>
                        <div class="panel-body">
							<?php echo $data_settings['paytm_ins']; ?>
						</div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>