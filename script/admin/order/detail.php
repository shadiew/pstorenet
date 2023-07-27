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
			$post_url = $_GET['url'];
			$check_target = mysqli_query($db, "SELECT * FROM blog WHERE url = '$post_url'");
			$data_target = mysqli_fetch_assoc($check_target);
			
	$check_worder_success = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE poid = '$post_poid'");
	$data_worder_success = mysqli_fetch_assoc($check_worder_success);
	$check_worder_provider = mysqli_query($db, "SELECT SUM(price_provider) AS total FROM orders WHERE poid = '$post_poid'");
	$data_worder_provider = mysqli_fetch_assoc($check_worder_provider);
	
	$pesanan = $data_worder_success['total'];
	$pusat = $data_worder_provider['total'];
	$keuntungan = $pesanan-$pusat;
			if (mysqli_num_rows($check_target) == 0) {
				header("Location: ".$cfg_baseurl."admin/order/");
			} else {
				$title = "Detail Orders";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-eye"></i>
                        Detail Orders
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
						<h4>Detail Orders</h4>
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
                                <div class="table-responsive">
									<table class="table table-bordered table-striped">
										<tr>
											<th>Date</th>
											<td><?php echo $data_target['date']; ?> <?php echo $data_target['time']; ?></td>
										</tr>
										<tr>
											<th>ID Order</th>
											<td><?php echo $data_target['oid']; ?></td>
										</tr>
										<tr>
											<th>Provider ID</th>
											<td><?php echo $data_target['poid']; ?></td>
										</tr>
										<tr>
											<th>Username</th>
											<td><?php echo $data_target['user']; ?></td>
										</tr>
										<tr>
											<th>Service</th>
											<td><?php echo $data_target['service']; ?></td>
										</tr>
										<tr>
											<th>Target</th>
											<td><?php echo $data_target['link']; ?></td>
										</tr>
										<tr>
											<th>Quantity</th>
											<td><?php echo $data_target['oid']; ?></td>
										</tr>
										<tr>
											<th>Remains</th>
											<td><?php echo $data_target['remains']; ?></td>
										</tr>
										<tr>
											<th>Price/K</th>
											<td>$ <?php echo $data_target['price']; ?></td>
										</tr>
										<tr>
											<th>Price Provider/K</th>
											<td>$ <?php echo $data_target['price_provider']; ?></td>
										</tr>
										<tr>
											<th>Profit</th>
											<td> <?php echo number_format($keuntungan,0,',','.'); ?></td>
										</tr>
									</table>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/order/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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
			header("Location: ".$cfg_baseurl."/admin/services.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>