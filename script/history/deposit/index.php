<?php
session_start();
require("../../lib/mainconfig.php");

  
if (!isset($_SESSION)) {
	session_start();
}
/* CLEARING POST DATA IF EXISTS*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$_SESSION['postdata'] = $_POST;
unset($_POST);
header("Location: ".$_SERVER[REQUEST_URI]);
exit;
}

if (@$_SESSION['postdata']){
$_POST=$_SESSION['postdata'];
unset($_SESSION['postdata']);
}
//clear

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
	$title = "Deposit History";
	include("../../lib/header.php");

?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon-time-is-money-1"></i>
                        Deposit History
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
                        <div class="card-title">Deposit History</div>
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": false; "searching":false}'>
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Deposit</th>
								<th>Date</th>
								<th>Method</th>
								<th>Transfer Amount</th>
								<th>Transaction ID / Sender / Email</th>
								<th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
								// start paging config
								$query_order = mysqli_query($db, "SELECT * FROM history_topup WHERE username = '$sess_username' ORDER BY id DESC");
								// end paging config
												
												while ($data_order = mysqli_fetch_assoc($query_order)) {
													if($data_order['status'] == "CANCEL") {
													    $label = "danger";
														$label2 = "CANCELED";
													} else if($data_order['status'] == "NO") {
													    $label = "warning";
														$label2 = "PENDING";
													} else if($data_order['status'] == "YES") {
													    $label = "success";
														$label2 = "SUCCESS";
													}
												?>
												<?php $no = $no+1; ?>
													<tr>
													    <td><center><?php echo $no ?></center></td>
														<td><?php echo $data_order['id_depo']; ?></td>
														<td><?php echo $data_order['date']; ?> <?php echo $data_order['time']; ?></td>
														<td><?php echo $data_order['name_method']; ?></td>
														<td><?php if($data_order['provider'] == "PAYTM"){echo "₹ ";}else{echo "$ ";} ?>
														<?php echo number_format($data_order['jumlah_transfer'],4); ?></td>
														<td><?php echo $data_order['nopengirim']; ?></td>
														<td><label class="badge badge-<?php echo $label; ?>"><?php echo $label2; ?></label></td>
													</tr>
												<?php
												}
												?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
<?php
	include("../../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}