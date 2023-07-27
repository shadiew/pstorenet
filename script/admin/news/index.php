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
			$checkdb_news = mysqli_query($db, "SELECT * FROM news WHERE id = '$post_id'");
			if (mysqli_num_rows($checkdb_news) == 0) {
				$msg_type = "error";
				$msg_content = "News Cannot Be Found.";
			} else {
				$delete_news = mysqli_query($db, "DELETE FROM news WHERE id = '$post_id'");
				if ($delete_news == TRUE) {
					$msg_type = "success";
					$msg_content = "News has been deleted.";
				}
			}
		}
	$title = "Daftar Berita";
	include("../../lib/header_admin.php");
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon icon-newspaper-o orange-text s-18"></i>
                        News List
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
                        <div class="card-title">News List</div>
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
						<a href="<?php echo $cfg_baseurl; ?>/admin/news/add.php" class="btn btn-info btn-sm"><i class="icon icon-plus"></i>Add</a> <br /><br />
                        <table class="table table-bordered table-hover data-tables"
                               data-options='{ "paging": iconlse; "searching":iconlse}'>
                            <thead>
                            <tr>
                                <th>#</th>
								<th>Date</th>
								<th>Category</th>
								<th>Content</th>
								<th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							// start paging config
							$query_list = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC"); // edit
							$no = 1;
							// end paging config
							while ($data_show = mysqli_fetch_assoc($query_list)) {
							    if($data_show['status'] == "INFO") {
									$label = "info";
									$label2 = "INFO";
								} else if($data_show['status'] == "NEW SERVICE") {
									$label = "success";
									$label2 = "NEW SERVICE";
								} else if($data_show['status'] == "SERVICE") {
									$label = "success";
									$label2 = "SERVICE";														
								} else if($data_show['status'] == "MAINTENANCE") {
									$label = "danger";
									$label2 = "MAINTENANCE";																										
								} else if($data_show['status'] == "UPDATE") {
									$label = "warning";
									$label2 = "UPDATE";						
								}
							?>
								<tr>
									<td><?php echo $no; ?></td>
									<td><?php echo $data_show['date']; ?> <?php echo $data_show['time']; ?></td>
									<td align="center"><label class="badge badge-<?php echo $label; ?>"><?php echo $data_show['status']; ?></label></td>
									<td><?php echo nl2br($data_show['content']); ?></td>
									<td align="center">
									<a href="<?php echo $cfg_baseurl; ?>/admin/news/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-warning"><i class="icon icon-edit"></i></a>
									<a href="<?php echo $cfg_baseurl; ?>/admin/news/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
									</td>
								</tr>
							<?php
							$no++;
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