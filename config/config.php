<?php
# This is an auto-generated config-file!
# Be careful, when manual editing this!

define("MMDVMLOGPATH", "/var/log/pi-star");
define("MMDVMLOGPREFIX", "MMDVM");
define("YSFGATEWAYLOGPATH", "/var/log/pi-star");
define("YSFGATEWAYLOGPREFIX", "YSFGateway");
define("YSFGATEWAYINIPATH", "/etc");
define("YSFGATEWAYINIFILENAME", "ysfgateway");
define("P25GATEWAYLOGPATH", "/var/log/pi-star");
define("P25GATEWAYLOGPREFIX", "P25Gateway");
define("P25GATEWAYINIPATH", "/etc");
define("P25GATEWAYINIFILENAME", "p25gateway");
define("LINKLOGPATH", "/var/log/pi-star");

$config_file = '/etc/WPSD-Dashboard-Config.ini';

if (file_exists($config_file)) {
    $config = parse_ini_file($config_file, true);

    $callsign = $config['WPSD']['Callsign'] ?? 'M1ABC';

    // Set timezone
    if (!empty($config['WPSD']['Timezone'])) {
        date_default_timezone_set($config['WPSD']['Timezone']);
    } else {
        date_default_timezone_set('UTC');
    }

    // Set time format
    if (isset($config['WPSD']['TimeFormat'])) {
        define('TIME_FORMAT', $config['WPSD']['TimeFormat']);
    } else {
        define('TIME_FORMAT', '24');
    }

    // Set auto-update check
    if (isset($config['WPSD']['UpdateNotifier'])) {
        define('AUTO_UPDATE_CHECK', $config['WPSD']['UpdateNotifier'] === '1' ? 'true' : 'false');
    } else {
        define('AUTO_UPDATE_CHECK', 'false'); // Default to 'false' if not set
    }

    // Set dashboard language
    $DashLanguage = !empty($config['WPSD']['DashLanguage']) ? $config['WPSD']['DashLanguage'] : 'english_us';
}
?>
