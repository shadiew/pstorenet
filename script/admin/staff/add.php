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
		if (isset($_POST['add'])) {
			$post_name = htmlspecialchars($_POST['name']);
			$post_facebook = htmlspecialchars($_POST['facebook']);
			$post_nomor = htmlspecialchars($_POST['nomor']);
			$post_instagram = htmlspecialchars($_POST['instagram']);
			$post_line = htmlspecialchars($_POST['line']);
			$post_level = htmlspecialchars($_POST['level']);

			$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE name = '$post_name'");
			$datadb_staff = mysqli_fetch_assoc($checkdb_staff);
			if (empty($post_name) || empty($post_nomor)) {
				$msg_type = "error";
				$msg_content = "Please Fill In All Inputs.";
			} else if (mysqli_num_rows($checkdb_staff) > 0) {
				$msg_type = "error";
				$msg_content = "Staff Registered.";
			} else {
				$insert_staff = mysqli_query($db, "INSERT INTO staff (name, facebook, nomor, instagram, level) VALUES ('$post_name', '$post_facebook', '$post_nomor', '$post_instagram', 'Developer')");
				if ($insert_staff == TRUE) {
					$msg_type = "success";
					$msg_content = "<br /><b>Name:</b> $post_name<br /><b>Facebook:</b> $post_facebook<br /><b>Phone Number:</b> $post_nomor<br /><b>Instagram:</b> $post_instagram";
				} else {
					$msg_type = "error";
					$msg_content = "A System Error Occurred.";
				}
			}
		}
	$title = "Add Staff";
	include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-plus"></i>
                        Add Staff
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
						<h4>Add Staff</h4>
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
									<label class="control-label">Name</label>
									<input type="text" name="name" class="form-control" placeholder="Name">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Facebook</label>
									<input type="text" name="facebook" class="form-control" placeholder="Facebook">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Whatsapp Number</label>
									<input type="number" name="nomor" class="form-control" placeholder="Whatsapp Number">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Instagram</label>
									<input type="text" name="instagram" class="form-control" placeholder="Username Instagram">
									<span class="help-block"></span>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/staff/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
								<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light"><i class="icon icon-refresh"></i> Reset</button>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="add"><i class="icon icon-send"></i> Add</button>
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
	header("Location: ".$cfg_baseurl);
}
?>