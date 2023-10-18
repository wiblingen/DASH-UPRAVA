<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
include_once $_SERVER['DOCUMENT_ROOT'].'/config/version.php';         // Version Lib
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';        // Translation Code

$UUID = $_SESSION['PiStarRelease']['Pi-Star']['UUID'];
$CALL = $_SESSION['PiStarRelease']['Pi-Star']['Callsign'];

$headers = stream_context_create(Array("http" => Array("method"  => "GET",
                                                       "timeout" => 10,
                                                       "header"  => "User-agent: WPSD-Messages - $CALL $UUID",
                                                       'request_fulluri' => True )));
// buster EOL!!!! YAY!!!!!!! \o/
$osReleaseFile = '/etc/os-release';
if (file_exists($osReleaseFile)) {
    $osReleaseContents = file_get_contents($osReleaseFile);
    $pattern = '/VERSION_CODENAME=(\w+)/';
    if (preg_match($pattern, $osReleaseContents, $matches)) {
        $debianCodename = $matches[1];
    }
    if ($debianCodename === "buster") {
	$result = @file_get_contents('https://wpsd-swd.w0chp.net/WPSD-SWD/WPSD_Messages/raw/branch/master/no-mo-busta-yo.html', false, $headers);
	echo $result;
    }
}
// older wpsd with very old uuid scheme
$UUID = $_SESSION['PiStarRelease']['Pi-Star']['UUID'];
$uuidNeedle = "-";
if (strpos($UUID, $uuidNeedle) !== false) {
    $result = @file_get_contents('https://wpsd-swd.w0chp.net/WPSD-SWD/WPSD_Messages/raw/branch/master/no-mo-busta-yo.html', false, $headers);
    echo $result;
}
// F1RMB detected
if( strpos(file_get_contents("/etc/pistar-release"),"-RMB") !== false) {
    $result = @file_get_contents('https://wpsd-swd.w0chp.net/WPSD-SWD/WPSD_Messages/raw/branch/master/update-req-uuid.html', false, $headers);
    echo $result;
}
?>
