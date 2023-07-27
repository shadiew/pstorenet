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
			$id = $_POST['id'];
			$checkdb_user = mysqli_query($db, "SELECT * FROM provider WHERE id = '$id'");
			if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "Provider Cannot Be Found.";
			} else {
				$delete_user = mysqli_query($db, "DELETE FROM provider WHERE id = '$id'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "Provider Deleted.";
				}
			}
		}
	$title = "Provider";
	include("../../lib/header_admin.php");
	$check_wuser = mysqli_query($db, "SELECT SUM(balance) AS total FROM users");
	$data_wuser = mysqli_fetch_assoc($check_wuser);
	$check_wuser = mysqli_query($db, "SELECT * FROM users");
	$count_wuser = mysqli_num_rows($check_wuser);
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-users deep-purple-text s-18"></i>
                        Provider
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
                        <div class="card-title">Provider</div>
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
						<a href="<?php echo $cfg_baseurl; ?>/admin/api/add.php" class="btn btn-info btn-sm"><i class="icon icon-plus"></i>Add</a> <br /><br />
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
								<th>Name</th>
								<th>API LINK</th>
								<th>API Key</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM provider ORDER BY id DESC"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							?>
								<tr>
									<td><?php echo $data_show['code']; ?></td>
									<td><?php echo $data_show['link']; ?></td>
									<td><?php echo $data_show['api_key']; ?></td>
									<td align="center">
										<div style="<?php if($data_show['code']=="MANUAL"){echo "display:none;";} ?>">
										<a href="<?php echo $cfg_baseurl; ?>/admin/api/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-warning btn-xs"><i class="icon icon-edit"></i></a>
										<a href="<?php echo $cfg_baseurl; ?>/admin/api/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
										</div>
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