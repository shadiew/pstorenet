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
					$post_terms_ins = mysqli_real_escape_string($db, htmlspecialchars_decode($_POST['terms_ins']));
					$post_privacy_ins = mysqli_real_escape_string($db, htmlspecialchars_decode($_POST['privacy_ins']));
					$post_terms_on = htmlspecialchars($_POST['terms_on']);
					$post_privacy_on = htmlspecialchars($_POST['privacy_on']);

						$update_service = mysqli_query($db, "UPDATE settings SET terms_ins='$post_terms_ins', privacy_ins='$post_privacy_ins', terms_on = '$post_terms_on', privacy_on = '$post_privacy_on'");
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
				$title = "Edit Pages";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Edit Pages
                    </h4>
                </div>
            </div>
        </div>
	</header>
	
	<link rel='stylesheet' href='https://cdn.quilljs.com/1.3.6/quill.snow.css'>

    <div class="animatedParent animateOnce">
        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body b-b">
						<h4>Edit Pages</h4>
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
									<h5 class="col-md-4 control-label">Terms & Conditions Page</h5>
									<label class="col-md-2 control-label">Status (ON/OFF)</label>
									<div class="col-md-10">
										<select class="form-control" id="terms_on" name="terms_on">
											<option value="<?php echo $datadb_service['terms_on']; ?>">Selected [<?php echo $datadb_service['terms_on']; ?>]</option>
											<option value="ON">ON</option>
											<option value="OFF">OFF</option>
										</select>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Content</label>
									<div class="col-md-10">
									<input name="terms_ins" type="hidden">
									<div id="editor-terms_ins" class="height-250">
									<?php echo $datadb_service[terms_ins]; ?>
									</div>
									<span class="help-block"></span>
									</div>
								</div><hr><br>
								<div class="form-group">
									<h5 class="col-md-4 control-label">Privacy Policy Page</h5>
									<label class="col-md-2 control-label">Status (ON/OFF)</label>
									<div class="col-md-10">
										<select class="form-control" id="privacy_on" name="privacy_on">
											<option value="<?php echo $datadb_service['privacy_on']; ?>">Selected [<?php echo $datadb_service['privacy_on']; ?>]</option>
											<option value="ON">ON</option>
											<option value="OFF">OFF</option>
										</select>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Content</label>
									<div class="col-md-10">
									<input name="privacy_ins" type="hidden">
									<div id="editor-privacy_ins" class="height-250">
									<?php echo $datadb_service[privacy_ins]; ?>
									</div>
									<span class="help-block"></span>
									</div>
								</div><hr><br>
								<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light"><i class="icon icon-refresh"></i> Reset</button>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="edit"><i class="icon icon-send"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script type="text/javascript" src="../../js/pages.js"></script>

<?php
				include("../../lib/footer.php");
			}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>