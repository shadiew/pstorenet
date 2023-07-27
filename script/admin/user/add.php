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
		    $post_email = htmlspecialchars(trim($_POST['email']));
			$post_username = htmlspecialchars(trim($_POST['username']));
			$post_password = htmlspecialchars($_POST['password']);
			$post_balance = htmlspecialchars($_POST['balance']);
			$post_level = htmlspecialchars($_POST['level']);

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			$checkdb_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$post_email'");
			$datadb_email = mysqli_fetch_assoc($checkdb_email);
			if (empty($post_email) || empty($post_username) || empty($post_password)) {
				$msg_type = "error";
				$msg_content = "Please Fill In All Inputs.";
			} else if ($post_level != "Member" AND $post_level != "Reseller" AND $post_level != "Admin" AND $post_level != "Agen") {
				$msg_type = "error";
				$msg_content = "Input Error Occurred.";
			} else if (mysqli_num_rows($checkdb_email) > 0) {
				$msg_type = "error";
				$msg_content = "Email is Registered.";
			} else if (mysqli_num_rows($checkdb_user) > 0) {
				$msg_type = "error";
				$msg_content = "User is Registered.";
			} else {
				$post_api = random(20);
				$insert_user = mysqli_query($db, "INSERT INTO users (email, username, password, balance, level, registered, status, api_key, uplink) VALUES ('$post_email', '$post_username', '$post_password', '$post_balance', '$post_level', '$date', 'Active', '$post_api', '$sess_username')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Email:</b> $post_email<br /><b>Username:</b> $post_username<br /><b>Password:</b> $post_password<br /><b>Level:</b> $post_level<br /><b>Balance:</b> $ ".number_format($post_balance,0,',','.');
				} else {
					$msg_type = "error";
					$msg_content = "A System Error Occurred.";
				}
			}
		}
	$title = "Add User";
	include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-user-plus"></i>
                        Add User
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
						<h4>Add User</h4>
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
									<label class="control-label">Level</label>
									<select class="form-control" name="level">
										<option value="Member">Member</option>
										<option value="Developers">Developer</option>
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Email</label>
									<input type="email" name="email" class="form-control" placeholder="Email">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Username</label>
									<input type="text" name="username" class="form-control" placeholder="Username">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Password</label>
									<input type="text" name="password" class="form-control" placeholder="Password">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Balance</label>
									<input type="number" name="balance" class="form-control" placeholder="Balance">
									<span class="help-block"></span>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/user/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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