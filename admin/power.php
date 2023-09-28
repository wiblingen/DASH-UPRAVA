<?php

if (!isset($_SESSION) || !is_array($_SESSION)) {
    session_id('pistardashsess');
    session_start();
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';        // Translation Code
    checkSessionValidity();
}

// Load the language support
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

// Sanity Check that this file has been opened correctly
if ($_SERVER["PHP_SELF"] == "/admin/power.php") {
    // Sanity Check Passed.
    header('Cache-Control: no-cache');

function purgeLogs() {
    $log_backup_dir = "/home/pi-star/.backup-mmdvmhost-logs/";
    $log_dir = "/var/log/pi-star/";
    exec ('sudo systemctl stop cron');
    exec ('sudo mount -o remount,rw /');
    exec ('sudo systemctl stop mmdvm-log-backup.timer');
    exec ('sudo systemctl stop mmdvm-log-backup.service');
    exec ('sudo systemctl stop mmdvm-log-restore.service');
    exec ('sudo systemctl stop mmdvm-log-shutdown.service');
    exec ("sudo rm -rf $log_dir/* $log_backup_dir/* > /dev/null");
}

if(isset($_SESSION['PiStarRelease']['Pi-Star']['ProcNum']) && ($_SESSION['PiStarRelease']['Pi-Star']['ProcNum'] >= 4)) {
    $rbTime = "90";
} else {
    $rbTime = "120";
}

?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
	<head>
	    <meta name="language" content="English" />
	    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	    <meta http-equiv="pragma" content="no-cache" />
	    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
	    <meta http-equiv="Expires" content="0" />
	    <title>WPSD <?php echo $lang['digital_voice']." ".$lang['dashboard']." - ".$lang['power'];?></title>
	    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
        <script type="text/javascript" src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
        <script type="text/javascript" src="/js/functions.js?version=<?php echo $versionCmd; ?>"></script>
        <script type="text/javascript">
          $.ajaxSetup({ cache: false });
        </script>
	</head>
	<body>
	    <div class="container">
		<div class="header">
		    <div class="SmallHeader shLeft noMob">Hostname: <?php echo exec('cat /etc/hostname'); ?></div>
		    <div class="SmallHeader shRight noMob">
                      <div id="CheckUpdate">
                      <?php
                          include $_SERVER['DOCUMENT_ROOT'].'/includes/checkupdates.php';
                      ?>
                      </div><br />
                    </div>
		    <h1>WPSD <?php echo $lang['digital_voice']." - ".$lang['power'];?></h1>
			<div class="navbar">
              <script type= "text/javascript">
               $(document).ready(function() {
                 setInterval(function() {
                   $("#timer").load("/includes/datetime.php");
                   }, 1000);

                 function update() {
                   $.ajax({
                     type: 'GET',
                     cache: false,
                     url: '/includes/datetime.php',
                     timeout: 1000,
                     success: function(data) {
                       $("#timer").html(data); 
                       window.setTimeout(update, 1000);
                     }
                   });
                 }
                 update();
               });
              </script>
              <div class="headerClock">
                <span id="timer"></span>
            </div>
			    <a class="menuconfig" href="/admin/configure.php"><?php echo $lang['configuration'];?></a>
			    <a class="menubackup noMob" href="/admin/config_backup.php"><?php echo $lang['backup_restore'];?></a>
			    <a class="menuupdate noMob" href="/admin/update.php"><?php echo $lang['update'];?></a>
			    <a class="menuadmin noMob" href="/admin/"><?php echo $lang['admin'];?></a>
			    <?php if (file_exists("/etc/dstar-radio.mmdvmhost")) { ?>
			    <a class="menulive" href="/live/">Live Caller</a>
			    <?php } ?>
			    <a class="menudashboard" href="/"><?php echo $lang['dashboard'];?></a>
			</div>
		</div>
		<div class="contentwide">
		    <?php if (!empty($_POST)) { ?>
			<table width="100%">
			    <tr><th colspan="2"><?php echo $lang['power'];?></th></tr>
			    <?php
			    if ( escapeshellcmd($_POST["action"]) == "reboot" ) {
				echo '<tr><td colspan="2" style="background: #000000; color: #4DEEEA;"><br /><br />Your Hotspot is rebooting...
				   <br />You will be re-directed back to the
				   <br />dashboard automatically in ' .$rbTime. ' seconds.<br /><br /><br />
				   <script language="JavaScript" type="text/javascript">
                                   setTimeout("location.href = \'/\'", '.$rbTime.'000);
				   </script>
				   </td></tr>'; 
		if ( escapeshellcmd($_POST["purgeLogs"]) == "1" ) {
		    purgeLogs();
		}
		exec("sudo sync && sudo reboot > /dev/null 2>&1 &");
			    }
			    else if ( escapeshellcmd($_POST["action"]) == "shutdown" ) {
				echo '<tr><td colspan="2" style="background: #000000; color: #4DEEEA;"><br /><br />Shutdown command has been sent to your Hotspot.
				   <br />Please wait at least 60 seconds for it to fully shutdown<br />before removing the power.<br /><br /><br /></td></tr>';
		if ( escapeshellcmd($_POST["purgeLogs"]) == "1" ) {
		    purgeLogs();
		}
		exec("sudo sync && sudo shutdown -h now > /dev/null 2>&1 &");
			    }

			    unset($_POST);
			    ?>
			</table>
		    <?php }
		    else { ?>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			    <table width="100%">
				<tr>
				    <th colspan="2"><?php echo $lang['power'];?></th>
				</tr>
				<tr>
				    <td align="center">
					<h3>Reboot</h3><br />
					<button style="border: none; background: none; margin: 15px 0px;" name="action" value="reboot"><img src="/images/reboot.png" border="0" alt="Reboot" /></button>
				    </td>
				    <td align="center">
					<h3>Shutdown</h3><br />
					<button style="border: none; background: none; margin: 15px 0px;" id="shutdown" name="action" value="shutdown"><img src="/images/shutdown.png" border="0" alt="Shutdown" /></button>					
				    </td>
				</tr>
				<tr>
				<?php
				function isServiceActive($service_name) {
				    $status_output = shell_exec("systemctl is-active $service_name");
				    $status_output = trim($status_output);
				    return ($status_output === "active");
				}
				$service_name = "mmdvm-log-backup.timer";
				$is_active = isServiceActive($service_name);
				if ($is_active) {
				    echo '<td colspan="2"><input type="checkbox" name="purgeLogs" value="1" id="purge" /> <label for="purge">Purge Last Heard dashboard data on Shutdown / Reboot</label></td>';
				}
				?>
				</tr>
			    </table>
			</form>
		    <?php } ?>
		</div>
		<div class="footer">
		    Original Pi-Star / Pi-Star Dashboard, &copy; Andy Taylor (<code>MW0MWZ</code>) 2014-<?php echo date("Y"); ?>.<br />
			<a href="https://w0chp.net/w0chp-pistar-dash/" style="color: #ffffff; text-decoration:underline;">W0CHP-PiStar-Dash (WPSD)</a> by W0CHP<br />
		</div>
	    </div>
	</body>
    </html>
<?php
}
?>
