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

$configfile = '/etc/ssh_tunnel';
$tempfile = '/tmp/ssh_tunnel.tmp';

// this is the function going to update your ini file
function update_ini_file($data, $filepath) {
    global $configfile;
    global $tempfile;
    $content = "";

    // Read the INI file contents
    $ini_content = file_get_contents($filepath);
    // Set the INI scanner option to treat values as literal strings
    $parsed_ini = parse_ini_string($ini_content, true, INI_SCANNER_RAW);

    foreach($data as $section=>$values) {
        // UnBreak special cases
        $section = str_replace("_", " ", $section);
        $content .= "[".$section."]\n";
        //append the values
        foreach($values as $key=>$value) {
            $content .= $key."=".$value."\n";
            $content .= "\n";
            // Prepare variables for systemd script with some sanitization
            ($key=="Enabled") && $ssh_tunnel_enabled=preg_replace('/[^0-9]/', '', $value);
            ($key=="SSH server address") && $ssh_server_address=preg_replace('/[^a-zA-Z0-9_.-]/', '', $value);
            ($key=="SSH server port") && $ssh_server_port=preg_replace('/\D/', '', $value);
            ($key=="SSH auth username") && $ssh_username=preg_replace('/[^a-zA-Z0-9_.-]/', '', $value);
            ($key=="Private key file") && $ssh_priv_key=preg_replace('/[^a-zA-Z0-9_.-\/]/', '', $value);
            ($key=="SSH management source port") && $ssh_tunnel_src=preg_replace('/\D/', '', $value);
            ($key=="SSH management destination port") && $ssh_tunnel_dst=preg_replace('/\D/', '', $value);
            ($key=="HTTP management source port") && $http_tunnel_src=preg_replace('/\D/', '', $value);
            ($key=="HTTP management destination port") && $http_tunnel_dst=preg_replace('/\D/', '', $value);
        }
    }

    // write it into file
    if (!$handle = fopen($filepath, 'w')) {
        return false;
    }

    $success = fwrite($handle, $content);
    fclose($handle);

    // Updates complete - copy the working file back to the proper location
    exec("sudo cp $tempfile $configfile");                      // Move the file back
    exec("sudo chmod 644 $configfile");                         // Set the correct runtime permissions
    exec("sudo chown root:root $configfile");                   // Set the owner

    $tempfile = '/tmp/ssh_tunnel_daemon.tmp';                   // Temporary service init file
    // Update OS systemd service
    exec("echo \"[Unit]\" > $tempfile");
    exec("echo \"Description=Reverse SSH tunnel\" >> $tempfile");
    exec("echo \"Wants=network-online.target\" >> $tempfile");
    exec("echo \"After=network-online.target\" >> $tempfile");
    exec("echo \"StartLimitIntervalSec=0\" >> $tempfile");
    exec("echo \"\" >> $tempfile");
    exec("echo \"[Service]\" >> $tempfile");
    exec("echo \"Type=simple\" >> $tempfile");
    exec("echo \"Restart=always\" >> $tempfile");
    exec("echo \"RestartSec=60\" >> $tempfile");
    exec("echo \"ExecStart=/usr/bin/ssh -qNn -o ServerAliveInterval=30 -o ServerAliveCountMax=3 -o ExitOnForwardFailure=yes -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -i $ssh_priv_key -R $ssh_tunnel_src:localhost:$ssh_tunnel_dst -R $http_tunnel_src:localhost:$http_tunnel_dst -p $ssh_server_port $ssh_username@$ssh_server_address\" >> $tempfile");
    exec("echo \"\" >> $tempfile");
    exec("echo \"[Install]\" >> $tempfile");
    exec("echo \"WantedBy=multi-user.target\" >> $tempfile");
    exec("sudo cp $tempfile /etc/systemd/system/ssh_tunnel.service");
    // Remove temp file
    exec("rm -f $tempfile");
    // Required after changes to systemd services
    exec("sudo systemctl daemon-reload");
    // If enabled in config, then enable systemd and restart the service
    ($ssh_tunnel_enabled=="1") && exec("sudo systemctl enable ssh_tunnel.service && sudo systemctl restart ssh_tunnel.service");
    // If disabled in config, then disable systemd and stop the service
    ($ssh_tunnel_enabled=="0") && exec("sudo systemctl disable ssh_tunnel.service && sudo systemctl stop ssh_tunnel.service");

    return $success;
}

require_once('edit_template.php');

?>

