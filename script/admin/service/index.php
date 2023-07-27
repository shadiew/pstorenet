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
		if (isset($_POST['delete'])) {
			$post_sid = $_POST['sid'];
			$checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
			if (mysqli_num_rows($checkdb_service) == 0) {
				$msg_type = "error";
				$msg_content = "Service Cannot Be Found.";
			} else {
				$delete_user = mysqli_query($db, "DELETE FROM services WHERE sid = '$post_sid'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "Service Deleted.";
				}
			}
		}
	$title = "List of services";
	include("../../lib/header_admin.php");
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-room_service light-blue-text s-18"></i>
                        List of services
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
                        <div class="card-title">List of services</div>
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
							<i class="icon icon-check icon-fw"></i>: Active Services.<br />
							<i class="icon icon-times icon-fw"></i>: Inactive Service.
						</div>
						<a href="<?php echo $cfg_baseurl; ?>/admin/service/add.php" class="btn btn-info btn-sm"><i class="icon icon-plus"></i>Add</a> <br /><br />
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                                <th></th>
								<th>Category</th>
								<th>Service</th>
								<th>Min</th>
								<th>Max</th>
								<th>Price/1000</th>
								<th>Price Provider/K</th>
								<th>Code Provider</th>
								<th>PID</th>
								<th>SID</th>
								<th>Note</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM services"); // edit
							//$query_list1 = mysqli_query($db, "SELECT * FROM services ORDER BY sid ASC"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							?>
								<tr>
									<td align="center"><?php if($data_show['status'] == "Active") { ?><i class="icon icon-check"></i><?php } else { ?><i class="icon icon-times"></i><?php } ?></td>
									<td><?php echo $data_show['category']; ?></td>
									<td><?php echo $data_show['service']; ?></td>
									<td><?php echo number_format($data_show['min']); ?></td>
									<td><?php echo number_format($data_show['max']); ?></td>
									<td><?php echo $data_show['price']; ?></td>
									<td><?php echo $data_show['price_provider']; ?></td>
									<td><?php echo $data_show['provider']; ?></td>
									<td><?php echo $data_show['pid']; ?></td>
									<td><?php echo $data_show['sid']; ?></td>
									<td><?php echo $data_show['note']; ?></td>
									<td align="center">
									<a href="<?php echo $cfg_baseurl; ?>/admin/service/detail.php?sid=<?php echo $data_show['sid']; ?>" class="btn btn-xs btn-info"><i class="icon icon-eye"></i></a>
									<a href="<?php echo $cfg_baseurl; ?>/admin/service/edit.php?sid=<?php echo $data_show['sid']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
									<a href="<?php echo $cfg_baseurl; ?>/admin/service/delete.php?sid=<?php echo $data_show['sid']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
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