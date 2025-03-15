<?php
session_start();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if (!isset($_SESSION['oled_state'])) {
        $_SESSION['oled_state'] = 'on'; // Default to 'on'
    }

    if ($action == 'toggle') {
        // Toggle the OLED state
        $_SESSION['oled_state'] = ($_SESSION['oled_state'] == 'off') ? 'on' : 'off';
    }

    if ($_SESSION['oled_state'] == 'off') {
        $command = 'sudo /usr/sbin/i2cset -y 1 0x3c 0x00 0xAE';  // Off
    } else {
        $command = 'sudo /usr/sbin/i2cset -y 1 0x3c 0x00 0xAF';  // On
    }

    exec($command);

    exit;
}
?>

