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
			$post_sid = $_POST['id'];
			$post_name = $_POST['name'];
			$checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_sid'");
			if (mysqli_num_rows($checkdb_service) == 0) {
				$msg_type = "error";
				$msg_content = "Deposit Method Cannot Be Used.";
			} else {
				$delete_user = mysqli_query($db, "DELETE FROM deposit_method WHERE id = '$post_sid'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "Deposit Method Deleted.";
				}
			}
		}
	$title = "Deposit Method";
	include("../../lib/header_admin.php");
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-money deep-blue-text s-18"></i>
                       Deposit Method
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
                        	<div class="card-title">Deposit Method</div>
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
					<a href="<?php echo $cfg_baseurl; ?>/admin/depo/add.php" class="btn btn-info btn-sm margin-left-10"><i class="icon icon-plus"></i>Add</a> <br /><br />
                          <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                                <th></th>
								<th>Method</th>
								<th>Target</th>
								<th>Note</th>
								<th>Rate</th>
								<th>Status</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM deposit_method"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							    	if($data_show['Active'] == "YES") {
										$label = "success";
									} else if($data_show['Active'] == "NO") {
										$label = "danger";
									}
							?>
								<tr>
									<td align="center"><i class="icon icon-check"></i></td>
									<td><?php echo $data_show['name']; ?></td>
									<td><?php echo $data_show['data']; ?></td>
									<td><?php echo $data_show['note']; ?></td>
									<td><?php echo $data_show['rate']; ?>%</td>
									<td align="center"><label class="badge badge-<?php echo $label; ?>"><?php echo $data_show['Active']; ?></label></td>
									<td align="center">
				                    <a href="<?php echo $cfg_baseurl; ?>/admin/depo/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
									<a href="<?php echo $cfg_baseurl; ?>/admin/depo/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
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