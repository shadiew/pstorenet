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
			$post_username = $_POST['username'];
			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "User Cannot Be Found.";
			} else {
				$delete_user = mysqli_query($db, "DELETE FROM users WHERE username = '$post_username'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "User <b>$post_username</b> Deleted.";
				}
			}
		}
	$title = "Users List";
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
                        Users List
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
                        <div class="card-title">Users List</div>
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
							<i class="icon icon-check icon-fw"></i>: Active User.<br />
							<i class="icon icon-times icon-fw"></i>: Inactive User.
						</div>
						<a href="<?php echo $cfg_baseurl; ?>/admin/user/add.php" class="btn btn-info btn-sm"><i class="icon icon-plus"></i>Add</a> <br /><br />
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                                <th></th>
								<th>Email</th>
								<th>Username</th>
								<th>Password</th>
								<th>Level</th>
								<th>Balance</th>
								<th>Registered</th>
								<th>Uplink</th>
								<th>API Key</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM users ORDER BY id DESC"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							?>
								<tr>
									<td align="center"><?php if($data_show['status'] == "Active") { ?><i class="icon icon-check"></i><?php } else { ?><i class="icon icon-times"></i><?php } ?></td>
									<td><?php echo $data_show['email']; ?></td>
									<td><?php echo $data_show['username']; ?></td>
									<td><?php echo $data_show['password']; ?></td>
									<td><?php echo $data_show['level']; ?></td>
									<td>$<?php echo $data_show['balance']; ?></td>
									<td><?php echo $data_show['registered']; ?></td>
									<td><?php echo $data_show['uplink']; ?></td>
									<td><?php echo $data_show['api_key']; ?></td>
									<td align="center">
										<a href="<?php echo $cfg_baseurl; ?>/admin/user/edit.php?username=<?php echo $data_show['username']; ?>" class="btn btn-warning btn-xs"><i class="icon icon-edit"></i></a>
										<a href="<?php echo $cfg_baseurl; ?>/admin/user/delete.php?username=<?php echo $data_show['username']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
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