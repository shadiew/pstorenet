<?php
require_once ('../lib/mainconfig.php');

/* GENERAL WEB SETTINGS */
$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
/* connect to gmail */
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = $data_settings['pay_paytm_email'];
$password = $data_settings['pay_paytm_pass'];

//READING ALL MAILS
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

	//CHECKING ALL TRANSACTION LIST ONE BY ONE
	$trans_list = mysqli_query($db, "SELECT * FROM history_topup WHERE status = 'NO' AND provider = 'PAYTM' ORDER BY id ASC");
	while ($data_trans = mysqli_fetch_assoc($trans_list)) {
		$tabelID = $data_trans['id'];
		$transactionID = $data_trans['nopengirim'];
		$amount = $data_trans['jumlah_transfer'];
		$user = $data_trans['username'];
		$transferOrig = $data_trans['amount'];
		$transfer = round ($transferOrig , 4);
		$id_depo = $data_trans['id_depo'];
		$matchTxn = imap_search($inbox, 'SUBJECT "'.$transactionID.'"',SE_FREE, "UTF-8");
		$a = var_export($matchTxn, true);
            $data = $a;
            $whatIWant = substr($data, strpos($data, ">") + 1);
            $to = ", )";
            $c = chop($whatIWant, $to);
            $d = str_replace(",", "", $c);
			$e = preg_replace('/\s+/', '', $d);
		$message = imap_fetchbody($inbox,$e,1.1);
		$abhi = $amount."</font>";


		$resultpop = mysqli_query($db, "SELECT * FROM `history_topup` WHERE `nopengirim` = '$transactionID' AND `provider` = 'PAYTM' AND `status` = 'YES'");
			if( mysqli_num_rows($resultpop) > 0) {
			}
				//MATCHING TRANSACTION AMOUNT WITH MAIL AMOUNT
				else if (strpos($message, $abhi) == true) { 

					//SUCCESSFULLY ADDING MONEY

					//UPDATING PAYMENT HISTORY
					$update_depo = mysqli_query($db, "UPDATE history_topup SET status = 'YES' WHERE nopengirim = '$transactionID' AND id = '$tabelID' AND provider = 'PAYTM'");

					//UPDATING BALANCE
					$update_depo = mysqli_query($db, "UPDATE users SET balance = balance+$transfer WHERE username = '$user'");

					//UPDATING BALANCE HISTORY
					$check_balance = mysqli_query($db, "SELECT * FROM users WHERE username = '$user'");
					$data_balance = mysqli_fetch_assoc($check_balance);
					$temp_balance = number_format($data_balance['balance'], 4);

					$update_depo = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, clearance, msg, date, time, type) VALUES ('$user', 'Add Balance', '$transfer', '$$temp_balance', 'You Have Made a Balance Deposit, ID: $id_depo', '$date', '$time', '+ $')");
				}
				else {
					//FAILED - CANCEL TRANSACTION
					$update_depo = mysqli_query($db, "UPDATE history_topup SET status = 'CANCEL' WHERE nopengirim = '$transactionID' AND provider = 'PAYTM'");
				}
	}

//DISCONNECTING IMAP
imap_close($inbox);