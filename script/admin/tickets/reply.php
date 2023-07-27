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
		if (isset($_GET['id'])) {
			$post_target = $_GET['id'];
			$check_ticket = mysqli_query($db, "SELECT * FROM tickets WHERE id = '$post_target'");
			$data_ticket = mysqli_fetch_array($check_ticket);
			if (mysqli_num_rows($check_ticket) == 0) {
				header("Location: ".$cfg_baseurl."admin/tickets/");
			} else {
				mysqli_query($db, "UPDATE tickets SET seen_admin = '1' WHERE id = '$post_target'");
				if (isset($_POST['submit'])) {
					$post_message = htmlspecialchars($_POST['message']);
					if ($data_ticket['status'] == "Closed") {
						$msg_type = "error";
						$msg_content = "Ticket has been closed.";
					} else if (empty($post_message)) {
						$msg_type = "error";
						$msg_content = "Please Fill in All Inputs";
					} else if (strlen($post_message) > 500) {
						$msg_type = "error";
						$msg_content = "Maximum message is 500 characters.";
					} else {
						$last_update = "$date $time";
						$insert_ticket = mysqli_query($db, "INSERT INTO tickets_message (ticket_id, sender, user, username_sender, message, datetime, ip) VALUES ('$post_target', 'Admin', '$data_ticket[user]', '$sess_username', '$post_message', '$last_update', '$ip')");
						$update_ticket = mysqli_query($db, "UPDATE tickets SET last_update = '$last_update', seen_user = '0', status = 'Responded' WHERE id = '$post_target'");
						if ($insert_ticket == TRUE) {
							$msg_type = "success";
							$msg_content = "Ticket has been returned.";
						} else {
							$msg_type = "error";
							//$msg_content = "System error.";
							$msg_content = mysqli_error();

						}
					}
				}
				$check_ticket = mysqli_query($db, "SELECT * FROM tickets WHERE id = '$post_target'");
				$data_ticket = mysqli_fetch_array($check_ticket);
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                         <i class="icon icon-email"></i>
                        Reply Tickets
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
						<h4>Reply Tickets</h4>
                           <div class="panel-body">
                                        <?php 
										if ($msg_type == "success") {
										?>
										<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h4><i class="fa fa-check-circle"></i> <strong>Success!</strong></h4>
											<p><?php echo $msg_content; ?></p>
                                        </div>
										<?php
										} else if ($msg_type == "error") {
										?>
										<div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h4><i class="fa fa-times-circle"></i> <strong>Ups!</strong></h4>
											<p><?php echo $msg_content; ?></p>
                                        </div>
										<?php
										}
										?>
										<div class="ticket-box">
											<div class="alert alert-info alert-white">
												<b><?php echo $data_ticket['user']; ?></b><br /><?php echo nl2br($data_ticket['message']); ?><br /><i class="text-muted font-size-10"><?php echo $data_ticket['datetime']; ?></i>
											</div>
<?php
$check_message = mysqli_query($db, "SELECT * FROM tickets_message WHERE ticket_id = '$post_target' ORDER BY `datetime` ASC");
while ($data_message = mysqli_fetch_array($check_message)) {
	if ($data_message['sender'] == "Admin") {
		$msg_alert = "success";
		$msg_text = "text-right";
		$msg_sender = $data_message['sender'];
	} else {
		$msg_alert = "info";
		$msg_text = "";
		$msg_sender = $data_message['user'];
	}
?>
											<div class="alert alert-<?php echo $msg_alert; ?> alert-white <?php echo $msg_text; ?>">
												<b><?php echo $msg_sender; ?></b><br /><?php echo nl2br($data_message['message']); ?><br /><i class="text-muted font-size-10"><?php echo $data_message['datetime']; ?></i>
											</div>
<?php
}
?>
										</div>
									</div>
									<div class="panel-footer">
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<div class="col-md-12">
													<textarea name="message" class="form-control" placeholder="Message" rows="3"></textarea>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>/admin/tickets/" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Back</a>
											<button type="submit" class="pull-right btn btn-success" name="submit">Reply</button>
										</form>
										<div class="clearfix"></div>
									</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
				include("../lib/footer.php");
			}
		} else {
			header("Location: ".$cfg_baseurl."/admin/tickets/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>