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
	
	$title = "Deposit List";
	include("../../lib/header_admin.php");
	
	if (isset($_GET['status'])) {
		$status = $_GET['status'];
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
	}

	// widget
	$check_worder = mysqli_query($db, "SELECT SUM(price) AS total FROM orders");
	$data_worder = mysqli_fetch_assoc($check_worder);
	$check_worder = mysqli_query($db, "SELECT * FROM orders");
	$count_worder = mysqli_num_rows($check_worder);
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-money deep-blue-text s-18"></i>
                        Deposit List
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
                        	<div class="card-title">Deposit List</div>
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
                        	<table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                            	<th></th>
				<th>ID Deposit</th>
				<th>Method</th>
				<th>Date</th>
				<th>Transfer</th>
				<th>Amount</th>
				<th>Username</th>
				<th>ID Transaction / Sender / Email</th>
				<th>Target</th>
				<th>Status</th>
				<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
				<?php
				// start paging config
				$query_list = mysqli_query($db, "SELECT * FROM history_topup ORDER BY id DESC"); // edit
				// end paging config
				while ($data_show = mysqli_fetch_assoc($query_list)) {
					if($data_show['status'] == "NO") {
						$status = "Pending";
						$label = "warning";
					} else if($data_show['status'] == "CANCEL") {
						$status = "Canceled";
						$label = "danger";
					} else if($data_show['status'] == "YES") {
						$status = "Success";
						$label = "success";
					}
					?>
					<tr>
						<td align="center"><?php if($data_show['place_from'] == "API") { ?><i class="icon icon-random"></i><?php } else { ?><i class="icon icon-globe"></i><?php } ?></td>
						<td><?php echo $data_show['id_depo']; ?></td>
						<td><?php echo $data_show['name_method']; ?></td>
						<td><?php echo $data_show['date']; ?> <?php echo $data_show['time']; ?></td>
						<td>$ <?php echo number_format($data_show['jumlah_transfer'],3); ?></td>
						<td>$ <?php echo number_format($data_show['amount'],3); ?></td>
						<td><?php echo $data_show['username']; ?></td>
						<td><?php echo $data_show['nopengirim']; ?></td>
						<td><?php echo $data_show['norek_tujuan_trf']; ?></td>
						<td><label class="badge badge-<?php echo $label; ?>"><?php echo $status; ?></label></td>
						<td align="center">
							<a href="<?php echo $cfg_baseurl; ?>/admin/deposit/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
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