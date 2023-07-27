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
			$checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_id'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				header("Location: ".$cfg_baseurl."/admin/depo/");
			} else {
				if (isset($_POST['edit'])) {
					$post_name = htmlspecialchars($_POST['name']);
					$post_data = htmlspecialchars($_POST['data']);
					$post_note = htmlspecialchars($_POST['note']);
					$post_rate = htmlspecialchars($_POST['rate']);
					$post_kategori = htmlspecialchars($_POST['kategori']);
					$post_Active = htmlspecialchars($_POST['Active']);
					$post_id = htmlspecialchars($_POST['id']);
					
					if (empty($post_name) || empty($post_data) || empty($post_note)|| empty($post_rate)) {
						$msg_type = "error";
						$msg_content = "Please Fill In All Inputs.";
					} else if ($post_name == null) {
						$msg_type = "error";
						$msg_content = "Input Error Occurred.";
					} else {
						$update_service = mysqli_query($db, "UPDATE deposit_method SET name = '$post_name', data = '$post_data', note = '$post_note', rate = '$post_rate', Active = '$post_Active' where id='$post_id'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "Deposit Method Changed.";
						} else {
							$msg_type = "error";
							$msg_content = "A System Error Occurred.";
						}
					}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM deposit_method WHERE id = '$post_id'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				$title = "Change Deposit Method";
				include("../../lib/header_admin.php");
?>
    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-edit"></i>
                        Change Deposit Method
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
						<h4>Change Deposit Method</h4>
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
									<label class="col-md-2 control-label">ID Deposit</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="id" placeholder="ID Deposit" value="<?php echo $datadb_service['id']; ?>" readonly>
									<span class="help-block"></span>
									</div>
								</div>
	                                    <div class="form-group">
											    <span class="help-block"></span>
												<label class="col-md-2 control-label">Provider</label>
												<div class="col-md-10">
												<input type="text" class="form-control" id="name" name="name" placeholder="Provider" value="<?php echo $datadb_service['name']; ?>">
													<!-- <select class="form-control" id="name" name="name">
														<option value="<?php echo $datadb_service['name']; ?>">Selected [<?php echo $datadb_service['name']; ?>]</option>
														<?php
														$check_cat = mysqli_query($db, "SELECT * FROM deposit_method ORDER BY name ASC");
														while ($data_cat = mysqli_fetch_assoc($check_cat)) {
														?>
														<option value="<?php echo $data_cat['name']; ?>"><?php echo $data_cat['name_method']; ?></option>
														<?php
														}
														?>
													</select> -->
													<span class="help-block"></span>
												</div>
											</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Target</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="data" placeholder="PAYTM 11111111111 XXXXXXXX" value="<?php echo $datadb_service['data']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-2 control-label">Note</label>
									<div class="col-md-10">
									<input type="text" class="form-control" name="note" placeholder="Note" value="<?php echo $datadb_service['note']; ?>">
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Rate</label>
									<div class="col-md-10">
									<select name="rate" class="form-control">
											<option value="<?php echo $datadb_service['rate']; ?>" selected><?php echo $datadb_service['rate']; ?>% (Selected)</option>
                                            <option value="0">0%</option>
                                            <option value="1">1%</option>
                                            <option value="2">2%</option>
                                            <option value="3">3%</option>
                                            <option value="4">4%</option>
                                            <option value="5">5%</option>
                                            <option value="6">6%</option>
                                            <option value="7">7%</option>
                                            <option value="8">8%</option>
                                            <option value="9">9%</option>
                                            <option value="10">10%</option>
                                            <option value="11">11%</option>
                                            <option value="12">12%</option>
                                            <option value="13">13%</option>
                                            <option value="14">14%</option>
                                            <option value="15">15%</option>
                                            <option value="16">16%</option>
                                            <option value="17">17%</option>
                                            <option value="18">18%</option>
                                            <option value="19">19%</option>
                                            <option value="20">20%</option>
                                            <option value="21">21%</option>
                                            <option value="22">22%</option>
                                            <option value="23">23%</option>
                                            <option value="24">24%</option>
                                            <option value="25">25%</option>
                                            <option value="26">26%</option>
                                            <option value="27">27%</option>
                                            <option value="28">28%</option>
                                            <option value="29">29%</option>
                                            <option value="30">30%</option>
                                            <option value="31">31%</option>
                                            <option value="32">32%</option>
                                            <option value="33">33%</option>
                                            <option value="34">34%</option>
                                            <option value="35">35%</option>
                                            <option value="36">36%</option>
                                            <option value="37">37%</option>
                                            <option value="38">38%</option>
                                            <option value="39">39%</option>
                                            <option value="40">40%</option>
                                            <option value="41">41%</option>
                                            <option value="42">42%</option>
                                            <option value="43">43%</option>
                                            <option value="44">44%</option>
                                            <option value="45">45%</option>
                                            <option value="46">46%</option>
                                            <option value="47">47%</option>
                                            <option value="48">48%</option>
                                            <option value="49">49%</option>
                                            <option value="50">50%</option>
                                            <option value="51">51%</option>
                                            <option value="52">52%</option>
                                            <option value="53">53%</option>
                                            <option value="54">54%</option>
                                            <option value="55">55%</option>
                                            <option value="56">56%</option>
                                            <option value="57">57%</option>
                                            <option value="58">58%</option>
                                            <option value="59">59%</option>
                                            <option value="60">60%</option>
                                            <option value="61">61%</option>
                                            <option value="62">62%</option>
                                            <option value="63">63%</option>
                                            <option value="64">64%</option>
                                            <option value="65">65%</option>
                                            <option value="66">66%</option>
                                            <option value="67">67%</option>
                                            <option value="68">68%</option>
                                            <option value="69">69%</option>
                                            <option value="70">70%</option>
                                            <option value="71">71%</option>
                                            <option value="72">72%</option>
                                            <option value="73">73%</option>
                                            <option value="74">74%</option>
                                            <option value="75">75%</option>
                                            <option value="76">76%</option>
                                            <option value="77">77%</option>
                                            <option value="78">78%</option>
                                            <option value="79">79%</option>
                                            <option value="80">80%</option>
                                            <option value="81">81%</option>
                                            <option value="82">82%</option>
                                            <option value="83">83%</option>
                                            <option value="84">84%</option>
                                            <option value="85">85%</option>
                                            <option value="86">86%</option>
                                            <option value="87">87%</option>
                                            <option value="88">88%</option>
                                            <option value="89">89%</option>
                                            <option value="90">90%</option>
                                            <option value="91">91%</option>
                                            <option value="92">92%</option>
                                            <option value="93">93%</option>
                                            <option value="94">94%</option>
                                            <option value="95">95%</option>
                                            <option value="96">96%</option>
                                            <option value="97">97%</option>
                                            <option value="98">98%</option>
                                            <option value="99">99%</option>
                                            <option value="100">100%</option>
									</select>
									<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Status</label>
									<div class="col-md-10">
										<select class="form-control" id="Active" name="Active">
														<option value="<?php echo $datadb_service['Active']; ?>">Selected [<?php echo $datadb_service['Active']; ?>]</option>
														<option value="YES">YES</option>
														<option value="NO">NO</option>
													</select>
									<span class="help-block"></span>
									</div>
								</div>
								<a href="<?php echo $cfg_baseurl; ?>/admin/depo/" class="btn btn-info btn-bordered waves-effect w-md waves-light"><i class="icon icon-arrow-circle-left"></i> Back</a>
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
			header("Location: ".$cfg_baseurl."/admin/depo/");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>