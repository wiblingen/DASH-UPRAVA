<?php
session_set_cookie_params(0, "/");
session_name("WPSD_Session");
session_id('wpsdsession');
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/classes/class-wpsd-functions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/ircddblocal.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';

$MYCALL = strtoupper($callsign);
$_SESSION['MYCALL'] = $MYCALL;

// Clear session data (page {re}load);
$keepSessions = ['MYCALL'];
foreach ($_SESSION as $key => $value) {
    if (!in_array($key, $keepSessions)) {
        unset($_SESSION[$key]);
    }
}

checkSessionValidity();

if (isset($_SESSION['CSSConfigs']['Text'])) {
    $textSections = $_SESSION['CSSConfigs']['Text']['TextSectionColor'];
}
if(empty($_GET['func'])) {
    $_GET['func'] = "main";
}
if(empty($_POST['func'])) {
    $_POST['func'] = "main";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
	<meta name="language" content="English" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="pragma" content="no-cache" />
	<link rel="shortcut icon" href="/images/favicon.ico?version=<?php echo $versionCmd; ?>" type="image/x-icon" />
	<title><?php echo "$MYCALL"." - " . __( 'Dashboard' );?></title>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css?version=<?php echo $versionCmd; ?>" />
	<?php include_once "../config/browserdetect.php"; ?>
	<script type="text/javascript" src="/js/jquery.min.js?version=<?php echo $versionCmd; ?>"></script>
	<script type="text/javascript" src="/js/functions.js?version=<?php echo $versionCmd; ?>"></script>
	<script type="text/javascript">
	 $.ajaxSetup({ cache: false });
   window.time_format = '<?php echo constant("TIME_FORMAT"); ?>';
	</script>
        <script type="text/javascript">
          $(document).ready(function(){
            setInterval(function(){
                $("#CheckUpdate").load(window.location.href + " #CheckUpdate" );
                },10000);
            });
          $(document).ready(function(){
            setInterval(function(){
                $("#CheckMessage").load(window.location.href + " #CheckMessage" );
                },3600000);
            });
          $(document).ready(function() {
            $('.menuradioinfo').click(function() {
              $("#radioInfo").slideToggle(function() {
                localStorage.setItem('radioinfo_visible', $(this).is(":visible"));
              })
            });
            $('#radioInfo').toggle(localStorage.getItem('radioinfo_visible') === 'true');
          });
          function clear_activity() {
            if ( 'true' === localStorage.getItem('filter_activity') || jQuery( '.filter-activity-max-wrap' ).length > 0 ) {
              max = localStorage.getItem( 'filter_activity_max') || 1;
              jQuery('.filter-activity-max').attr('value',max);
              jQuery('.activity-duration').each( function(i,el) {
                duration = parseFloat( jQuery(this).text() );
                if ( duration < max ) {
                  jQuery(this).closest('tr').hide();
                } else {
                  jQuery(this).closest('tr').addClass('good-activity');
                }
              });
              

              jQuery('.good-activity').each( function( i,el ) {
                if (i % 2 === 0) {
                /* we are even */
                jQuery(el).addClass('even');
              } else {
                jQuery(el).addClass('odd');
              }
              });
            }
          };
          function setFilterActivity(obj) {
            localStorage.setItem('filter_activity', obj.checked);
            $.ajax({
              type: "POST",
              url: '/mmdvmhost/filteractivity_ajax.php',
              data:{
                action: obj.checked
              },
            });
          }
          function setFilterActivityMax(obj) {
            max = obj.value || 1;
            localStorage.setItem('filter_activity_max', obj.value);
          }
	</script>
  <script>
    document.addEventListener('keydown', function(event) {
      if ( event.key === 'S' || event.keyCode === 83 ) {
        window.location.href = '/mmdvmhost/export-lh.php';
      }
    });
  </script>
    </head>
    <body>
	<div class="container">
	    <div class="header">
               <div class="SmallHeader shLeft noMob"><a style="border-bottom: 1px dotted;" class="tooltip" href="#"><?php echo __( 'Hostname' ).": ";?> <span><strong>System IP Address<br /></strong><?php echo str_replace(',', ',<br />', exec("hostname -I | awk '{print $1}'"));?> </span>  <?php echo exec('cat /etc/hostname'); ?></a></div>
	       <div class="SmallHeader shRight noMob">
	         <div id="CheckUpdate">
       		  <?php
          	      include $_SERVER['DOCUMENT_ROOT'].'/includes/checkupdates.php';
       		  ?>
       	          </div><br />
   	        </div>
		<h1>WPSD <?php echo __( 'Dashboard for' )." <code style='font-weight:550;'>".$_SESSION['MYCALL']."</code>"; ?></h1>
		<div id="CheckMessage">
		<?php
		    include('../includes/messages.php');
		?>
		</div>

 		<div class="navbar">
                <script type= "text/javascript">
                function reloadDateTime(){
                  $( '#timer' ).html( _getDatetime( window.time_format ) );
                  setTimeout(reloadDateTime,1000);
                }
                reloadDateTime();
                </script>
		<div class="headerClock">
		    <span id="timer"></span>
		</div>
			<a class="menuradioinfo" href='#'>Radio Info</a>
			<?php if (file_exists("/etc/dstar-radio.mmdvmhost")) { ?>
			<a class="menulive" href="/live/">Live Caller</a>
			<?php } ?>
			<a class="menudashboard" href="/"><?php echo __( 'Dashboard' );?></a>
		    </div> 
	    </div>
	    <?php
            // Output some default features
	    if (file_exists('/etc/dstar-radio.mmdvmhost')) {
                echo '<div class="contentwide">'."\n";
                echo '<script type="text/javascript">'."\n";
                echo 'function reloadRadioInfo(){'."\n";
                echo '  $("#radioInfo").load("/mmdvmhost/radioinfo.php",function(){ setTimeout(reloadRadioInfo, 1000) });'."\n";
                echo '}'."\n";
                echo 'setTimeout(reloadRadioInfo, 1000);'."\n";
                echo '</script>'."\n";
                echo '<div id="radioInfo">'."\n";
                include '../mmdvmhost/radioinfo.php';
                echo '</div>'."\n";
                echo '<br class="noMob" />'."\n";
		echo '<script type="text/javascript">'."\n";
		echo 'function setLastCaller(obj) {'."\n";
		echo '    if (obj.checked) {'."\n";
		echo "        $.ajax({
                	        type: \"POST\",
  	          	        url: '/mmdvmhost/callerdetails_ajax.php',
                	        data:{action:'enable'},
				success: function(data) { 
     				    $('#lcmsg').html(data).fadeIn('slow');
				    $('#lcmsg').html(\"<div style='padding:8px;font-style:italic;font-weight:bold;'>For optimal performance, the number of Last Heard rows will be decreased while Caller Details function is enabled.</div>\").fadeIn('slow')
     				    $('#lcmsg').delay(4000).fadeOut('slow');
				}
         	             });";
		echo '    }'."\n";
		echo '    else {'."\n";
		echo "        $.ajax({
	                        type: \"POST\",
	                        url: '/mmdvmhost/callerdetails_ajax.php',
	                        data:{action:'disable'},
				success: function(data) { 
     				    $('#lcmsg').html(data).fadeIn('slow');
				    $('#lcmsg').html(\"<div style='padding:8px;font-style:italic;font-weight:bold;'>Caller Details function disabled. Increasing Last Heard table rows to user preference (if set) or default (40).</div>\").fadeIn('slow')
     				    $('#lcmsg').delay(4000).fadeOut('slow');
				}
	                      });";
		echo '    }'."\n";
		echo '}'."\n";
		echo '</script>'."\n";
		echo '<div id="lcmsg" style="background:#d6d6d6;color:black; margin:0 0 10px 0;"></div>'."\n";

		echo '<script>
		  async function fetchData(url, targetElement) {
		    try {
		      const response = await fetch(url);
		      const data = await response.text();
		      $(targetElement).html(data);
		    } catch (error) {
		      console.error(`Error fetching data from ${url}:`, error);
		    }
		  }
		
                  function reloadDynData() {
                    fetchData("/mmdvmhost/last_heard_table.php", "#lastHeard");
                    fetchData("/mmdvmhost/local_tx_table.php", "#localTxs");';
                    if(isset($_SESSION['WPSDrelease']['WPSD']['ProcNum']) && ($_SESSION['WPSDrelease']['WPSD']['ProcNum'] >= 4)) {
                        echo 'fetchData("/mmdvmhost/caller_details_table.php", "#liveCallerDeets");';
                    }
                  echo '
                  }';
                  if(isset($_SESSION['WPSDrelease']['WPSD']['ProcNum']) && ($_SESSION['WPSDrelease']['WPSD']['ProcNum'] >= 4)) {
                      echo "setInterval(reloadDynData, 1500);";
                  } else {
                      echo "setInterval(reloadDynData, 2500);";
                  }
                echo '
                </script>';

		echo '<script>'."\n";
		echo 'function setLHTGnames(obj) {'."\n";
		echo '    if (obj.checked) {'."\n";
		echo "        $.ajax({
                	        type: \"POST\",
  	          	        url: '/mmdvmhost/tgnames_ajax.php',
                	        data:{action:'enable'},
                                success: function(data) { 
                                    $('#lcmsg').html(data).fadeIn('slow');
                                    $('#lcmsg').html(\"<div style='padding:8px;font-style:italic;font-weight:bold;'>Talkgroup Names display enabled: Please wait until data populated.</div>\").fadeIn('slow')
                                    $('#lcmsg').delay(4000).fadeOut('slow');
                                }
         	             });";
		echo '    }'."\n";
		echo '    else {'."\n";
		echo "        $.ajax({
	                        type: \"POST\",
	                        url: '/mmdvmhost/tgnames_ajax.php',
	                        data:{action:'disable'},
                                success: function(data) { 
                                    $('#lcmsg').html(data).fadeIn('slow');
                                    $('#lcmsg').html(\"<div style='padding:8px;font-style:italic;font-weight:bold;'>Talkgroup Names display disabled: Please wait until data is cleared.</div>\").fadeIn('slow')
                                    $('#lcmsg').delay(4000).fadeOut('slow');
                                }
	                      });";
		echo '    }'."\n";
		echo '}'."\n";
		echo '</script>'."\n";

            	echo '<div id="liveCallerDeets">'."\n";
            	echo '</div>'."\n";

            	if (!file_exists('/etc/.CALLERDETAILS')) {
                    echo '<div id="lastHeard" style="margin-top:-20px;">'."\n";
            	} else {
                    echo '<div id="lastHeard">'."\n";
            	}
            	echo '</div>'."\n";

            	echo '<div id="localTxs" style="margin-top: 20px;">'."\n";
            	echo '</div>'."\n";

		// If POCSAG is enabled, show the information panel
		$testMMDVModePOCSAG = getConfigItem("POCSAG", "Enable", $_SESSION['MMDVMHostConfigs']);
        	if ( $testMMDVModePOCSAG == 1 ) {
            	    if ($_SERVER["PHP_SELF"] == "/simple/index.php") { // display pages in pocsag mgr or main dash page only with no other func requested
	                $myOrigin = ($_SERVER["PHP_SELF"] == "/admin/index.php" ? "admin" : "other");
		    
		    	echo '<script type="text/javascript">'."\n";
		    	echo 'var pagesto;'."\n";
		    	echo 'function setPagesAutorefresh(obj) {'."\n";
	            	echo '        pagesto = setTimeout(reloadPages, 10000, "?origin='.$myOrigin.'");'."\n";
		    	echo '}'."\n";
		    	echo 'function reloadPages(OptStr){'."\n";
		    	echo '    $("#Pages").load("/mmdvmhost/pocsag_table.php"+OptStr, function(){ pagesto = setTimeout(reloadPages, 10000, "?origin='.$myOrigin.'") });'."\n";
		    	echo '}'."\n";
		    	echo 'pagesto = setTimeout(reloadPages, 10000, "?origin='.$myOrigin.'");'."\n";
		    	echo '</script>'."\n";
		    	echo "\n".'<div id="Pages">'."\n";
		    	include '../mmdvmhost/pocsag_table.php';				// POCSAG Messages
		    	echo '</div>'."\n";
		    }
    		}
	    } else {
		echo '<div class="contentwide">'."\n";
		// Instance not configured...
		echo "<h1>New Installation...</h1>\n";
		echo "<p>Your installation needs to be configured.</p>\n";
		echo "<p>You will be redirected to the configuration page in 15 seconds...</p>\n";
		echo '<script type="text/javascript">setTimeout(function() { window.location="/admin/configure.php";},15000);</script>'."\n";
	    }
	?>
	</div>
<?php include $_SERVER['DOCUMENT_ROOT']. '/includes/footer.php'; ?>	
	</div>
    <?php
    if($_SESSION['WPSDdashConfig']['WPSD']['PhoneticCallsigns'] == "1"){
        echo '<script src="/js/phonetic-callsigns.js"></script>';
    }
    ?>
    <?php echo wpsd()->user_js(); ?>
    <script>
    reloadDateTime();
    reloadDynData();
    </script>
    </body>
</html>

