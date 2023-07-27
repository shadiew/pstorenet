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
			$checkdb_user = mysqli_query($db, "SELECT * FROM tickets WHERE id = '$post_id'");
			if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "Ticket Cannot Be Found.";
			} else {
	    		$delete_ticket = mysqli_query($db, "DELETE FROM tickets WHERE id = '$post_id'");
				$delete_ticket = mysqli_query($db, "DELETE FROM tickets_message WHERE ticket_id = '$post_id'");
				if ($delete_ticket == TRUE) {
					$msg_type = "success";
					$msg_content = "Successfully Deleted.";
				}
			}
		}
	$title = "Ticket Support";
	include("../../lib/header_admin.php");
?>
<div class="page has-sidebar-left bg-light height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row">
                <div class="col">
                    <h3 class="my-3">
                        <i class="icon icon-email"></i>
                        Ticket Support
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
                        	<div class="card-title">Ticket Support</div>
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
														<th>Status</th>
														<th>Username</th>
														<th>Subject</th>
														<th>Message</th>
														<th>Date Received</th>
														<th>Last Update</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
<?php
// start paging config
if (isset($_GET['search'])) {
	$search = $_GET['search'];
	$query_list = "SELECT * FROM tickets WHERE user LIKE '%$search%' ORDER BY id DESC"; // edit
} else {
	$query_list = "SELECT * FROM tickets ORDER BY id DESC"; // edit
}
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page_no"])) {
	$starting_position = ($_GET["page_no"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query);
// end paging config
	while ($data_show = mysqli_fetch_assoc($new_query)) {
		if($data_show['status'] == "Closed") {
			$label = "danger";
		} else if($data_show['status'] == "Responded") {
			$label = "success";
		} else if($data_show['status'] == "Waiting") {
			$label = "info";
		} else {
			$label = "warning";
		}
?>

																	<tr>
														<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_show['status']; ?></span></td>
														<td><?php echo $data_show['user']; ?></td>
														<td><?php if($data_show['seen_admin'] == 0) { ?><label class="badge badge">NEW!</label><?php } ?> <?php echo $data_show['subject']; ?></td>
														<td><?php echo $data_show['message']; ?></td>
														<td><?php echo $data_show['datetime']; ?></td>
														<td><?php echo $data_show['last_update']; ?></td>
														<td align="center">
														<a href="<?php echo $cfg_baseurl; ?>/admin/tickets/reply.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-success"><i class="icon icon-reply"></i></a>
														<a href="<?php echo $cfg_baseurl; ?>/admin/tickets/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="icon icon-trash"></i></a>
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