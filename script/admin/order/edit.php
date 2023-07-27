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
							$msg_content = "<b>Order ID:</b> $post_poid<br /><b>Status:</b> $post_status";
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
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Change Order
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
						<h4>Change Order</h4>
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
									<span class="help-block"></span>
									<label class="col-md-2 control-label">Provider ID</label>
									<div class="col-md-10">
									<input type="text" class="form-control" placeholder="Order ID" value="<?php echo $datadb_order['poid']; ?>" readonly>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Status</label>
									<div class="col-md-10">
									<select class="form-control" name="status">
										<option value="<?php echo $datadb_order['status']; ?>"><?php echo $datadb_order['status']; ?> (Selected)</option>
										<option value="Pending">Pending</option>
										<option value="Processing">Processing</option>
										<option value="In Progress">In Progress</option>
										<option value="Error">Error</option>
										<option value="Partial">Partial</option>
										<option value="Success">Success</option>
										<option value="Canceled">Canceled</option>
									</select>
									<span class="help-block"></span>
									</div>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/order/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="fa fa-arrow-circle-left"></i> Back</a>
								<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light"><i class="fa fa-refresh"></i> Reset</button>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="edit"><i class="fa fa-send"></i> Edit</button>
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
			header("Location: ".$cfg_baseurl."/admin/order/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>