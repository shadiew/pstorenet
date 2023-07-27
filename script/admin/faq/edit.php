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
			$checkdb_faq = mysqli_query($db, "SELECT * FROM faq WHERE id = '$post_id'");
			$datadb_faq = mysqli_fetch_assoc($checkdb_faq);
			if (mysqli_num_rows($checkdb_faq) == 0) {
				header("Location: ".$cfg_baseurl."admin/faq/");
			} else {
				if (isset($_POST['edit'])) {
					$post_question = mysqli_real_escape_string($db,htmlspecialchars($_POST['question']));
					$post_answer = mysqli_real_escape_string($db,htmlspecialchars($_POST['answer']));

					if (empty($post_question) || empty($post_answer)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else {
						$update_staff = mysqli_query($db, "UPDATE faq SET question = '$post_question', answer = '$post_answer' WHERE id = '$post_id'");
						if ($update_staff == TRUE) {
							$msg_type = "success";
							$msg_content = "<br /><b>Question:</b> $post_question<br /><b>Answer:</b> $post_answer";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.";
						}
					}
				}
				$checkdb_faq = mysqli_query($db, "SELECT * FROM faq WHERE id = '$post_id'");
				$datadb_faq = mysqli_fetch_assoc($checkdb_faq);
				$title = "Edit FAQ";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Edit FAQ
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
						<h4>Edit FAQ</h4>
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
									<label class="control-label">Question</label>
									<input type="text" name="question" class="form-control" placeholder="Question" value="<?php echo $datadb_faq['question']; ?>">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Answer</label>
									<textarea type="text" name="answer" class="form-control" placeholder="Answer"><?php echo $datadb_faq['answer']; ?></textarea>
									<span class="help-block"></span>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/faq/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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