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

$backgroundModeCellActiveColor = $_SESSION['CSSConfigs']['Background']['ModeCellActiveColor'];
$backgroundModeCellInactiveColor = $_SESSION['CSSConfigs']['Background']['ModeCellInactiveColor'];

$bmAPIkey = $_POST['bmAPIkey'];
$MYCALL = $_POST['MYCALL'];
$dmrID = $_POST['dmrID'];

function getDeviceLogs($bmAPIkey, $MYCALL, $dmrID, $backgroundModeCellActiveColor, $backgroundModeCellInactiveColor) {

    $apiHeaders = array(
	'Content-Type: application/json',
	'Authorization: Bearer ' . $bmAPIkey,
	'User-Agent: WPSD Dashboard for ' . $dmrID,
    );

    $jsonContext = stream_context_create(array('http' => array(
	'header' => $apiHeaders,
	'timeout' => 10,
    )));

    $data = json_decode(@file_get_contents("https://api.brandmeister.network/v2/deviceLogs/byCall/$MYCALL", false, $jsonContext), true);

    if ($data === null) {
	echo 'Error decoding JSON';
    } else {
	echo '<table width="100%">';
	echo '<tr><th>Timestamp</th><th>BM Master</th><th>Hotspot/Repeater ID</th><th>Message</th></tr>';
	foreach ($data as $item) {
	    echo '<tr>';
	    $localTimestamp = convertZuluToLocal($item['timestamp']);
	    echo '<td style="white-space:normal;" class="divTableCellMono" align="left">' . htmlspecialchars($localTimestamp) . '</td>';
	    echo '<td style="white-space:normal;"class="divTableCellMono" align="left">' . htmlspecialchars($item['master']) . '</td>';
	    echo '<td style="white-space:normal;" class="divTableCellMono" align="left">' . htmlspecialchars($item['repeaterid']) . '</td>';
	    $message = preg_replace_callback('/\)\s(\w)/', function($matches) {
		return ') ' . ucfirst($matches[1]);
	    }, $item['message']);
	    $message = str_replace("Verified", "<span class='larger' style='font-weight:bold;color:$backgroundModeCellActiveColor;'>Verified</span>", $message);
	    $message = str_replace("failed", "<span class='larger' style='font-weight:bold;color:$backgroundModeCellInactiveColor;'>failed</span>", $message);
	    $message = str_replace("wrong configuration", "<span class='larger' style='font-weight:bold;color:$backgroundModeCellInactiveColor;'>wrong configuration</span>", $message);
	    echo '<td style="white-space:normal;" class="divTableCellMono" align="left">' . $message . '</td>';
	    echo '</tr>';
	}
	echo '</table>';
    }
}

getDeviceLogs($bmAPIkey, $MYCALL, $dmrID, $backgroundModeCellActiveColor, $backgroundModeCellInactiveColor);

?>
