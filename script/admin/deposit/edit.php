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
			$checkdb_depo = mysqli_query($db, "SELECT * FROM history_topup WHERE id = '$post_id'");
			$datadb_depo = mysqli_fetch_assoc($checkdb_depo);
			$user = $datadb_depo['username'];
			$saldo = $datadb_depo['amount'];
			$id_depo = $datadb_depo['id_depo'];
			if (mysqli_num_rows($checkdb_depo) == 0) {
				header("Location: ".$cfg_baseurl."/admin/deposit/");
			} else {
				if (isset($_POST['edit'])) {
					$post_status = $_POST['status'];
					if (empty($post_status)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else {
						if ($post_status == "YES") {
							$update_depo = mysqli_query($db, "UPDATE users SET balance = balance+$saldo WHERE username = '$user'");
							
							$check_balance = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
							$data_balance = mysqli_fetch_assoc($check_balance);
							$temp_balance = number_format($data_balance['balance'], 4);

							$update_depo = mysqli_query($db, "UPDATE history_topup SET status = '$post_status' WHERE id = '$post_id'");
                    		$update_depo = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, clearance, msg, date, time, type) VALUES ('$user', 'Add Balance', '$saldo', '$$temp_balance', 'You Have Made a Balance Deposit, ID: $id_depo', '$date', '$time', '+ $')");
							
							if ($update_depo == TRUE) {
								$msg_type = "success";
								$msg_content = "Deposit Amended.";
							} else {
								$msg_type = "error";
								$msg_content = "A System Error Occurred.";
							}
						} else {
							$update_depo = mysqli_query($db, "UPDATE history_topup SET status = '$post_status' WHERE id = '$post_id'");
							if ($update_depo == TRUE) {
								$msg_type = "success";
								$msg_content = "Deposit Amended.";
							} else {
								$msg_type = "error";
								$msg_content = "A System Error Occurred.";
							}
						}
					}
				}
				$checkdb_depo = mysqli_query($db, "SELECT * FROM history_topup WHERE id = '$post_id'");
				$datadb_depo = mysqli_fetch_assoc($checkdb_depo);
				$title = "Change Deposit";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Change Deposit
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
						<h4>Change Deposit</h4>
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
									<label class="control-label">Status</label>
									<select class="form-control" name="status">
										<option value="<?php echo $datadb_depo['status']; ?>"><?php echo $datadb_depo['status']; ?> [SELECTED]</option>
										<option value="YES">YES</option>
										<option value="CANCEL">CANCEL</option>
									</select>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/deposit/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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
		} else {
			header("Location: ".$cfg_baseurl."/admin/deposit/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>