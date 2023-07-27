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
		if (isset($_GET['sid'])) {
			$post_sid = $_GET['sid'];
			$check_target = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
			$data_target = mysqli_fetch_assoc($check_target);
			if (mysqli_num_rows($check_target) == 0) {
				header("Location: ".$cfg_baseurl."admin/service/");
			} else {
				$title = "Service Details";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-eye"></i>
                        Service Details
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
						<h4>Service Details</h4>
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
											<th>Service ID</th>
											<td><?php echo $data_target['sid']; ?></td>
										</tr>
										<tr>
											<th>Service Name</th>
											<td><?php echo $data_target['service']; ?></td>
										</tr>
										<tr>
											<th>Category</th>
											<td>
												<?php
												$check_cat = mysqli_query($db, "SELECT * FROM service_cat WHERE code = '$data_target[category]'");
												$data_cat = mysqli_fetch_array($check_cat);
												?>
												<?php echo $data_cat['name']; ?>
											</td>
										</tr>
										<tr>
											<th>Note</th>
											<td><?php echo nl2br($data_target['note']); ?></td>
										</tr>
										<tr>
											<th>Min Order</th>
											<td><?php echo number_format($data_target['min'],0,',','.'); ?></td>
										</tr>
										<tr>
											<th>Max Order</th>
											<td><?php echo number_format($data_target['max'],0,',','.'); ?></td>
										</tr>
										<tr>
											<th>Price/K</th>
											<td><?php echo number_format($data_target['price'],0,',','.'); ?></td>
										</tr>
										<tr>
											<th>Price Provider/K</th>
											<td><?php echo number_format($data_target['price_provider'],0,',','.'); ?></td>
										</tr>
										<tr>
											<th>Provider ID</th>
											<td><?php echo $data_target['pid']; ?></td>
										</tr>
										<tr>
											<th>Provider</th>
											<td><?php echo $data_target['provider']; ?></td>
										</tr>
										<tr>
											<th>Status</th>
											<td><?php echo $data_target['status']; ?></td>
										</tr>
									</table>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/service/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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
			header("Location: ".$cfg_baseurl."/admin/service/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>