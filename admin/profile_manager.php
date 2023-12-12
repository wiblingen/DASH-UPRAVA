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
if ($_SERVER["PHP_SELF"] == "/admin/profile_manager.php") {
    // Sanity Check Passed.
    header('Cache-Control: no-cache');

if (isset($_SESSION['CSSConfigs']['Background'])) {
    $backgroundModeCellActiveColor = $_SESSION['CSSConfigs']['Background']['ModeCellActiveColor'];
}

$config_dir = "/etc/WPSD_config_mgr";
$curr_config_raw = trim(file_get_contents('/etc/.WPSD_config'));
$curr_config = $curr_config_raw;
$saved = date("M d Y @ h:i A", filemtime("$config_dir" . "/". "$curr_config"));
if (file_exists('/etc/.WPSD_config') && count(glob("$config_dir/*")) > 0) {
    if (is_dir("$config_dir" . "/" ."$curr_config") != false ) {
    	 $curr_config = "<span class='larger' style='font-weight:bold;color:$backgroundModeCellActiveColor;'>".trim(file_get_contents('/etc/.WPSD_config'))."</span><br /><small>(Saved: ".$saved."</small>)\n";
    } else {
	$no_raw_profile = true;
	$curr_config = "<p><i class='fa fa-exclamation-circle'></i> Current Profile Deleted! You may want to switch to a saved profile, or save a new profile.</p>";
    }
} else {
    $no_raw_profile = true;
    $curr_config = "<p>No saved profiles yet.</p>";
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
	    <title>WPSD <?php echo $lang['digital_voice']." ".$lang['dashboard']."";?> - Profile Manager</title>
	    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
	    <script type="text/javascript" src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
	    <script type="text/javascript" src="/js/functions.js?version=<?php echo $versionCmd; ?>"></script>
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
                    <h1>WPSD <?php echo $lang['digital_voice']; ?> - Profile Manager</h1>
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
		      <a class="menuadmin noMob" href="/admin/"><?php echo $lang['admin'];?></a>
		      <a class="menudashboard" href="/"><?php echo $lang['dashboard'];?></a>
 	    	</div>
                </div>
		<div class="contentwide">
		    <h3 class="larger">Profile Manager</h3>
		    <?php if (!empty($_POST)) { ?>
		    <table width="100%">
			    <?php
			    if ( escapeshellcmd($_POST["save_current_config"]) || escapeshellcmd($_POST['curr_config'] )) { // new or current profile save posted
                                if (escapeshellcmd($_POST["save_current_config"])) { // new profile, need new descr.
                                    $desc = $_POST['config_desc'];
                                } else if (escapeshellcmd($_POST['curr_config'])) { // current profile, use existing descr.
                                    $desc = $_POST['curr_config'];
                                }
				if ($desc == "") {
				    echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-times-circle" aria-hidden="true"></i> You need to provide a Profile Description!</p>
				    Page reloading...<br /><br />
				    <script language="JavaScript" type="text/javascript">
				    setTimeout("location.href = \''.$_SERVER["PHP_SELF"].'\'", 5000);
				    </script>
				    </td></tr>';
				} else if (!preg_match('/^[a-zA-Z0-9\s]+$/', $desc)) {
				    echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-ban" aria-hidden="true"></i> Non-Alpha-Numeric/Special Characters are not Permitted...</p>
				    Page reloading...<br /><br />
				    <script language="JavaScript" type="text/javascript">
                                    setTimeout("location.href = \''.$_SERVER["PHP_SELF"].'\'", 5000);
				    </script>
				    </td></tr>';
				} else {
				    $desc = escapeshellarg($desc);
				    exec('sudo mount -o remount,rw /');
				    exec("sudo mkdir -p /etc/WPSD_config_mgr/$desc > /dev/null");
				    $profileDir = "/etc/WPSD_config_mgr/$desc";
                            	    exec("sudo rm -rf $profileDir > /dev/null")."\n";
                            	    exec("sudo mkdir $profileDir > /dev/null")."\n";
                            	    if (exec('cat /etc/dhcpcd.conf | grep "static ip_address" | grep -v "#"')) {
                                        exec("sudo cp /etc/dhcpcd.conf $profileDir > /dev/null")."\n";
                            	    }
                            	    exec("sudo cp /etc/wpa_supplicant/wpa_supplicant.conf $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/hostapd/hostapd.conf $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/pistar-css.ini $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/aprsgateway $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/ircddbgateway $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/mmdvmhost $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dapnetgateway $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/pistar-css.ini $profileDir > /dev/null");
                            	    exec("sudo cp /etc/p25gateway $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/ysfgateway $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dmr2nxdn $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dmr2ysf $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/nxdn2dmr $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/ysf2dmr $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dgidgateway $profileDir > /dev/null");
                            	    exec("sudo cp /etc/nxdngateway $profileDir > /dev/null");
                            	    exec("sudo cp /etc/m17gateway $profileDir > /dev/null");
                            	    exec("sudo cp /etc/ysf2nxdn $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/ysf2p25 $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dmrgateway $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/starnetserver $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/timeserver $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dstar-radio.* $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/pistar-remote $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/hosts $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/hostname $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/bmapi.key $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/dapnetapi.key $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/default/gpsd $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/*_paused $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/.CALLERDETAILS $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/.pistar-css.ini.user $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /etc/.TGNAMES $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /usr/local/etc/RSSI.dat $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /var/www/dashboard/config/ircddblocal.php $profileDir > /dev/null")."\n";
                            	    exec("sudo cp /var/www/dashboard/config/config.php $profileDir > /dev/null")."\n";
			    	    exec("sudo cp /var/www/dashboard/config/language.php $profileDir > /dev/null")."\n";
				    exec("sudo sh -c 'cp -a /root/*Hosts.txt' $profileDir > /dev/null")."\n";
				    exec("sudo sh -c \"echo $desc > /etc/.WPSD_config\"");
				    echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-check-square" aria-hidden="true"></i> Saved Current Settings to Profile, '.$desc.'</p>
				    Page reloading...<br /><br />
				    <script language="JavaScript" type="text/javascript">
                                    setTimeout("location.href = \''.$_SERVER["PHP_SELF"].'\'", 3000);
				    </script>
				    </td></tr>';
				}
			    }
			    else if ( escapeshellcmd($_POST["restore_config"]) ) {
				if (empty($_POST['configs'])) {
				     echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-times-circle" aria-hidden="true"></i> No profile selected!</p>
                                       Page reloading...<br /><br />
                                       <script language="JavaScript" type="text/javascript">
                                       setTimeout("location.href = \'' . $_SERVER["PHP_SELF"] . '\'", 5000);
                                       </script>
                                       </td></tr>';
				} else {
				    $resto = escapeshellarg($_POST['configs']);
				    $profileDir = "/etc/WPSD_config_mgr/$resto";
				    exec('sudo mount -o remount,rw /');
				    exec("sudo sh -c 'mv $profileDir/*.php /var/www/dashboard/config/' > /dev/null");
				    exec("sudo sh -c 'cp -a $profileDir/*Hosts.txt /root/' > /dev/null");
				    exec("sudo sh -c 'rm -rf $profileDir/*Hosts.txt' > /dev/null");
				    exec("sudo sh -c 'cp -a $profileDir/* /etc/' > /dev/null");
				    exec("sudo sh -c 'cp -a $profileDir/.CALLERDETAILS /etc/' > /dev/null");
				    exec("sudo sh -c 'cp -a $profileDir/.TGNAMES /etc/' > /dev/null");
				    exec("sudo sh -c 'cp -a $profileDir/.pistar-css.ini.user /etc/' > /dev/null");
                                    exec("sudo cp /var/www/dashboard/config/ircddblocal.php $profileDir > /dev/null")."\n";
                                    exec("sudo cp /var/www/dashboard/config/config.php $profileDir > /dev/null")."\n";
                                    exec("sudo cp /var/www/dashboard/config/language.php $profileDir > /dev/null")."\n";
				    exec("sudo chown www-data:www-data /var/www/dashboard/ > /dev/null");
				    exec("sudo sh -c 'cp -a /root/*Hosts.txt $profileDir' > /dev/null");
				    exec("sudo sh -c \"echo ".$_POST['configs']." > /etc/.WPSD_config\"");
				    exec("sudo wpsd-services restart > /dev/null &");
				    echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-check-square" aria-hidden="true"></i> Switched to Profile, '.$resto.'</p>
				    Page reloading...<br /><br />
				    <script language="JavaScript" type="text/javascript">
                                    setTimeout("location.href = \''.$_SERVER["PHP_SELF"].'\'", 3000);
				    </script>
				    </td></tr>';
				}
			    }
			    else if ( escapeshellcmd($_POST["remove_config"]) ) {
				if (empty($_POST['delete_configs'])) {
				    echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-times-circle" aria-hidden="true"></i> No profile selected for deletion!</p>
					Page reloading...<br /><br />
					<script language="JavaScript" type="text/javascript">
					setTimeout("location.href = \'' . $_SERVER["PHP_SELF"] . '\'", 5000);
					</script>
				</td></tr>';
				} else {
				    $del = escapeshellarg($_POST['delete_configs']);
				    exec('sudo mount -o remount,rw /');
				    exec("sudo rm -rf /etc/WPSD_config_mgr/$del > /dev/null");
				    echo '<tr><td colspan="3"><p class="larger"><i class="fa fa-check-square" aria-hidden="true"></i> Deleted Profile, ' .$del.'</p>
				    Page reloading...<br /><br />
				    <script language="JavaScript" type="text/javascript">
                                    setTimeout("location.href = \''.$_SERVER["PHP_SELF"].'\'", 3000);
				    </script>
				    </td></tr>';
				}
			    }
			    unset($_POST);
			    ?>
		    </table>
		    <?php }
		    else { ?>
                        <?php
                        // check that no modes are paused. If so, bail and direct user to unpause...
                        $is_paused = glob('/etc/*_paused');
                        $repl_str = array('/\/etc\//', '/_paused/');
                        $paused_modes = preg_replace($repl_str, '', $is_paused);
                        if (!empty($is_paused)) {
                                echo '<h1>IMPORTANT:</h1>';
                                echo '<p><b>One or more modes have been detected to have been "paused" by you</b>:</p>';
                                foreach($paused_modes as $mode) {
                                        echo "<h3>$mode</h3>";
                                }
                                echo '<p>You must "resume" all of the modes you have paused in order to make any configuration changes...</p>';
                                echo '<p>Go the <a style="text-decoration:underline;color:inherit;" href="/admin/?func=mode_man">Instant Mode Manager page to Resume the paused mode(s)</a>. Once that\'s completed, this configuration page will be enabled.</p>';
                                echo '<br />'."\n";
                                echo '<br />';
                        } else {
                        ?>
		    <table width="100%">
			<tr>
			    <th width="33%" class="larger">Switch Profile</th>
			    <th width="33%" class="larger">Current Running Profile</th>
			    <th width="33%" class="larger">Save a New Profile</th>
			</tr>

			<tr>
                            <td style="white-space:normal;padding: 3px;">
                            <?php
                                if (count(glob("$config_dir/*")) == 0) {
                            ?>
                                <p>No saved profiles yet.</p>
                            <?php } else { ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="list_configs">
                                    <label for="list_profiles">Select a Profile:</label>
                                    <select name="configs" id="list_profiles" form="list_configs">
				    <?php
				    if ($no_raw_profile != true) {
					echo "              <option name='selected_config' value='$curr_config_raw' selected>$curr_config_raw</option>\n";
				    } else{ 
					echo '		    <option value="" disabled selected>Select...</option>';
				    }
					
				    foreach ( glob("$config_dir/*") as $dir ) {
					$config_file = str_replace("$config_dir/", "", $dir);
					echo "              <option name='selected_config' value='$config_file'>$config_file</option>\n";
				    }
				    ?>
                                    </select>
                                    <input type="submit" name="restore_config" value="Switch to Profile">
                                </form>
				<p><i class='fa fa-question-circle'></i> Instantly Switch to a Saved Profile</p>
                            <?php } ?>
                            </td>

			    <td style="white-space:normal;padding: 3px;">
			    <p>
			    <?php echo $curr_config; ?>
			    <?php if ($no_raw_profile != true) { ?>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="save_running_config">
				    <div<>Save Current Settings to This Running Profile: </div>
				    <input type="hidden" name="curr_config" value="<?php echo $curr_config_raw; ?>">
				    <button type="submit" name="running_config">Quick Save</button>
				</form>
			    <?php } ?>
			    </p>
			    </td>

			    <td style="white-space:normal;padding: 3px;">
				<p>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="save_config">
				    <label for="profile_desc">Description:</label>
				    <input type="text" placeholder="Enter Short Description" name="config_desc" id="profile_desc" size="27" maxlength="255">
				    <input type="submit" name="save_current_config" value="Save Profile">
				</form>
				</p>
				<p><i class='fa fa-question-circle'></i> Save Current Settings to a New Profile.<br /><small>(Spaces in Profile descriptions <em>are</em> permitted.)</small></p>
			   </td>
			</tr>

			<tr>
			    <td colspan="3" style="white-space:normal;padding: 3px;">
				<p>This function allows you save Profiles of your setups;  and then switch to/re-apply them as-needed for different uses, etc. <em>Switching profiles is instant.</em></p>
				<p><i class="fa fa-exclamation-circle"></i> If you make any subsequent system/configuration changes after saving/running a profile, you must save the profile again keep those changed in the current running profile.</p>
			    </td>
			</tr>
		    </table>
		</form>
		
		<p>
		<br />
		<table align="center" style="width:60%;max-width:65%;">
		    <tr>
			<th class="larger" colspan="3"><i class='fa fa-exclamation-triangle'></i> Delete Profile <i class='fa fa-exclamation-triangle'></i></th>
		    </tr>

		    <tr>
			<td colspan="3" style="white-space:normal;padding: 3px;">
			<p>
			<?php
			    if (count(glob("$config_dir/*")) == 0) {
			?>
			    <p>No saved profiles yet.</p>
			<?php } else { ?>
			    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="del_configs">
				<label for="profiles_avail">Select a Profile:</label>
				<select name="delete_configs" id="profiles_avail" form="del_configs">
				<option value="" disabled selected>Select...</option>
				<?php
				    foreach ( glob("$config_dir/*") as $dir ) {
					$config_file = str_replace("$config_dir/", "", $dir);
					echo "	<option name='selected_config' value='$config_file'>$config_file</option>\n";
				}
				?>
				</select>
				<input style="background:crimson;color:white;" type="submit" name="remove_config" value="Delete Profile">
			    </form>
			<?php } ?>
		 	</p>
			</td>
		    </tr>
		</table>
		<br />
		</p>
		<?php } ?>
	    <?php } ?>

	    </div>

	    <div class="footer">
		<a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?><br />
	    </div>
	</div>
    </body>
</html>
<?php
}
?>
