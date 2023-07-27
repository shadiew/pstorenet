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
	} else if ($data_user['level'] == "Member") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_POST['add'])) {
			$post_username = htmlspecialchars($_POST['username']);
			$post_balance = htmlspecialchars($_POST['balance']);

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (empty($post_username) || empty($post_balance)) {
				$msg_type = "error";
				$msg_content = "Please Fill In All Inputs.";
			} else if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "User Cannot Be Found.";
			} else if ($post_balance < $cfg_min_transfer) {
				$msg_type = "error";
				$msg_content = "Minimum Transfer is <b>$ $cfg_min_transfer</b>.";
			} else {
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance+$post_balance WHERE username = '$post_username'"); // send receiver
				$insert_tf = mysqli_query($db, "INSERT INTO transfer_balance (sender, receiver, quantity, date) VALUES ('$sess_username', '$post_username', '$post_balance', '$date')");	
				
				$check_balance = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
				$data_balance = mysqli_fetch_assoc($check_balance);
				$temp_balance = number_format($data_balance['balance'], 4);
				
        		$insert_tf = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, clearance, msg, date, time, type) VALUES ('$post_username', 'Add Balance', '$post_balance', '$$temp_balance', 'You have been transferred a balance by ADMIN', '$date', '$time', '+ $')");				    
				if ($insert_tf == TRUE) {
					$msg_type = "success";
					$msg_content = "You have successfully transferred the balance to<b>$post_username</b> | <b>$".number_format($post_balance,0,',','.')."</b>.";
				} else {
					$msg_type = "error";
					$msg_content = "A System Error Occurred.";
				}
			}
		}
	$title = "Add Funds";
	include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon icon icon-money purple-text s-18"></i>
                        Add Funds
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
						<h4>Add Funds</h4>
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
								<strong>Success!</strong> <?php echo $msg_content; ?>
								</div>
								<?php
								}
								?>
                                <div class="form-group">
									<span class="help-block"></span>
									<label class="control-label">Username</label>
									<input type="text" name="username" class="form-control" placeholder="Recipient's username">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Total Transfer</label>
									<input type="number" name="balance" step="0.0001" class="form-control" placeholder="Total Transfer">
									<span class="help-block"></span>
								</div>
								<button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</a>
								<button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="add"><i class="fa fa-send"></i> Add</button>
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
} else {
	header("Location: ".$cfg_baseurl);
}
?>