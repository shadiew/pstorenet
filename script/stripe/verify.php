<?php
    require_once ('../lib/mainconfig.php');

    $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
    $data_settings = mysqli_fetch_assoc($check_settings);

    if($_POST['tokenId']) {

      require_once('vendor/autoload.php');
    
      //stripe secret key or revoke key
      $stripeSecret = $data_settings['pay_stripe_sk'];

      // See your keys here: https://dashboard.stripe.com/account/apikeys
      \Stripe\Stripe::setApiKey($stripeSecret);
      \Stripe\Stripe::setMaxNetworkRetries(2);

     // Get the payment token ID submitted by the form:
      $token = str_replace("tok_","",$_POST['tokenId']);
      $amount = $_POST['amount']/100;
      $user = $_POST['userId'];

      // Charge the user's card:
      $charge = \Stripe\Charge::create(array(
          "amount" => $_POST['amount'],
          "currency" => "usd",
          "description" => "stripe integration in PHP with source code - tutsmake.com",
          "source" => $_POST['tokenId'],
       ));
            
       // after successfull payment, you can store payment related information into your database

        $data = array('success' => true, 'data'=> $charge);

        $data = json_encode($data);

        $resultpop = mysqli_query($db, "SELECT * FROM `history_topup` WHERE `nopengirim` = '$token' AND `provider` = 'STRIPE' ");
        if( mysqli_num_rows($resultpop) > 0) {
            $msg_type = "Error";
            $msg_content = "Transaction ID is already used.";
        }else{
            /* GENERATING DEPO ID */
            $check_highest_id = mysqli_query($db, "SELECT * FROM `history_topup` ORDER BY `id_depo` DESC LIMIT 1");
			      $highest_id = mysqli_fetch_array($check_highest_id);
		      	$id_depo = $highest_id['id_depo'] + 1;

            /* UPDATING PAYMENT HISTORY */
            $insert_topup = mysqli_query($db, "INSERT INTO history_topup(provider, amount, jumlah_transfer, username, user, norek_tujuan_trf, nopengirim, date, time, status, type, id_depo, top_ten, name_method) VALUES ('STRIPE','$amount','$amount','$user','$user','--','$token','$date','$time','NO','WEB','$id_depo','ON', 'Stripe')");
		      	if ($insert_topup == TRUE) {
            $update_depo = mysqli_query($db, "UPDATE history_topup SET status = 'YES' WHERE nopengirim = '$token'");

            /* UPDATING BALANCE */
            $update_depo = mysqli_query($db, "UPDATE users SET balance = balance+$amount WHERE username = '$user'");

            /* UPDATING BALANCE HISTORY */
            $check_balance = mysqli_query($db, "SELECT * FROM users WHERE username = '$user'");
			$data_balance = mysqli_fetch_assoc($check_balance);
            $temp_balance = number_format($data_balance['balance'], 4);
            $update_depo = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, clearance, msg, date, time, type) VALUES ('$user', 'Add Balance', '$amount', '$$temp_balance', 'You Have Made a Balance Deposit, ID: $id_depo', '$date', '$time', '+ $')");
            if ($update_depo == TRUE) {
                $msg_type = "Succcess";
                $msg_content = "ADDED ".$amount;
            }
            }
        }
        echo json_encode($data);
}