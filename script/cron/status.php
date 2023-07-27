<?php
require("../lib/mainconfig.php");

$check_order = mysqli_query($db, "SELECT * FROM orders WHERE status IN ('Checking','Pending','Processing','In Progress') AND provider != 'MANUAL' AND provider != ''");

if (mysqli_num_rows($check_order) == 0) {
  die("Pending orders not found.");
} else {
  while($data_order = mysqli_fetch_assoc($check_order)) {
    $o_oid = $data_order['oid'];
    $o_poid = $data_order['poid'];
    $o_provider = $data_order['provider'];
    
    $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$o_provider'");
    $data_provider = mysqli_fetch_assoc($check_provider);
    
    $p_apikey = $data_provider['api_key'];
    $p_link = $data_provider['link'];
    
    $order_postdata = "key=$p_apikey&action=status&order=$o_poid";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $p_link);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $order_data = json_decode($chresult, true);
    $u_status = $order_data['status'];
    $u_start = $order_data['start_count'];
    $u_remains = $order_data['remains'];

    if ($u_status == "Pending") {
        $real_status = "Pending";
    } else if ($u_status == "Processing") {
        $real_status = "Processing";
    } else if ($u_status == "In progress") {
        $real_status = "In progress";    
    } else if ($u_status == "Partial") {
        $real_status = "Partial";
    } else if ($u_status == "Canceled") {
        $real_status = "Canceled";
    } else if ($u_status == "Completed") {
        $real_status = "Success";
    } else if ($u_status == "Success") {
        $real_status = "Success";
    }else {
        $real_status = "Pending";
    }

    if (empty($u_start)) {
        $u_start = "0";
    }
    
    $update_order = mysqli_query($db, "UPDATE orders SET status = '$real_status', start_count = '$u_start', remains = '$u_remains' WHERE oid = '$o_oid'");
    $update_order = mysqli_query($db, "UPDATE profit SET status = '$real_status', start_count = '$u_start', remains = '$u_remains' WHERE oid = '$o_oid'");
    if ($update_order == TRUE) {
      echo "$o_oid status $real_status | start $u_start | remains $u_remains<br />";
    } else {
      echo "Error database.".$real_status."-----".$u_start."-----".$u_remains."-----".$o_oid;
    }
  }
}