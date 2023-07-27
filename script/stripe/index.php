<?php
session_start();
require("../lib/mainconfig.php");
  
/* CHECK USER SESSION */   
if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."/logout/");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."/logout/");
	}
	$email = $data_user['email'];
	if ($email == "") {
	header("Location: ".$cfg_baseurl."settings");
    }
    

	/* GENERAL WEB SETTINGS */
    $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
    $data_settings = mysqli_fetch_assoc($check_settings);
    if($data_settings['pay_stripe_on'] == "OFF"){
		header("Location: ".$cfg_baseurl);
	}
	$title = "Deposit";
	include("../lib/header.php");
	$msg_type = "nothing";
	
?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <div class="page has-sidebar-left">
    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-cc-stripe"></i>
                        Stripe Deposit
                    </h4>
                </div>
            </div>
        </div>
    </header>

    <div class="animatedParent animateOnce">
        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body b-b">
                        <!-- STRIPE DEPOSIT FORM -->
                        <h4>Stripe Deposit</h4>
                        <form name="stripeForm" method="post">
                        <div class="form-group">
                            <label for="sms" class="col-form-label">Deposit Amount (USD)</label>
                            <input id="amount_field" required type="number" pattern="[0-9]{10}" name="amount" min="<?php echo $data_settings['pay_stripe_min']; ?>" step="0.1" class="form-control" placeholder="$">
                            </div>
                            <input type="submit" class="btn btn-primary" value="Deposit">
                        </div>
                        </form>
                    </div>
                </div><br>
                <div class="col-md-5">
                    <!-- INFORMATION TAB -->
                    <div class="card">
                    <div class="card-body b-b"><h3>Information</h3>
                    <hr>
                        <div class="panel-body">
							<?php echo $data_settings['stripe_ins']; ?>
						</div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript" src="../js/stripe.js"></script>


<!-- STRIPE DYNAMIC JS -->
<script type="text/javascript">
  function pay() {
    var amount = document.getElementById('amount_field').value;
    amount *= 100;
    var handler = StripeCheckout.configure({
      key: '<?php echo $data_settings['pay_stripe_pk']; ?>', // stripe publisher key id
      locale: 'auto',
      token: function (token) {
        $.ajax({
          url:"verify.php",
          method: 'post',
          data: { tokenId: token.id, amount: amount, userId: "<?php echo $sess_username ?>"},
          dataType: "json",
          success: function( response ) {
            window.location.assign("../history/deposit/");
          },
          error: function (){
            alert("There was a problem processing your request. Please contact website administrator.");
          }
        })
      }
    });
    handler.open({
      name: '<?php echo $data_settings['web_name']; ?>',
      description: 'Add Funds',
      amount: amount
    });
  }
</script>

<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>