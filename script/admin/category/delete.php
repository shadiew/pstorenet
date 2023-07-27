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
			$id = $_GET['id'];
			$checkdb_user = mysqli_query($db, "SELECT * FROM service_cat WHERE id = '$id'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (mysqli_num_rows($checkdb_user) == 0) {
				header("Location: ".$cfg_baseurl."/admin/category/");
			} else {
				$title = "Delete Category";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-trash"></i>
                        Delete Category
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
						<h4>Delete Category</h4>
                            <form role="form" method="POST" action="<?php echo $cfg_baseurl; ?>/admin/category/">
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
									<label class="control-label">ID</label>
									<input type="text" class="form-control" name="id" placeholder="ID" value="<?php echo $datadb_user['id']; ?>" readonly>
								</div>
                                <div class="form-group">
									<label class="control-label">Category</label>
									<input type="text" class="form-control" name="category" placeholder="Name" value="<?php echo $datadb_user['name']; ?>" readonly>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/category/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
						        <button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="delete"><i class="icon icon-trash"></i> Delete</button>
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
			header("Location: ".$cfg_baseurl."/admin/category/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>