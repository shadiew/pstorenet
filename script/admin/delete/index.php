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
		if (isset($_POST['delete'])) {
			$post_code = $_POST['code'];
            $post_delCat = $_POST['delCat'];
            
            if($post_code == "DELETE"){
                $del = mysqli_query($db, "DELETE FROM `services`");
                $del2 = mysqli_query($db, "ALTER TABLE services AUTO_INCREMENT = 1");
                if($del == TRUE){
                    $msg_type = "success";
                    $msg_content = "Services Deleted Successfully.";
                }else{
                    $msg_type = "error";
                    $msg_content = "Failed To Delete Services.";
                }
                if($post_delCat == "on"){
                   $del2 = mysqli_query($db, "DELETE FROM `service_cat`");
                   if($del2 == TRUE){
                    $msg_content .= " Categories Deleted Successfully.";
                    }else{
                        $msg_content .= " Failed To Delete Categories.";
                    }
                }
               }else{
                $msg_type = "error";
                $msg_content = 'Wrong confirmation code. Type "delete" in uppercase.';
               }
		    }
	$title = "Delete All Services";
	include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-delete_forever"></i>
                        Delete All Services
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
						<h4>Delete All Services</h4>
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
									<label class="control-label">Type "delete" in uppercase for confirmation. This will also delete all categories.</label>
									<input type="text" name="code" class="form-control" placeholder="Confirm Please">
									<span class="help-block"></span>
								</div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" name="delCat" type="checkbox"> Delete categories also.
                                        </label>
                                    </div>
                                </div>
								<a href="<?php echo $cfg_baseurl; ?>" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="delete"><i class="icon icon-delete_forever"></i> Delete</button>
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