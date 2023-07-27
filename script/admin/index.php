<?php
session_start();
require("../lib/mainconfig.php");
$msg_type = "nothing";

/* CHECK FOR MAINTENANCE */
if($cfg_mt == 1) {
    die("Web is under maintenance.");
}else{

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."/logout/");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."/logout/");
	}else if ($data_user['level'] != "Developers") {
		header("Location: ".$cfg_baseurl);
	}

    /* DATA FOR DASHBOARD */
	$check_order = mysqli_query($db, "SELECT SUM(jumlah_transfer) AS total FROM history_topup WHERE status = 'YES'");
    $data_order = mysqli_fetch_assoc($check_order);

    $number_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users"));

    $number_tickets = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tickets"));

    $number_order = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders"));

    $check_earnings = mysqli_query($db, "SELECT SUM(price - price_provider) AS total FROM orders WHERE status = 'Success'");
    $data_earnings = mysqli_fetch_assoc($check_earnings);

    $check_earnings_today = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date'");
    $data_earnings_today = mysqli_fetch_assoc($check_earnings_today);


    $number_order_completed = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE status = 'Success'"));
    $number_order_pending = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE status = 'Pending' OR  status = 'Processing' OR status = 'In Progress'"));

    /* DATA FOR ORDERS STATISTICS CHART */
    $date_1 = date('Y-m-d',(strtotime ( '-5 day' , strtotime ($date) ) ));
    $date_2 = date('Y-m-d',(strtotime ( '-4 day' , strtotime ($date) ) ));
    $date_3 = date('Y-m-d',(strtotime ( '-3 day' , strtotime ($date) ) ));
    $date_4 = date('Y-m-d',(strtotime ( '-2 day' , strtotime ($date) ) ));
    $date_5 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($date) ) ));
    $date_6 = $date;

    $check_earning_date_1 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_1'");
    $check_earning_date_2 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_2'");
    $check_earning_date_3 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_3'");
    $check_earning_date_4 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_4'");
    $check_earning_date_5 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_5'");
    $check_earning_date_6 = mysqli_query($db, "SELECT COALESCE(SUM(price - price_provider),0) AS total FROM orders WHERE status = 'Success' AND date = '$date_6'");

    $data_earnings_date_1 = mysqli_fetch_assoc($check_earning_date_1);
    $data_earnings_date_2 = mysqli_fetch_assoc($check_earning_date_2);
    $data_earnings_date_3 = mysqli_fetch_assoc($check_earning_date_3);
    $data_earnings_date_4 = mysqli_fetch_assoc($check_earning_date_4);
    $data_earnings_date_5 = mysqli_fetch_assoc($check_earning_date_5);
    $data_earnings_date_6 = mysqli_fetch_assoc($check_earning_date_6);

    /* GENERAL WEB SETTINGS */
    $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
    $data_settings = mysqli_fetch_assoc($check_settings);

	$email = $data_user['email'];
	$hp = $data_user['nohp'];
	/* if ($email == "") {
	header("Location: ".$cfg_baseurl2."settings.php");
	} */
} else {
	header("Location: ../home");
}
$title = "Admin Dashboard";
include("../lib/header_admin.php");
if (isset($_SESSION['user'])) {
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<div class="page has-sidebar-left height-full">
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon icon-home2 indigo-text s-18"></i>
                        Admin Dashboard
                    </h4>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Dashboard Start-->
            <div class="tab-pane animated fadeInUpShort show active" id="dashboard">
                <div class="row my-3">
                    <div class="col-md-3">
                        <div class="counter-box white r-5 p-3">
                            <div class="p-4">
                                <div class="float-right">
                                    <span class="icon icon-user-o text-light-blue s-48"></span>
                                </div>
                                <div class="counter-title">Total Users</div>
                                <h5><?php echo $number_users; ?></h5>
                            </div>
                            <div class="progress progress-xs r-0">
                                <div class="progress-bar width-25p" role="progressbar" aria-valuenow="25"
                                     aria-valuemin="0" aria-valuemax="128"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="counter-box white r-5 p-3">
                            <div class="p-4">
                                <div class="float-right">
                                    <span class="icon icon-attach_money s-48"></span>
                                </div>
                                <div class="counter-title">Total Amount Recieved</div>
                                <h5>$<?php echo number_format($data_order['total'], 4); ?></h5>
                            </div>
                            <div class="progress progress-xs r-0">
                                <div class="progress-bar  width-25p" role="progressbar" aria-valuenow="25"
                                     aria-valuemin="0" aria-valuemax="128"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="counter-box white r-5 p-3">
                            <div class="p-4">
                                <div class="float-right">
                                    <span class="icon icon-shopping-cart2 s-48"></span>
                                </div>
                                <div class="counter-title">Total Orders</div>
                                <h5><?php echo $number_order; ?></h5>
                            </div>
                            <div class="progress progress-xs r-0">
                                <div class="progress-bar  width-25p" role="progressbar" aria-valuenow="25"
                                     aria-valuemin="0" aria-valuemax="128"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="counter-box white r-5 p-3">
                            <div class="p-4">
                                <div class="float-right">
                                    <span class="icon icon-chat_bubble_outline s-48"></span>
                                </div>
                                <div class="counter-title">Total Tickets</div>
                                <h5><?php echo $number_tickets; ?></h5>
                            </div>
                            <div class="progress progress-xs r-0">
                                <div class="progress-bar  width-25p" role="progressbar" aria-valuenow="25"
                                     aria-valuemin="0" aria-valuemax="128"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-12">
                        <div class="white p-5 r-5">
                            <div class="card-title">
                                <h4> Total Profit</h4>
                            </div>
                            <div class="row my-3">
                                <div class="col-md-3">
                                    <div class="my-3 mt-4">
                                        <h5>Today <span class="green-text">+ $<?php echo number_format($data_earnings_today['total'], 2); ?></span></h5>
                                        <span class="s-24">$<?php echo number_format(($data_earnings['total']-$data_earnings_today['total']),4); ?></span>
                                        <p class="tsg-dash-spent-box">We have earned <b>$<?php echo number_format($data_earnings['total'],4); ?></b> in total. </br>This amount is counted by subtracting the provider's price from normal price.</p>
                                    </div>
                                    <div class="row no-gutters bg-light r-3 p-2 mt-5">
                                        <div class="col-md-6 b-r p-3">
                                                <h6>Total Orders</h6>
                                                <h5>Completed</h5>
                                                <h4 class="green-text margin-top-5"><?php echo $number_order_completed; ?> </h4>
                                        </div>
                                        <div class="col-md-6 p-3">
                                            <div class="">
                                                <h6>Total Orders</h6>
                                                <h5>Pending</h5>
                                                <h4 class="amber-text margin-top-5"><?php echo $number_order_pending; ?> </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 height-350">

                                <!-- ORDERS STATISTICS CHART -->
                                <canvas id="myChart"></canvas>
                                    <script>
                                    var ctx = document.getElementById('myChart');
                                    var myChart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: ['<?php echo $date_1; ?>', '<?php echo $date_2; ?>', '<?php echo $date_3; ?>', '<?php echo $date_4; ?>', '<?php echo $date_5; ?>', '<?php echo $date_6; ?>'],
                                            datasets: [
                                                {   label:'Earnings',
                                                    fill: true,
                                                    data: [<?php echo $data_earnings_date_1['total']; ?>, <?php echo $data_earnings_date_2['total']; ?>, <?php echo $data_earnings_date_3['total']; ?>, <?php echo $data_earnings_date_4['total']; ?>, <?php echo $data_earnings_date_5['total']; ?>, <?php echo $data_earnings_today['total']; ?>],
                                                    backgroundColor: 'rgba(50,141,255,.2)',
                                                    borderColor: '#328dff',
                                                    pointBorderColor: '#328dff',
                                                    pointBackgroundColor: '#fff',
                                                    pointBorderWidth: 2,
                                                    borderWidth: 1,
                                                    borderJoinStyle: 'miter',
                                                    pointHoverBackgroundColor: '#328dff',
                                                    pointHoverBorderColor: '#328dff',
                                                    pointHoverBorderWidth: 1,
                                                    pointRadius: 3,
                                                    
                                                }
                                            ]
                                        },
                                        options: {
                                            maintainAspectRatio: false,
                                            legend: {
                                                display: true
                                            },

                                            scales: {
                                                xAxes: [{
                                                    display: true,
                                                    gridLines: {
                                                        zeroLineColor: '#eee',
                                                        color: '#eee',
                                                    
                                                        borderDash: [5, 5],
                                                    }
                                                }],
                                                yAxes: [{
                                                    display: true,
                                                    gridLines: {
                                                        zeroLineColor: '#eee',
                                                        color: '#eee',
                                                        borderDash: [5, 5],
                                                    }
                                                }]

                                            },
                                            elements: {
                                                line: {
                                                
                                                    tension: 0.4,
                                                    borderWidth: 1
                                                },
                                                point: {
                                                    radius: 2,
                                                    hitRadius: 10,
                                                    hoverRadius: 6,
                                                    borderWidth: 4
                                                }
                                            }
                                        }
                                    });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- User dashboard button -->
                <div class="card no-b shadow  mt-3 mb-3">
                    <div class="card-body text-center">
                    <div class="col-md-10">
                    <table class="table cell-vertical-align-middle mb-0">
                                <tr class="no-b">
                                <td><h4 class="mb-0">This is admin dashboard. You can visit the user dashboard by using this button.</h4></td>
                                <td>
                                    <a href="../"><button type="button" class="btn btn-primary btn-lg r-20 shadow1"><i class="icon-heart-o mr-2"></i>User Dashboard</button></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    </div>
                </div>
            <!--Dashboard End-->
        </div>
    </div>
</div>
<?php
}
include("../lib/footer.php");
}
?>