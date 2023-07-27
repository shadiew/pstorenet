<?php
require("../lib/mainconfig.php");

if (isset($_POST['service'])) {
	$post_sid = mysqli_real_escape_string($db, $_POST['service']);
	$check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid' AND status IN ('Active','normal')");
	if (mysqli_num_rows($check_service) == 1) {
		$data_service = mysqli_fetch_assoc($check_service);
?>

		<div class="alert alert-primary alert-dismissible d-flex align-items-baseline" role="alert">
                        <span class="alert-icon alert-icon-lg text-primary me-2">
                          <i class="ti ti-user ti-sm"></i>
                        </span>
                        <div class="d-flex flex-column ps-1">
                          <h5 class="alert-heading mb-2">Informasi!</h5>
                          <p class="mb-0">
                            <li>Min Order: <?php echo number_format($data_service['min']); ?></li>
                            <li>Max Order: <?php echo number_format($data_service['max']); ?></li>
                            <li>Note: <?php echo $data_service['note']; ?></li>
                          </p>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
        </div>


		
	<?php
	} else {
	?>
		

					  <div class="alert alert-danger alert-dismissible" role="alert">
                        Error! Service not found.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>

	<?php
	}
} else {
	?>
	<div class="alert alert-danger alert-dismissible" role="alert">
                        Error! Service not found.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
<?php
}
