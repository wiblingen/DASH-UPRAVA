<?php

if (!isset($_SESSION) || !is_array($_SESSION)) {
    session_id('wpsdsession');
    session_start();
    
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
    include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';        // Translation Code
    checkSessionValidity();
}

if ($osVer >= 12) { // Bookworm uses NetworkManager
    $editorname = 'NetworkManager Connections';
    $configDirectory = '/etc/NetworkManager/system-connections/';
    $tempfile = '/tmp/nm_edit_temp.tmp';
    $servicenames = array('');
    $connectionFiles = glob($configDirectory . '*.nmconnection');
} else { // WPA supplicant
    $editorname = 'WPA Supplicant';
    $configfile = '/etc/wpa_supplicant/wpa_supplicant.conf';
    $tempfile = '/tmp/k45s7h5s9k3.tmp';
    $servicenames = array();
}

require_once('fulledit_template.php');

?>
