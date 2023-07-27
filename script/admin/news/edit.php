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
			$checkdb_news = mysqli_query($db, "SELECT * FROM news WHERE id = '$post_id'");
			$datadb_news = mysqli_fetch_assoc($checkdb_news);
			if (mysqli_num_rows($checkdb_news) == 0) {
				header("Location: ".$cfg_baseurl."admin/news/");
			} else {
				if (isset($_POST['edit'])) {
					$post_content = htmlspecialchars($_POST['content']);
					$post_status = htmlspecialchars($_POST['status']);
					if (empty($post_content) || empty($post_status)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else {
						$update_news = mysqli_query($db, "UPDATE news SET content = '$post_content', status = '$post_status' WHERE id = '$post_id'");
						if ($update_news == TRUE) {
							$msg_type = "success";
							$msg_content = "News Altered.";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.";
						}
					}
				}
				$checkdb_news = mysqli_query($db, "SELECT * FROM news WHERE id = '$post_id'");
				$datadb_news = mysqli_fetch_assoc($checkdb_news);
				$title = "Change News";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Change News
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
						<h4>Change News</h4>
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
												<label class="control-label">Category</label>
												<div>
													<select class="form-control" name="status">
														<option value="<?php echo $datadb_news['status']; ?>">[ SELECTED ] <?php echo $datadb_news['status']; ?></option>
														<option value="SERVICE">SERVICE</option>
														<option value="MAINTENANCE">MAINTENANCE</option>
														<option value="UPDATE">UPDATE</option>
														<option value="INFO">INFO</option>
													</select>
													<span class="help-block"></span>
												</div>
											</div>
                                <div class="form-group">
									<label class="control-label">Content</label>
									<textarea name="content" class="form-control" placeholder="Content"><?php echo $datadb_news['content']; ?></textarea>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/news/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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
			header("Location: ".$cfg_baseurl."/admin/news/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>