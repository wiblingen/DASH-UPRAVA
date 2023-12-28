<?php
if (isset($_COOKIE['PHPSESSID']))
{
    session_id($_COOKIE['PHPSESSID']); 
}
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION) || !is_array($_SESSION) || (count($_SESSION, COUNT_RECURSIVE) < 10)) {
    session_id('wpsdsession');
    session_start();
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';        // Translation Code
    checkSessionValidity();
}

include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';	      // Translation Code
include_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';

// Sanity Check that this file has been opened correctly
if ($_SERVER["PHP_SELF"] == "/admin/advanced/jitter_test.php") {

  if (isset($_POST['group'])) {
    if ($_POST['group'] == "brandmeister") { $target = "BM"; }
    if ($_POST['group'] == "dmrplus")      { $target = "DMR+"; }
    if ($_POST['group'] == "hblink")       { $target = "HB"; }
    if ($_POST['group'] == "freedmr")      { $target = "FreeDMR"; }
    if ($_POST['group'] == "freedmr_sa")   { $target = "FD"; }
    if ($_POST['group'] == "sysx")	   { $target = "SystemX"; }
    if ($_POST['group'] == "xlx")          { $target = "XLX"; }
  } else { $target = ""; }

    if (!isset($_GET['ajax'])) {
	system('sudo touch /var/log/pi-star/pi-star_icmptest.log > /dev/null 2>&1 &');
	system('sudo echo "" > /var/log/pi-star/pi-star_icmptest.log > /dev/null 2>&1 &');
	system('sudo /usr/local/sbin/pistar-jittertest '.$target.' > /dev/null 2>&1 &');
	$_SESSION['jittertest-isrunning'] = 1;
    }
    
    // Sanity Check Passed.
    header('Cache-Control: no-cache');
    
    if (!isset($_GET['ajax'])) {
	//unset($_SESSION['update_offset']);
	if (file_exists('/var/log/pi-star/pi-star_icmptest.log')) {
	    $_SESSION['update_offset'] = filesize('/var/log/pi-star/pi-star_icmptest.log');
	}
	else {
	    $_SESSION['update_offset'] = 0;
	}
    }
    
    if (isset($_GET['ajax'])) {
	//session_start();
	if (!file_exists('/var/log/pi-star/pi-star_icmptest.log')) {
	    exit();
	}
	
	if (($handle = fopen('/var/log/pi-star/pi-star_icmptest.log', 'rb')) != false) {
	    if (isset($_SESSION['update_offset'])) {
		fseek($handle, 0, SEEK_END);
		if ($_SESSION['update_offset'] > ftell($handle)) { //log rotated/truncated
		    $_SESSION['update_offset'] = 0; //continue at beginning of the new log
		}
		
		$data = stream_get_contents($handle, -1, $_SESSION['update_offset']);
		
		$jitterIsRunning = shell_exec('ps ax | grep "/usr/local/sbin/pistar-jittertest" | grep -v grep') != null ? "YES" : "NO";
		$oldOffset = $_SESSION['update_offset'];
		
		$_SESSION['update_offset'] += strlen($data);
		echo "<pre>$data</pre>";
		
		// we reach the end of the test
		if (($oldOffset == $_SESSION['update_offset']) && (isset($_SESSION['jittertest-isrunning']) && ($_SESSION['jittertest-isrunning'] == 1)) && ($jitterIsRunning == "NO"))
		{
		    unset($_SESSION['jittertest-isrunning']);
		    echo "<pre>
--------------
Test Complete.
--------------
			</pre>";
		}
	    }
	    else {
		fseek($handle, 0, SEEK_END);
		$_SESSION['update_offset'] = ftell($handle);
	    }
	}
	exit();
    }
?>

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html lang="en">
  <head>
    <meta name="language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <meta http-equiv="Expires" content="0" />
    <title>WPSD <?php echo $lang['digital_voice']." ".$lang['dashboard']." - Jitter Test";?></title>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/browserdetect.php'; ?>
    <script type="text/javascript" src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
    <script type="text/javascript" src="/js/jquery-timing.min.js?version=<?php echo $versionCmd; ?>"></script>
    <script type="text/javascript">
    $(function() {
      $.repeat(1000, function() {
        $.get('/admin/advanced/jitter_test.php?ajax', function(data) {
          if (data.length < 1) return;
          var objDiv = document.getElementById("tail");
          var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
          $('#tail').append(data);
          if (isScrolledToBottom)
            objDiv.scrollTop = objDiv.scrollHeight;
        });
      });
    });
    </script>
  </head>
  <body>
  <div class="container">
  <?php include './header-menu.inc'; ?>
  <div class="contentwide">
  <table width="100%">
  <?php if (empty($target)) { ?>
  <tr><th>DMR Network Jitter Test</th></tr>
  <tr><td>
  <form method="post" action="./jitter_test.php">
  <p>
    BrandMeister:<input type="radio" value="brandmeister" name="group" /> |
    HB-Link:<input type="radio" value="hblink" name="group" /> |
    FreeDMR:<input type="radio" value="freedmr" name="group" /> | 
    FreeDMR Stand-alone Hosts:<input type="radio" value="freedmr_sa" name="group" /><br />
    SystemX (FreeSTAR):<input type="radio" value="sysx" name="group" /> |
    XLX Hosts:<input type="radio" value="xlx" name="group" /> |
    DMR+:<input type="radio" value="dmrplus" name="group" />
    <input type="submit" name="sumbit" value="Start Test" />
  </p>
  </form>
  </td></tr>
  <tr><td><p><b>Please select a network above.</b></p></td></tr>
  </table>
  </div>
  <div class="footer">
  <a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?>
  <br />
  </div>
  </div>
  </body>
  <?php } else { ?>

  <tr><th>DMR Network Jitter Test</th></tr>
  <tr><td>
  <form method="post" action="./jitter_test.php">
  <p>
    BrandMeister:<input type="radio" value="brandmeister" name="group" /> |
    HB-Link:<input type="radio" value="hblink" name="group" /> |
    FreeDMR:<input type="radio" value="freedmr" name="group" /> | 
    FreeDMR Stand-alone Hosts:<input type="radio" value="freedmr_sa" name="group" /><br />
    SystemX (FreeSTAR):<input type="radio" value="sysx" name="group" /> |
    XLX Hosts:<input type="radio" value="xlx" name="group" /> |
    DMR+:<input type="radio" value="dmrplus" name="group" />
    <input type="submit" name="sumbit" value="Start Test" />
  </p>
  </form>
  </td></tr>
  <tr><td><b>Test Results:</b></td></tr>
  <tr><td align="left"><div id="tail">Starting test...<br /></div></td></tr>
  </table>
  </div>
  <div class="footer">
  <a href="https://wpsd.radio/">WPSD</a> &copy; <code>W0CHP</code> 2020-<?php echo date("Y"); ?>
  <br />
  </div>
  </div>
  </body>
  </html>
  <?php } ?>

<?php
}
?>
