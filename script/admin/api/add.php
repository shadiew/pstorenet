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
			$post_code = htmlspecialchars($_POST['code']);
			$post_link = htmlspecialchars($_POST['link']);
			$post_api_key = htmlspecialchars($_POST['api_key']);

			$checkdb_user = mysqli_query($db, "SELECT * FROM provider WHERE code = '$post_code'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
		    if (mysqli_num_rows($checkdb_user) > 0) {
				$msg_type = "error";
				$msg_content = "Provider is Registered.";
			} else {
				$insert_user = mysqli_query($db, "INSERT INTO provider (code, link, api_key) VALUES ('$post_code', '$post_link', '$post_api_key')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "Provider has been added";
				} else {
					$msg_type = "error";
					$msg_content = "A System Error Occurred.";
				}
			}
		}
	$title = "Add Provider";
	include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-user-plus"></i>
                       Add Provider
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
						<h4>Add Provider</h4>
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
									<input type="text" name="code" class="form-control" placeholder="Name">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">API Link</label>
									<input type="text" name="link" class="form-control" placeholder="Link">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">API Key</label>
									<input type="text" name="api_key" class="form-control" placeholder="API Key">
									<span class="help-block"></span>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/api/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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