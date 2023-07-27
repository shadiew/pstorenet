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
		if (isset($_GET['sid'])) {
			$post_sid = $_GET['sid'];
			$checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				header("Location: ".$cfg_baseurl."admin/service/");
			} else {
				if (isset($_POST['edit'])) {
					$post_cat = htmlspecialchars($_POST['category']);
					$post_service = htmlspecialchars($_POST['service']);
					$post_note = mysqli_real_escape_string($db, htmlspecialchars($_POST['note']));
					$post_min = htmlspecialchars($_POST['min']);
					$post_max = htmlspecialchars($_POST['max']);
					$post_price = htmlspecialchars($_POST['price']);
					$post_price_provider = htmlspecialchars($_POST['price_provider']);
					$post_pid = htmlspecialchars($_POST['pid']);
					$post_provider = htmlspecialchars($_POST['provider']);
					$post_status = htmlspecialchars($_POST['status']);
					$post_update = htmlspecialchars($_POST['update']);

					if(empty($post_provider)){
						$post_provider = "MANUAL";
					}
					if ( empty($post_cat) || empty($post_service) || empty($post_min) || empty($post_max) || empty($post_price)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else if($post_provider != "MANUAL" AND (empty($post_price_provider) || empty($post_pid))){
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else if ($post_status != "Active" AND $post_status != "Not active") {
						$msg_type = "error";
						$msg_content = "Input Error Occurred.";
					} else {
						$post_pid = !empty($post_pid) ? "$post_pid" : "0";
						$post_price_provider = !empty($post_price_provider) ? "$post_price_provider" : "0";

						$update_service = mysqli_query($db, "UPDATE services SET category = '$post_cat', service = '$post_service', note = '$post_note', min = '$post_min', max = '$post_max', price = '$post_price', price_provider = '$post_price_provider', status = '$post_status', pid = '$post_pid', provider = '$post_provider' WHERE sid = '$post_sid'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_service<br /><b>Category:</b> $post_cat<br /><b>Note:</b> $post_note<br /><b>Min:</b> $post_min<br /><b>Max:</b> $post_max<br /><b>Price/1000:</b> $ $post_price<br /><b>Price Provider/1000:</b> $ $post_price_provider<br /><b>Provider ID:</b> $post_pid<br /><b>Status:</b> $post_status";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.";
						}
					}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				$title = "Change Service";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Change Service
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
						<h4>Change Service</h4>
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
									<label class="col-md-2 control-label">Service ID</label>
									<div class="col-md-10">
									<input type="text" class="form-control" placeholder="Service ID" value="<?php echo $datadb_service['sid']; ?>" readonly>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Service Name</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="service" placeholder="Service Name" value="<?php echo $datadb_service['service']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Category</label>
									<div class="col-md-10">
									<select class="form-control" name="category">
										<option value="<?php echo $datadb_service['category']; ?>"><?php echo $datadb_service['category']; ?> (Selected)</option>
										<?php
										$check_cat = mysqli_query($db, "SELECT * FROM service_cat ORDER BY name ASC");
										while ($data_cat = mysqli_fetch_assoc($check_cat)) {
										?>
										<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
										<?php
										}
										?>
									</select>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Note</label>
									<div class="col-md-10">
									<textarea type="text" class="form-control" name="note" placeholder="Etc: Input username, Input link"><?php echo $datadb_service['note']; ?></textarea>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Min Order</label>
									<div class="col-md-10">
									<input type="number" class="form-control" name="min" placeholder="Min Order" value="<?php echo $datadb_service['min']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Max Order</label>
									<div class="col-md-10">
									<input type="number" class="form-control" name="max" placeholder="Max Order" value="<?php echo $datadb_service['max']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Price/1000</label>
									<div class="col-md-10">
									<input type="number" class="form-control" name="price" placeholder="Price/1000" step="0.0001" value="<?php echo $datadb_service['price']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Price Provider/K</label>
									<div class="col-md-10">
									<input type="number" class="form-control" name="price_provider" placeholder="Price/1000" step="0.0001" value="<?php echo $datadb_service['price_provider']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Provider ID</label>
									<div class="col-md-10">
									<input type="number" class="form-control" name="pid" placeholder="Provider ID" value="<?php echo $datadb_service['pid']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Provider</label>
									<div class="col-md-10">
									<select class="form-control" id="name" name="provider">
														<option value="<?php
														$checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
														$datadb_provider = mysqli_fetch_assoc($checkdb_provider);
														echo $datadb_service['provider']; ?>">Selected [<?php echo $datadb_service['provider']; ?>]</option>
														<?php
														$checkdb_providerLoop = mysqli_query($db, "SELECT * FROM provider");
														while ($datadb_providerLoop = mysqli_fetch_assoc($checkdb_providerLoop)) {
														?>
														<option value="<?php echo $datadb_providerLoop['code']; ?>"><?php echo $datadb_providerLoop['code']; ?></option>
														<?php
														}
														?>
									</select>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Status</label>
									<div class="col-md-10">
									<select class="form-control" name="status">
										<option value="<?php echo $datadb_service['status']; ?>"><?php echo $datadb_service['status']; ?> (Selected)</option>
										<option value="Active">Active</option>
										<option value="Not active">Not active</option>
									</select>
									<span class="help-block"></span>
									</div>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/service/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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
			header("Location: ".$cfg_baseurl."/admin/service/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>