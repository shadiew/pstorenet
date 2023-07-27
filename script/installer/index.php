<?php
require("../lib/function.php");
require("../lib/config.php");
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Installation - TheSocialGrowth</title>
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="https://thesocialgrowth.com/logo/fav_small.png" type="image/png" sizes="16x16">
</head>
<?php
if (!is_file("../lib/config.php")) {
    copy("../lib/config_main.php", "../lib/config.php");
}
if ($cfg_baseurl != "enter_base_url") {
    redirect($cfg_baseurl);
}
$step = (isset($_POST['step']) && $_POST['step'] != '') ? $_POST['step'] : '';
$stepper = 0;
?>

<body class="light">
    <div id="app">
        <main>
            <div id="primary" class="p-t-b-100 height-full">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 mx-md-auto paper-card login-card-1">
                            <div class="text-center">
                                    <img src="https://thesocialgrowth.com/logo/logo_blue.svg" alt="TheSocialGrowth" class="login-card-logo">
                            </div>
                            <br><br>
                            <div class="col-lg">

                                <!-- Error Box -->
                                <?php
                  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pre_error'] != '') {
                      ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">×</span>
                                    </button>
                                    <strong>Failed!</strong> <?php echo $_POST['pre_error']; ?>
                                </div>
                                <?php
                  }
                ?>


                                <?php
switch ($step) {
  case '1':
  step_1();
  break;
  case '2':
  step_2();
  break;
  case '3':
  step_3();
  break;
  case '4':
  step_4();
  break;
  default:
  step_1();
}


function stepper($url)
{
    if (isset($url)) {?>
                                <form id="redirector" method="post">
                                    <input type="text" hidden name="step" value="<?php echo $url; ?>">
                                </form>
                                <script type="text/javascript" src="redirector.js"></script>
                                <?php }
}
function redirect($url)
{
    if (!headers_sent()) {
        header('Location: '.$url);
        exit;
    } else {?>
        <script type="text/javascript">window.location.href="<?php echo $url; ?>"</script>
        <noscript>
        <meta http-equiv="refresh" content="0;url=<?php echo $url; ?>" />
        </noscript>
        <?php
    }
}

function step_1()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agree'])) {
        stepper('2');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['agree'])) {
        ?><div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">×</span>
                                    </button>
                                    <strong>Failed!</strong> You must check the tickbox below.
                                </div>
                                <?php
    } ?>
                                <p>Thanks for buying TheSocialGrowth.</p>
                                <form method="post">
                                    <p>
                                        I am ready to install the script.
                                        <input type="checkbox" name="agree" />
                                    </p>
                                    <input type="text" hidden name="step" value="1">
                                    <input type="submit" class="btn btn-success btn-lg btn-block" value="Continue" />
                                </form>
                                <?php
}
function tsg_runner($code) {
    global $stepper;
    $code = trim($code);
    $post_data = "id=$code";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://socialgrowth.oraclecode.vip/installer/echo.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $stepper = $chresult;
    return $chresult;
    
    
}
    

    
   
function step_2()
{
    $config_file_path = '../lib/config.php';
    if ($_POST['pre_error'] =='' && $_POST['stepset']) {
        stepper('3');
        exit;
    }
        
    if (phpversion() < '7.0') {
        $pre_error = 'You need to use PHP7.0 or above for our site!<br />';
    }
    if (ini_get('session.auto_start')) {
        $pre_error .= 'Our site will not work with session.auto_start enabled!<br />';
    }
    if (!extension_loaded('mysqli')) {
        $pre_error .= 'MySQLi extension needs to be loaded for our site to work!<br />';
    }
    if (!extension_loaded('gd')) {
        $pre_error .= 'GD extension needs to be loaded for our site to work!<br />';
    }
    if (!is_writable($config_file_path)) {
        $pre_error .= 'config.php needs to be writable for our site to be installed!';
    } else {
        $pre_error = "";
        $stepset = 2;
    } ?>
                                <table class="table-services" width="100%">
                                    <tr>
                                        <td></td>
                                        <td><u>Current</td>
                                        <td><u>Requirement</td>
                                        <td><u>Status</td>
                                    <tr>
                                        <td>PHP Version:</td>
                                        <td><?php echo phpversion(); ?></td>
                                        <td>7.0+</td>
                                        <td><?php echo (phpversion() >= '7.0') ? 'Ok' : 'Not Ok'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Session Auto Start:</td>
                                        <td><?php echo (ini_get('session_auto_start')) ? 'On' : 'Off'; ?></td>
                                        <td>Off</td>
                                        <td><?php echo (!ini_get('session_auto_start')) ? 'Ok' : 'Not Ok'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>MySQL:</td>
                                        <td><?php echo extension_loaded('mysqli') ? 'On' : 'Off'; ?></td>
                                        <td>On</td>
                                        <td><?php echo extension_loaded('mysqli') ? 'Ok' : 'Not Ok'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>GD:</td>
                                        <td><?php echo extension_loaded('gd') ? 'On' : 'Off'; ?></td>
                                        <td>On</td>
                                        <td><?php echo extension_loaded('gd') ? 'Ok' : 'Not Ok'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>config.php</td>
                                        <td><?php echo is_writable($config_file_path) ? 'Writable' : 'Unwritable'; ?>
                                        </td>
                                        <td>Writable</td>
                                        <td><?php echo is_writable($config_file_path) ? 'Ok' : 'Not Ok'; ?></td>
                                    </tr>
                                </table><br><br>
                                <form method="post">
                                    <input type="hidden" name="pre_error" id="pre_error"
                                        value="<?php echo $pre_error; ?>" />
                                    <input type="hidden" name="stepset" id="stepset" value="<?php echo $stepset; ?>" />
                                    <input type="text" hidden name="step" value="2">
                                    <input type="submit" class="btn btn-success btn-lg btn-block" name="continue"
                                        value="Continue" />
                                </form>
                                <?php
}
  function step_3()
  {
    global $stepper;
    $config_file_path = '../lib/config.php';
      if (isset($_POST['submit']) && $_POST['submit']=="Install!") {
          $database_host=isset($_POST['database_host'])?$_POST['database_host']:"";
          $database_name=isset($_POST['database_name'])?$_POST['database_name']:"";
          $database_username=isset($_POST['database_username'])?$_POST['database_username']:"";
          $database_password=isset($_POST['database_password'])?$_POST['database_password']:"";
          $admin_name=isset($_POST['admin_name'])?$_POST['admin_name']:"";
          $admin_password=isset($_POST['admin_password'])?$_POST['admin_password']:"";
          $admin_email=isset($_POST['admin_email'])?$_POST['admin_email']:"";
          $timezone = isset($_POST['timezone'])?$_POST['timezone']:"";
          $website_url = isset($_POST['website_url'])?$_POST['website_url']:"";
          $p_code = isset($_POST['p_code'])?htmlspecialchars($_POST['p_code']):"";
          
          
$url = 'http://oraclecode.net/?edd_action=get_version&item_id=101686&license=' . $p_code . '&url=' . $website_url;
                $unparsed_json = file_get_contents($url);
                $json_object = json_decode($unparsed_json);
                $url1=$json_object->download_link;
                $zipFile = "./data.zip"; // Local Zip File Path
                $zipResource = fopen($zipFile, "w");
                // Get The Zip File From Server
                $zipFile = "./data.zip"; // Local Zip File Path
                $zipResource = fopen($zipFile, "w");
                // Get The Zip File From Server
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url1);
                curl_setopt($ch, CURLOPT_FAILONERROR, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
                curl_setopt($ch, CURLOPT_FILE, $zipResource);
                $page = curl_exec($ch);
                if(!$page) {
                echo "Error :- ".curl_error($ch);
                }
                curl_close($ch);

/* Open the Zip file */
               $zip = new ZipArchive;
               $extractPath = "./";
               if($zip->open($zipFile) != "true"){
                 echo "Error :- Unable to open the Zip File";
                } 
                   /* Extract Zip File */
                $zip->extractTo($extractPath);
                $zip->close();
                @unlink('./data.zip');
                
              
          
          
    
          if (empty($p_code) || empty($timezone) || empty($website_url) || empty($admin_email) || empty($admin_name) || empty($admin_password) || empty($database_host) || empty($database_username) || empty($database_name)) {
              ?><div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">×</span>
                                    </button>
                                    <strong>Failed!</strong> All fields are required! Please re-enter.
                                </div>
                                <?php
          } else {
              $connection = mysqli_connect($database_host, $database_username, $database_password, $database_name);
              $o = tsg_runner($p_code);
              if (!mysqli_connect_error()) {
                  if (mysqli_num_rows(mysqli_query($connection, "SHOW TABLES LIKE 'settings'"))) {
                      ?><div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">×</span>
                                    </button>
                                    <strong>Failed!</strong> Database already exists. Please drop old database.
                                </div>
                                <?php
                } else if ($o == 0) {
                        ?><div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <strong>Failed!</strong> Item purchase code is wrong. Please fill it correctly.
                            </div>
                        <?php
                } else if ($o == 2) {
                    ?><div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <strong>Failed!</strong> Your purchase code has been used several times. Please contact the <a href="mailto:thesocialgrowth.com@gmail.com" target="_blank">support.</a>
                        </div>
                    <?php
                } else if ($o == 1) {
                      $file ='./data.sql';
                      $apikey = random(20);
                      $sql = file_get_contents("./data.sql");
                      $sql = str_replace('admin_username', $admin_name, $sql);
                      $sql = str_replace('admin_email', $admin_email, $sql);
                      $sql = str_replace('admin_password', $admin_password, $sql);
                      $sql = str_replace('admin_api_key', $apikey, $sql);
                      file_put_contents('./data.sql', $sql);

                      if ($sql = file($file)) {
                          $query = '';
                          foreach ($sql as $line) {
                              $tsl = trim($line);
                              if (($sql != '') && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {
                                  $query .= $line;
      
                                  if (preg_match('/;\s*$/', $line)) {
                                      if ($stepper == 1) {
                                          mysqli_query($connection, $query);
                                          $err = mysqli_error($connection);
                                          if (!empty($err)) {
                                              echo $err;
                                              break;
                                          }
                                          $query = '';
                                      }
                                  }
                              }
                          }
                          unlink('./data.sql');
                      }
      
                      if ($cfg_baseurl != "enter_base_url") {
                          copy("../lib/config_main.php", "../lib/config.php");
                      }

                      $website_url = rtrim($website_url, "/");

                      $config_file = file_get_contents($config_file_path);
                      $config_file = str_replace('enter_db_server', $database_host, $config_file);
                      $config_file = str_replace('enter_db_username', $database_username, $config_file);
                      $config_file = str_replace('enter_db_password', $database_password, $config_file);
                      $config_file = str_replace('enter_db_name', $database_name, $config_file);
                      $config_file = str_replace('enter_timezone', $timezone, $config_file);
                      $config_file = str_replace('enter_base_url', $website_url, $config_file);
                      file_put_contents($config_file_path, $config_file);

                      redirect("../login/");
                  }
              } else {
                ?><div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span>
                </button>
                <strong>Failed!</strong> Database connection error.
            </div>
        <?php
              }
          }
      } ?>
                                <form method="post" action="index.php">
                                    <p>
                                        <label class="col-form-label" for="database_host">Database Host</label>
                                        <input class="form-control" type="text" name="database_host" value='localhost'
                                            size="30">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="database_name">Database Name</label>
                                        <input class="form-control" type="text" name="database_name" size="30"
                                            value="<?php echo $database_name; ?>">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="database_username">Database Username</label>
                                        <input class="form-control" type="text" name="database_username" size="30"
                                            value="<?php echo $database_username; ?>">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="database_password">Database Password</label>
                                        <input class="form-control" type="text" name="database_password" size="30"
                                            value="<?php echo $database_password; ?>">
                                    </p>
                                    <br>
                                    <hr><br>
                                    <p>
                                        <label class="col-form-label" for="username">Admin Login</label>
                                        <input class="form-control" type="text" name="admin_name" size="30"
                                            value="<?php echo $admin_name; ?>">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="email">Admin Email</label>
                                        <input class="form-control" type="email" name="admin_email" size="30"
                                            value="<?php echo $admin_email; ?>">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="admin_password">Admin Password</label>
                                        <input class="form-control" name="admin_password" type="text" size="30"
                                            maxlength="30" value="<?php echo $admin_password; ?>">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="website_url">Website URL
                                            (https://example.com)</label>
                                        <input class="form-control" name="website_url" type="text" size="30"
                                            value="<?php echo $website_url; ?>">
                                    </p>
                                    <p>
                                        <label class="col-form-label" for="p_code">Item Purchase Code</label>
                                        <input class="form-control" name="p_code" type="text" size="30"
                                            value="">
                                    </p>
                                    <p>
                                        <?php
      $timezone_identifiers =
      DateTimeZone::listIdentifiers(DateTimeZone::ALL);

      echo '<select name="timezone" class="form-control">';

      echo "<option disabled selected> 
          Please Select Timezone 
        </option>";

      $n = 425;
      for ($i = 0; $i < $n; $i++) {
        
      // Print the timezone identifiers
          echo "<option value='" . $timezone_identifiers[$i] .
          "'>" . $timezone_identifiers[$i] . "</option>";
      }

      echo "</select>"; ?>
                                    </p>
                                    <p>
                                        <p>
                                            <input type="text" hidden name="step" value="3">
                                            <input type="submit" class="btn btn-success btn-lg btn-block" name="submit"
                                                value="Install!">
                                        </p>
                                </form>
                                <?php
  }
    function step_4()
    {
        redirect("../");
    }
?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #primary -->
        </main>
        <div class="control-sidebar-bg shadow white fixed"></div>
    </div>
    <!--/#app -->
    <script src="../assets/js/app.js"></script>
</body>

</html>