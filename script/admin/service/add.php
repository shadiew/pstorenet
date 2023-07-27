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
		$checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
		$datadb_provider = mysqli_fetch_assoc($checkdb_provider);

		$check_highest_sid = mysqli_query($db, "SELECT * FROM `services` ORDER BY `sid` DESC LIMIT 1");
    	$highest_sid = mysqli_fetch_array($check_highest_sid);
        $post_sid = $highest_sid['sid'] + 1;

		if (isset($_POST['add'])) {
			$post_cat = htmlspecialchars($_POST['category']);
			$post_service = htmlspecialchars($_POST['service']);
			$post_note = mysqli_real_escape_string($db, htmlspecialchars($_POST['note']));
			$post_min = htmlspecialchars($_POST['min']);
			$post_max = htmlspecialchars($_POST['max']);
			$post_price = htmlspecialchars($_POST['price']);
			$post_price_provider = htmlspecialchars($_POST['price_provider']);
			$post_pid = htmlspecialchars($_POST['pid']);
			$post_provider = htmlspecialchars($_POST['provider']);

			$checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			$checkdb_pid = mysqli_query($db, "SELECT * FROM services WHERE pid = '$post_pid'");
			$pid = $datadb_service['pid'];
			
			$cat = mysqli_query($db, "SELECT * FROM service_cat WHERE name = '$post_cat'");
			$data_cat = mysqli_fetch_assoc($cat);
			$type = $data_cat['type'];
			if ( empty($post_cat) || empty($post_service) || empty($post_min) || empty($post_max) || empty($post_price)) {
				$msg_type = "error";
				$msg_content = "Please Fill In All Inputs.";
			} else if($post_provider != "MANUAL" AND (empty($post_price_provider) || empty($post_pid))){
				$msg_type = "error";
				$msg_content = "Please Fill In All Inputs.";
			} else if (mysqli_num_rows($checkdb_service) > 0) {
				$msg_type = "error";
				$msg_content = "Service ID $post_sid Sudah Terdaftar.";
			} else {
					$post_pid = !empty($post_pid) ? "$post_pid" : "0";
					$post_price_provider = !empty($post_price_provider) ? "$post_price_provider" : "0";

					$insert_service = mysqli_query($db, "INSERT INTO history_update (sid, service, rate, status, date) VALUES ('$post_sid', '$post_service', '$post_price', 'New service', '$date')");
					$insert_service = mysqli_query($db, "INSERT INTO services (sid, category, service, note, min, max, price, price_provider, status, pid, provider) VALUES ('$post_sid', '$post_cat', '$post_service', '$post_note', '$post_min', '$post_max', '$post_price', '$post_price_provider', 'Active', '$post_pid', '$post_provider')");
					if ($insert_service == TRUE) {
						$msg_type = "success";
						$msg_content = "<b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_service<br /><b>Category:</b> $post_cat<br /><b>Note:</b> $post_note<br /><b>Min:</b> $post_min<br /><b>Max:</b> $post_max<br /><b>Price/1000:</b> $ $post_price<br /><b>Price Provider/K:</b> $ $post_price_provider<br /><b>Provider ID:</b> $post_pid";
					} else {
						$msg_type = "error";
						$msg_content = "A System Error Occurred. ".mysqli_error($db);
					}
			}
		}
	$title = "Add Services";
	include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-plus"></i>
                        Add Services
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
						<h4>Add Services</h4>
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
									<select class="form-control" name="category">
										<?php
										$check_cat = mysqli_query($db, "SELECT * FROM service_cat WHERE status = 'Active' ORDER BY name ASC");
										while ($data_cat = mysqli_fetch_assoc($check_cat)) {
										?>
										<option value="<?php echo $data_cat['name']; ?>"><?php echo $data_cat['name']; ?></option>
										<?php
										}
										?>
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Service ID</label>
									<input type="number" name="sid" class="form-control" placeholder="Service ID" value="<?php echo $post_sid; ?>" disabled>
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Service Name</label>
									<input type="text" name="service" class="form-control" placeholder="Service Name">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Note</label>
									<textarea type="text" name="note" class="form-control" placeholder="Etc: Input username, Input link"></textarea>
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Min Order</label>
									<input type="number" name="min" class="form-control" placeholder="Min Order">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Max Order</label>
									<input type="number" name="max" class="form-control" placeholder="Min Order">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Price/1000</label>
									<input type="number" name="price" class="form-control" step="0.0001" placeholder="Etc: 30000">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Price Provider/K</label>
									<input type="number" name="price_provider" class="form-control" step="0.0001" placeholder="Etc: 10000">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Provider ID</label>
									<input type="text" name="pid" class="form-control" step="0.0001" placeholder="Provider ID">
									<span class="help-block"></span>
								</div>
								<div class="form-group">
									<label class="control-label">Provider Code</label>
									<select class="form-control" id="name" name="provider">
														<option value="<?php
														$checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
														$datadb_provider = mysqli_fetch_assoc($checkdb_provider);
														echo $datadb_provider['code']; ?>">Selected [<?php echo $datadb_provider['code']; ?>]</option>
														<?php
														while ($datadb_providerLoop = mysqli_fetch_assoc($checkdb_provider)) {
														?>
														<option value="<?php echo $datadb_providerLoop['code']; ?>"><?php echo $datadb_providerLoop['code']; ?></option>
														<?php
														}
														?>
									</select>
									<span class="help-block"></span>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/service/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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