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
			$post_id = $_GET['id'];
			$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE id = '$post_id'");
			$datadb_staff = mysqli_fetch_assoc($checkdb_staff);
			if (mysqli_num_rows($checkdb_staff) == 0) {
				header("Location: ".$cfg_baseurl."admin/staff/");
			} else {
				if (isset($_POST['edit'])) {
					$post_name = htmlspecialchars($_POST['name']);
			        $post_facebook = htmlspecialchars($_POST['facebook']);
		        	$post_nomor = htmlspecialchars($_POST['nomor']);
			        $post_instagram = htmlspecialchars($_POST['instagram']);
		        	$post_line = htmlspecialchars($_POST['line']);
		        	$post_level = htmlspecialchars($_POST['level']);
					if (empty($post_name) || empty($post_facebook) || empty($post_nomor) || empty($post_instagram)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else {
						$update_staff = mysqli_query($db, "UPDATE staff SET name = '$post_name', facebook = '$post_facebook', nomor = '$post_nomor', instagram = '$post_instagram', level = 'Developer' WHERE id = '$post_id'");
						if ($update_staff == TRUE) {
							$msg_type = "success";
							$msg_content = "<br /><b>Name:</b> $post_name<br /><b>Facebook:</b> $post_facebook<br /><b>Phone Number:</b> $post_nomor<br /><b>Instagram:</b> $post_instagram";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.";
						}
					}
				}
				$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE id = '$post_id'");
				$datadb_staff = mysqli_fetch_assoc($checkdb_staff);
				$title = "Change Staff";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Change Staff
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
						<h4>Change Staff</h4>
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
                                <div class="form-group">
									<span class="help-block"></span>
									<label class="control-label">Name</label>
									<input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo $datadb_staff['name']; ?>">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Facebook</label>
									<input type="text" name="facebook" class="form-control" placeholder="Facebook" value="<?php echo $datadb_staff['facebook']; ?>">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Whatsapp Number</label>
									<input type="number" name="nomor" class="form-control" placeholder="Whatsapp Number" value="<?php echo $datadb_staff['nomor']; ?>">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Instagram</label>
									<input type="text" name="instagram" class="form-control" placeholder="Username Instagram" value="<?php echo $datadb_staff['instagram']; ?>">
									<span class="help-block"></span>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/staff/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
								<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light"><i class="icon icon-refresh"></i> Reset</button>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="edit"><i class="icon icon-send"></i> Edit</button>
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
			header("Location: ".$cfg_baseurl."/admin/services.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>