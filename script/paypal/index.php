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
    if($data_settings['pay_paypal_on'] == "OFF"){
		header("Location: ".$cfg_baseurl);
	}
	$title = "Deposit";
	include("../lib/header.php");
	$msg_type = "nothing";
	
?>

    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-paypal"></i>
                        Paypal Deposit
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

                        <!-- PAYPAL DEPOSIT FORM -->
						<h4>Paypal Deposit</h4>
								<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_xclick">
								<input type="hidden" name="cancel_return" value="<?php echo $cfg_baseurl; ?>/paypal">
								<input type="hidden" name="return" value="<?php echo $cfg_baseurl; ?>/paypal/history.php">
								<input type="hidden" name="notify_url" value="<?php echo $cfg_baseurl; ?>/paypal/verify.php">
								<input type="hidden" name="rm" value="2"> 
								<input type="hidden" name="cbt" value="Return To Panel"> 
								<input type="hidden" name="business" value="<?php echo $data_settings['pay_paypal']; ?>">
								<input type="hidden" name="item_name" value="Credit Purchase">
								<input type="hidden" name="currency_code" value="USD">
								<input type="hidden" name="custom" value="<?php echo $sess_username ?>">
								<input type="hidden" name="button_subtype" value="services">
                                <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" class="paypal-logo">
								<div class="form-group">
                                    <label for="sms" class="col-form-label">Deposit Amount (USD)</label>
                                    <input required type="number" pattern="[0-9]{10}" name="amount" min="<?php echo $data_settings['pay_paypal_min']; ?>" step="0.1" class="form-control" placeholder="$" onkeyup="get_total(this.value).value;">
                                </div>
								<input type="submit" id="paypal" class="btn btn-primary" value="Deposit">
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
							<?php echo $data_settings['paypal_ins']; ?>
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