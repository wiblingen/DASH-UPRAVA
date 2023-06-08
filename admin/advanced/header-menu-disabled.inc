<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php'; ?>
<div class="header">
    <div class="SmallHeader shLeft">Hostname: <?php echo exec('cat /etc/hostname'); ?></div>
    <div class="SmallHeader shRight">
       <div id="CheckUpdate">
       <?php
          include $_SERVER['DOCUMENT_ROOT'].'/includes/checkupdates.php';
       ?>
       </div><br />
    </div>
    <h1>WPSD - Digital Voice Dashboard Advanced</h1>
    <p>
	<div class="navbar">
	    <a class="menuconfig" href="/admin/configure.php"><?php echo $lang['configuration'];?></a>
	    <a class="menubackup" href="/admin/config_backup.php"><?php echo $lang['backup_restore'];?></a>
	    <!--<a class="menuupgrade" href="/admin/advanced/upgrade.php">Upgrade</a>-->
	    <a class="menuupdate" href="/admin/update.php"><?php echo $lang['update'];?></a>
	    <a class="menuadmin" href="/admin/"><?php echo $lang['admin'];?></a>
	    <?php if (file_exists("/etc/dstar-radio.mmdvmhost")) { ?>
	    <a class="menulive" href="/live/">Live Caller</a>
	    <?php } ?>
	    <a class="menudashboard" href="/"><?php echo $lang['dashboard'];?></a>
	</div>

    </p>
</div>
