<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config

if (constant("TIME_FORMAT") == "24") {
    $local_time = date('H:i:s T, M j');
} else {
    $local_time = date('h:i:s A T, M j');
}

echo $local_time;

?>
