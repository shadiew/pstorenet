<?php
require("../lib/mainconfig.php");

if (isset($_POST['custom'])) {
	$post_sid = mysqli_real_escape_string($db, $_POST['custom']);
	$check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid' AND status = 'Active'");
	if (mysqli_num_rows($check_service) == 1) {
		$data_service = mysqli_fetch_assoc($check_service);
		$servicename = $data_service['service'];
		$kata = 'Likes Komentar';
		$run = strpos($kata, $servicename);
		$nama_service = $data_service['service'];
		function RemoveSpecialChar($nama_service)
		{
			$result  = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $nama_service);

			return $result;
		}
		if (preg_match_all('/^(?=.*Likes)(?=.*Komentar)/i', $data_service['service'])) {
?>
			<div class="form-group">
				<div class="form-group">
					<label class="control-label">Target / Link</label>
					<div>
						<input type="text" name="custom_link" class="form-control" placeholder="Link/Target">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Username</label>
					<div>
						<input type="text" name="link" class="form-control" placeholder="Username">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Quantity</label>
					<div>
						<input type="number" name="quantity" class="form-control" placeholder="Quantity" onkeyup="get_total(this.value).value;">
					</div>
				</div>
				<input type="hidden" id="rate" value="0">
					<div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input
                          id="total" value="0" disabled
                          type="number"
                          class="form-control"
                          placeholder="Amount"
                          aria-label="Amount (to the nearest dollar)" />
                        <span class="input-group-text">.00</span>
                     </div>


				

			<?php } else	if (preg_match_all('/^(?=.*Mentions)(?=.*User)/i', $data_service['service'])) {
			?>
				
					
				
					<div class="row g-3">
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-first-name">Target / Link</label>
	                      <input type="text" name="custom_link" class="form-control" placeholder="Link/Target" />
	                    </div>
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-last-name">Username</label>
	                      <input type="text" name="link" class="form-control" placeholder="Username" />
	                    </div>
	                </div>

	                <div class="mb-3">
                          <label class="form-label">Quantity</label>
                          <input type="number"name="quantity" class="form-control"  placeholder="0" onkeyup="get_total(this.value).value;" />
                    </div>



					<input type="hidden" id="rate" value="0">
					<div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input
                          id="total" value="0" disabled
                          type="number"
                          class="form-control"
                          placeholder="Amount"
                          aria-label="Amount (to the nearest dollar)" />
                        <span class="input-group-text">.00</span>
                     </div>

				<?php } else if (preg_match_all('/^(?=.*Mentions)(?=.*Custom)/i', $data_service['service'])) { ?>

					<div class="row g-3">
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-first-name">Target</label>
	                      <input type="text" name="link" class="form-control" placeholder="Link/Target" />
	                    </div>
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-last-name">Quantity</label>
	                      <input type="text" class="form-control" name="quantity" id="jumlah" readonly placeholder="0" />
	                    </div>

                	</div>


					<div class="mb-3">
                          <label class="form-label">Comment Data</label>
                          <textarea
                          	name="custom_mentions"
                            id="comments"
                            class="form-control"
                            placeholder="Hi, Do you have a moment to talk Joe?"
                            onkeyup="get_count(this.value).value;"></textarea>
                        </div>


					
					<div class="form-group">
						<label class="control-label">Quantity</label>
						<div>
							<div class="input-group"><span class="input-group-addon"></span>
								<input type="number" class="form-control" name="quantity" id="jumlah" readonly>
							</div>
						</div>
					</div>
					<input type="hidden" id="rate" value="0">
					<div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input
                          id="total" value="0" disabled
                          type="number"
                          class="form-control"
                          placeholder="Amount"
                          aria-label="Amount (to the nearest dollar)" />
                        <span class="input-group-text">.00</span>
                     </div>

				<?php } else if (preg_match_all('/^(?=.*Comments)(?=.*Custom)/i', $data_service['service'])) { ?>
					

					

					<div class="mb-3">
                          <label class="form-label" for="basic-default-message">Comment Data</label>
                          <textarea
                            name="comments"
                            id="comments"
                            class="form-control"
                            onkeyup="get_count(this.value).value;"
                            placeholder="Hi, Do you have a moment to talk Joe?"></textarea>
                    </div>

                    <div class="row g-3">
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-first-name">Target</label>
	                      <input type="text" name="link" class="form-control" placeholder="Link/Target" />
	                    </div>
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-last-name">Quantity</label>
	                      <input type="number" name="quantity" id="jumlah" readonly class="form-control" placeholder="" />
	                    </div>
                	</div>


					<input type="hidden" id="rate" value="0">
					<div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input
                          id="total" value="0" disabled
                          type="number"
                          class="form-control"
                          placeholder="Amount"
                          aria-label="Amount (to the nearest dollar)" />
                        <span class="input-group-text">.00</span>
                     </div>

				<?php } else { ?>
					

					<div class="row g-3">
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-first-name">Target</label>
	                      <input type="text" name="link" class="form-control" placeholder="Link/Target" />
	                    </div>
	                    <div class="col-md-6">
	                      <label class="form-label" for="multicol-last-name">Quantity</label>
	                      <input type="number" name="quantity" class="form-control" placeholder="Quantity" onkeyup="get_total(this.value).value;" />
	                    </div>
	                </div>
	                <br>
					<input type="hidden" id="rate" value="0">
					<div class="row g-3">
					<div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input
                          id="total" value="0" disabled
                          type="number"
                          class="form-control"
                          placeholder="Amount"
                          aria-label="Amount (to the nearest dollar)" />
                        <span class="input-group-text">.00</span>
                     </div>
                 </div>
		<?php }
		}
	} ?>