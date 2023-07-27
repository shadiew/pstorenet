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
			$checkdb_service = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				$msg_type = "error";
				$msg_content = "Contact Support! There is a problem in your database";
			} else {
				if (isset($_POST['edit'])) {
					$post_web_name = htmlspecialchars($_POST['web_name']);
					$post_web_slogan = mysqli_real_escape_string($db, $_POST['web_slogan']);
					$post_link_logo = htmlspecialchars(($_POST['link_logo']));
					$post_link_logo_dark = htmlspecialchars($_POST['link_logo_dark']);
					$post_link_fav = htmlspecialchars($_POST['link_fav']);
					$post_web_copyright = htmlspecialchars($_POST['web_copyright']);

					$post_seo_link_fb = htmlspecialchars($_POST['seo_link_fb']);
					$post_seo_link_insta = htmlspecialchars($_POST['seo_link_insta']);
					$post_seo_link_tweet = htmlspecialchars($_POST['seo_link_tweet']);

					if (empty($post_web_name) || empty($post_web_slogan) || empty($post_link_logo) || empty($post_link_logo_dark) || empty($post_link_fav)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else {
						$update_service = mysqli_query($db, "UPDATE settings SET web_name = '$post_web_name', web_slogan = '$post_web_slogan', link_logo = '$post_link_logo', link_logo_dark = '$post_link_logo_dark' , link_fav = '$post_link_fav', web_copyright = '$post_web_copyright', seo_link_fb = '$post_seo_link_fb', seo_link_insta = '$post_seo_link_insta', seo_link_tweet = '$post_seo_link_tweet'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "Successfully changed.";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.".mysqli_error($db);
						}
					}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				$title = "General Settings";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        General Settings
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
						<h4>General Settings</h4>
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
									<label class="col-md-2 control-label">Website Name</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="web_name" placeholder="Website Name" value="<?php echo $datadb_service['web_name']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Website Slogan <code>(SUPPORTS CODE)</code></label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="web_slogan" placeholder="Website Slogan" value="<?php echo $datadb_service['web_slogan']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Logo Link (Light)</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="link_logo" placeholder="https://" value="<?php echo $datadb_service['link_logo']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Logo Link (Dark)</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="link_logo_dark" placeholder="https://" value="<?php echo $datadb_service['link_logo_dark']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Link Favicon</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="link_fav" placeholder="https://" value="<?php echo $datadb_service['link_fav']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Copyright Text</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="web_copyright" placeholder="© 2020" value="<?php echo $datadb_service['web_copyright']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Facebook Link</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="seo_link_fb" placeholder="Facebook Link" value="<?php echo $datadb_service['seo_link_fb']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Instagram Link</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="seo_link_insta" placeholder="Instagram Link" value="<?php echo $datadb_service['seo_link_insta']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Twitter Link</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="seo_link_tweet" placeholder="Twitter Link" value="<?php echo $datadb_service['seo_link_tweet']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
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
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>