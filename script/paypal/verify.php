<?php

require_once ('../lib/mainconfig.php');
include('ipnlistener.php');
/* PAYPAL IPN LISTENER https://github.com/Quixotix/PHP-PayPal-IPN */

$listener = new IpnListener();

$listener->use_sandbox = true;
$listener->use_ssl = true;
$listener->use_curl = false;

$listener->requirePostMethod();
$verified = $listener->processIpn();

$user = $_POST['custom'];
$transfer = $_POST['payment_gross'];
$transcurrency = $_POST['mc_currency'];
$transid = $_POST['txn_id'];
$pstatus = $_POST['payment_status'];

if ($verified) {
    if($pstatus == "Completed"){
        $resultpop = mysqli_query($db, "SELECT * FROM `history_topup` WHERE `nopengirim` = '$transid' AND `provider` = 'PAYPAL' ");
        if( mysqli_num_rows($resultpop) > 0) {
            $msg_type = "Error";
            $msg_content = "Transaction ID is already used.";
        }elseif ($transcurrency == "USD"){

            /* GENERATING DEPO ID */
            $check_highest_id = mysqli_query($db, "SELECT * FROM `history_topup` ORDER BY `id_depo` DESC LIMIT 1");
			$highest_id = mysqli_fetch_array($check_highest_id);
			$id_depo = $highest_id['id_depo'] + 1;

            /* UPDATING PAYMENT HISTORY */
            $insert_topup = mysqli_query($db, "INSERT INTO history_topup(provider, amount, jumlah_transfer, username, user, norek_tujuan_trf, nopengirim, date, time, status, type, id_depo, top_ten, name_method) VALUES ('PAYPAL','$transfer','$transfer','$user','$user','--','$transid','$date','$time','NO','WEB','$id_depo','ON', 'Paypal')");
			if ($insert_topup == TRUE) {
            $update_depo = mysqli_query($db, "UPDATE history_topup SET status = 'YES' WHERE nopengirim = '$transid'");

            /* UPDATING BALANCE */
            $update_depo = mysqli_query($db, "UPDATE users SET balance = balance+$transfer WHERE username = '$user'");

            /* UPDATING BALANCE HISTORY */
            $check_balance = mysqli_query($db, "SELECT * FROM users WHERE username = '$user'");
			$data_balance = mysqli_fetch_assoc($check_balance);
            $temp_balance = number_format($data_balance['balance'], 4);
            $update_depo = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, clearance, msg, date, time, type) VALUES ('$user', 'Add Balance', '$transfer', '$$temp_balance', 'You Have Made a Balance Deposit, ID: $id_depo', '$date', '$time', '+ $')");
            if ($update_depo == TRUE) {
                $msg_type = "Succcess";
                $msg_content = "ADDED ".$transfer;
            }
            }
        }else{
            $msg_type = "Error";
            $msg_content = "Invalid";
        }
        echo "<b>".$msg_type."</b>";
        echo "<br>".$msg_content;
    }
}