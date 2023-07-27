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
					$post_web_title = htmlspecialchars($_POST['web_title']);
					$post_web_description = htmlspecialchars($_POST['web_description']);
					$post_seo_keywords = htmlspecialchars($_POST['seo_keywords']);
					$post_seo_meta = mysqli_real_escape_string($db, $_POST['seo_meta']);
					$post_seo_analytics = mysqli_real_escape_string($db, $_POST['seo_analytics']);
					$post_seo_chat = mysqli_real_escape_string($db, $_POST['seo_chat']);

					/* if (empty($post_web_title) || empty($post_web_description) || empty($post_seo_keywords) || empty($post_seo_meta) || empty($post_seo_analytics) || empty($post_seo_chat)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else { */
						$update_service = mysqli_query($db, "UPDATE settings SET web_title = '$post_web_title', web_description = '$post_web_description', seo_keywords = '$post_seo_keywords', seo_meta = '$post_seo_meta' , seo_analytics = '$post_seo_analytics', seo_chat = '$post_seo_chat'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "Successfully changed.";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.".mysqli_error($db);
						}
				
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				$title = "SEO Settings";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        SEO Settings
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
						<h4>SEO Settings</h4>
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
									<label class="col-md-2 control-label">Website Title</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="web_title" placeholder="Website Title" value="<?php echo $datadb_service['web_title']; ?>">
									<span class="help-block"></span>
									</div>
                                </div>
                                <div class="form-group">
									<label class="col-md-2 control-label">Website Description</label>
									<div class="col-md-10">
									<textarea type="text" class="form-control" name="web_description" placeholder="Website Description"><?php echo $datadb_service['web_description']; ?></textarea>
									<span class="help-block"></span>
									</div>
                                </div>
                                <div class="form-group">
									<label class="col-md-2 control-label">Website Keywords</label>
									<div class="col-md-10">
									<textarea type="text" class="form-control" name="seo_keywords" placeholder="Website Keywords"><?php echo $datadb_service['seo_keywords']; ?></textarea>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-10 control-label">Header Code (This code will be placed after &lt;head&gt; tag) <code>(SUPPORTS CODE)</code></label>
									<div class="col-md-10">
									<textarea type="text" class="form-control" name="seo_meta" placeholder="&lt;meta name=&quot;keywords&quot;&gt;"><?php echo $datadb_service['seo_meta']; ?></textarea>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Google Analytics Code <code>(SUPPORTS CODE)</code></label>
									<div class="col-md-10">
									<textarea type="text" class="form-control" name="seo_analytics" placeholder="Global site tag (gtag.js) - Google Analytics "><?php echo $datadb_service['seo_analytics']; ?></textarea>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Embed Code(chat) <code>(SUPPORTS CODE)</code></label>
									<div class="col-md-10">
									<textarea type="text" class="form-control" name="seo_chat" placeholder="Embed Code(chat)"><?php echo $datadb_service['seo_chat']; ?></textarea>
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