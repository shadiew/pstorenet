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
			$checkdb_service = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				$msg_type = "error";
				$msg_content = "Contact Support! There is a problem in your database";
			} else {
				if (isset($_POST['edit'])) {
					$post_pay_paypal = htmlspecialchars($_POST['pay_paypal']);
					$post_pay_stripe_pk = htmlspecialchars($_POST['pay_stripe_pk']);
					$post_pay_stripe_sk = htmlspecialchars($_POST['pay_stripe_sk']);
					$post_pay_paytm = htmlspecialchars($_POST['pay_paytm']);
					$post_pay_paypal_min = (int) $_POST['pay_paypal_min'];
					$post_pay_stripe_min =  (int) $_POST['pay_stripe_min'];
					$post_pay_paytm_min =  (int) $_POST['pay_paytm_min'];
					$post_pay_paypal_on = htmlspecialchars($_POST['pay_paypal_on']);
					$post_pay_stripe_on = htmlspecialchars($_POST['pay_stripe_on']);
					$post_pay_paytm_on =  htmlspecialchars($_POST['pay_paytm_on']);
					$post_pay_paytm_email = htmlspecialchars($_POST['pay_paytm_email']);
					$post_pay_paytm_pass = htmlspecialchars($_POST['pay_paytm_pass']);

						$update_service = mysqli_query($db, "UPDATE settings SET pay_paypal = '$post_pay_paypal', pay_paypal_on = '$post_pay_paypal_on', pay_paypal_min = '$post_pay_paypal_min', pay_stripe_pk = '$post_pay_stripe_pk', pay_stripe_sk = '$post_pay_stripe_sk', pay_stripe_on = '$post_pay_stripe_on', pay_stripe_min = '$post_pay_stripe_min', pay_paytm = '$post_pay_paytm', pay_paytm_on = '$post_pay_paytm_on', pay_paytm_min = '$post_pay_paytm_min', pay_paytm_email = '$post_pay_paytm_email', pay_paytm_pass = '$post_pay_paytm_pass'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "Successfully changed.";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.".mysqli_error($db);
						}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				$title = "Payment Settings";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Payment Settings
                    </h4>
                </div>
            </div>
        </div>
    </header>

    <div class="animatedParent animateOnce">
        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body b-b">
						<h4>Payment Settings</h4>
                            <form role="form" method="POST">
								<?php 
								if ($msg_type == "success") {
								?>
								<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
								</button>
								<strong>Success!</strong> <?php echo $msg_content; ?>
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
								<div class="form-group">
									<h5 class="col-md-4 control-label">Paypal</h5>
									<label class="col-md-4 control-label">E-Mail To Receive Payments</label>
									<div class="col-md-10">
									<input type="email" class="form-control" name="pay_paypal" placeholder="E-Mail" value="<?php echo $datadb_service['pay_paypal']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Minimum Deposit Amount</label>
									<div class="col-md-10">
									<input type="number" min="0" class="form-control" name="pay_paypal_min" placeholder="Amount (Ex. 10)" value="<?php echo $datadb_service['pay_paypal_min']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Status (ON/OFF)</label>
									<div class="col-md-10">
										<select class="form-control" id="pay_paypal_on" name="pay_paypal_on">
														<option value="<?php echo $datadb_service['pay_paypal_on']; ?>">Selected [<?php echo $datadb_service['pay_paypal_on']; ?>]</option>
														<option value="ON">ON</option>
														<option value="OFF">OFF</option>
													</select>
									<span class="help-block"></span>
									</div>
								</div>
								<hr>
								<div class="form-group">
									<h5 class="col-md-4 control-label">Stripe</h5>
									<label class="col-md-4 control-label">Publishable key</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="pay_stripe_pk" placeholder="Publishable key" value="<?php echo $datadb_service['pay_stripe_pk']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Secret key</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="pay_stripe_sk" placeholder="Secret key" value="<?php echo $datadb_service['pay_stripe_sk']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Minimum Deposit Amount</label>
									<div class="col-md-10">
									<input type="number" min="0" class="form-control" name="pay_stripe_min" placeholder="Amount (Ex. 10)" value="<?php echo $datadb_service['pay_stripe_min']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Status (ON/OFF)</label>
									<div class="col-md-10">
										<select class="form-control" id="pay_stripe_on" name="pay_stripe_on">
														<option value="<?php echo $datadb_service['pay_stripe_on']; ?>">Selected [<?php echo $datadb_service['pay_stripe_on']; ?>]</option>
														<option value="ON">ON</option>
														<option value="OFF">OFF</option>
													</select>
									<span class="help-block"></span>
									</div>
								</div>
								<hr>
								<div class="form-group">
									<h5 class="col-md-4 control-label">Paytm</h5>
									<label class="col-md-4 control-label">Payment Link</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="pay_paytm" placeholder="Payment Link" value="<?php echo $datadb_service['pay_paytm']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Minimum Deposit Amount</label>
									<div class="col-md-10">
									<input type="number" min="0" class="form-control" name="pay_paytm_min" placeholder="Amount (Ex. 10)" value="<?php echo $datadb_service['pay_paytm_min']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Notification Email</label>
									<div class="col-md-10">
									<input type="email" class="form-control" name="pay_paytm_email" placeholder="Notification Email" value="<?php echo $datadb_service['pay_paytm_email']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Notification Email Password</label>
									<div class="col-md-10">
									<input type="password" class="form-control" name="pay_paytm_pass" placeholder="Notification Email Password" value="<?php echo $datadb_service['pay_paytm_pass']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Status (ON/OFF)</label>
									<div class="col-md-10">
										<select class="form-control" id="pay_paytm_on" name="pay_paytm_on">
														<option value="<?php echo $datadb_service['pay_paytm_on']; ?>">Selected [<?php echo $datadb_service['pay_paytm_on']; ?>]</option>
														<option value="ON">ON</option>
														<option value="OFF">OFF</option>
													</select>
									<span class="help-block"></span>
									</div>
								</div>
								<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light"><i class="icon icon-refresh"></i> Reset</button>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="edit"><i class="icon icon-send"></i> Edit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
				include("../../lib/footer.php");
			}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>