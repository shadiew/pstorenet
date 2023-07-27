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
			$post_id = $_POST['id'];
			$post_name = $_POST['name'];
			$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE id = '$post_id'");
			if (mysqli_num_rows($checkdb_staff) == 0) {
				$msg_type = "error";
				$msg_content = "Staff Cannot Be Found.";
			} else {
				$delete_staff = mysqli_query($db, "DELETE FROM staff WHERE id = '$post_id'");
				if ($delete_staff == TRUE) {
					$msg_type = "success";
					$msg_content = "Staff has been deleted.";
				}
			}
		}
	$title = "Staff List";
	include("../../lib/header_admin.php");
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-child cyan-text s-18"></i>
                        Staff List
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
                        <div class="card-title">Staff List</div>
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
						<a href="<?php echo $cfg_baseurl; ?>/admin/staff/add.php" class="btn btn-info btn-sm"><i class="icon icon-plus"></i>Add</a> <br /><br />
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                                <th>Name</th>
								<th>Facebook</th>
								<th>WhatsApp</th>
								<th>Instagram</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM staff ORDER BY level ASC"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							?>
								<tr>
									<td><?php echo $data_show['name']; ?></td>
									<td><?php echo $data_show['facebook']; ?></td>
									<td><?php echo $data_show['nomor']; ?></td>
									<td><?php echo $data_show['instagram']; ?></td>
									<td align="center">
										<a href="<?php echo $cfg_baseurl; ?>/admin/staff/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
										<a href="<?php echo $cfg_baseurl; ?>/admin/staff/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
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