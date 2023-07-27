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
			$checkdb_staff = mysqli_query($db, "SELECT * FROM faq WHERE id = '$post_id'");
			if (mysqli_num_rows($checkdb_staff) == 0) {
				$msg_type = "error";
				$msg_content = "FAQ Cannot Be Found.";
			} else {
				$delete_staff = mysqli_query($db, "DELETE FROM faq WHERE id = '$post_id'");
				if ($delete_staff == TRUE) {
					$msg_type = "success";
					$msg_content = "FAQ has been deleted.";
				}
			}
		}
	$title = "FAQs List";
	include("../../lib/header_admin.php");
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon-help_outline cyan-text s-18"></i>
                        FAQs List
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
                        <div class="card-title">FAQs List</div>
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
						<a href="<?php echo $cfg_baseurl; ?>/admin/faq/add.php" class="btn btn-info btn-sm"><i class="icon icon-plus"></i>Add</a> <br /><br />
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": true; "searching":true}'>
                            <thead>
                            <tr>
                                <th>Question</th>
								<th>Answer</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM faq ORDER BY id ASC"); // edit
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							?>
								<tr>
									<td><?php echo $data_show['question']; ?></td>
									<td><?php echo $data_show['answer']; ?></td>
									<td align="center">
										<a href="<?php echo $cfg_baseurl; ?>/admin/faq/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
										<a href="<?php echo $cfg_baseurl; ?>/admin/faq/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
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