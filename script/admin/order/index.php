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
	
	$title = "Order List";
	include("../../lib/header_admin.php");

	// widget
	$check_worder = mysqli_query($db, "SELECT SUM(price) AS total FROM orders");
	$data_worder = mysqli_fetch_assoc($check_worder);
	$check_worder = mysqli_query($db, "SELECT * FROM orders");
	$count_worder = mysqli_num_rows($check_worder);

	$check_worder_success = mysqli_query($db, "SELECT SUM(price) AS total FROM profit WHERE status = 'Success'");
	$data_worder_success = mysqli_fetch_assoc($check_worder_success);
	$check_worder_success = mysqli_query($db, "SELECT * FROM orders");
	$count_worder_success = mysqli_num_rows($check_worder_success);
	
	$check_worder_provider = mysqli_query($db, "SELECT SUM(price_provider) AS total FROM profit WHERE status = 'Success'");
	$data_worder_provider = mysqli_fetch_assoc($check_worder_provider);
	$check_worder_provider = mysqli_query($db, "SELECT * FROM orders");
	$count_worder_provider = mysqli_num_rows($check_worder_provider);
	
	$pesanan = $data_worder_success['total'];
	$pusat = $data_worder_provider['total'];
	$keuntungan = $pesanan-$pusat;
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue acck_worder_juale nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-package teal-text s-18"></i>
                        Order List
                    </h3>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card my-3 no-b">
                    <div class="card-body">
						<div class="table-responsive">
                        <div class="card-title">Order List</div>
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
						<div class="alert alert-info">
							$<?php echo $data_worder['total']; ?> - Total Purchases.<br />
							<?php echo number_format($count_worder,0,',','.'); ?> - Total Order.<br />
						</div>
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                                <th></th>
								<th>OID</th>
								<th>Username</th>
								<th>Date</th>
								<th>Service</th>
								<th>Target</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Price Provider</th>
								<th>Provider</th>
								<th>OID Provider</th>
								<th>Status</th>
								<th>Refund</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
								if($data_show['status'] == "Pending") {
									$label = "warning";
								} else if($data_show['status'] == "Processing") {
									$label = "info";
								} else if($data_show['status'] == "In Progress") {
									$label = "info";
								} else if($data_show['status'] == "Error") {
									$label = "danger";
								} else if($data_show['status'] == "Canceled") {
									$label = "danger";
								} else if($data_show['status'] == "Partial") {
									$label = "danger";
								} else if($data_show['status'] == "Success") {
									$label = "success";
								} else if($data_show['status'] == "Completed") {
									$label = "success";
								}
								?>
								<tr>
									<td align="center"><?php if($data_show['place_from'] == "API") { ?><i class="icon icon-random"></i><?php } else { ?><i class="icon icon-globe"></i><?php } ?></td>
									<td><?php echo $data_show['oid']; ?></td>
									<td><?php echo $data_show['user']; ?></td>
									<td><?php echo $data_show['date']; ?> <?php echo $data_show['time']; ?></td>
									<td><?php echo $data_show['service']; ?></td>
									<td><input type="text" class="form-control width-200" value="<?php echo $data_show['link']; ?>" readonly></td>
									<td><?php echo number_format($data_show['quantity'],0,',','.'); ?></td>
									<td>$<?php echo $data_show['price']; ?></td>
									<td>$<?php echo $data_show['price_provider']; ?></td>
									<td><?php if(empty($data_show['provider'])){echo "MANUAL";}else{echo $data_show['provider']; }?></td>
									<td><?php echo $data_show['poid']; ?></td>
									<td><label class="badge badge-<?php echo $label; ?>"><?php echo $data_show['status']; ?></label></td>
									<td><label class="badge badge-<?php if($data_show['refund'] == 0) { echo "danger"; } else { echo "success"; } ?>"><?php if($data_show['refund'] == 0) { ?><i class="icon icon-times"></i><?php } else { ?><i class="icon icon-check"></i><?php } ?></label></td>
									<td align="center">
								    	<a href="<?php echo $cfg_baseurl; ?>/admin/order/detail.php?poid=<?php echo $data_show['poid']; ?>" class="btn btn-xs btn-info"><i class="icon icon-eye"></i></a>
										<a href="<?php echo $cfg_baseurl; ?>/admin/order/edit.php?poid=<?php echo $data_show['poid']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
									</td>
								</tr>
								<?php
								}
								?>
							</tbody>
                        </table>
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