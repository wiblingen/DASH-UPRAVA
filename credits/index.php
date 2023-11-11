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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
    <head>
	<meta name="robots" content="index" />
	<meta name="robots" content="follow" />
	<meta name="language" content="English" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="pragma" content="no-cache" />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
	<meta http-equiv="Expires" content="0" />
	<title>W0CHP-PiStar-Dash Credits</title>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="/css/pistar-css.php?version=<?php echo $versionCmd; ?>" />
    </head>
    <body>
	<div class="container">
		<div class="header">
                    <div style="font-size: 10px; text-align: left; padding-left: 8px; float: left;">Hostname: <?php echo exec('cat /etc/hostname'); ?></div><div style="font-size: 10px; text-align: right; padding-right: 8px;">Pi-Star: <?php echo $_SESSION['PiStarRelease']['Pi-Star']['Version'].'<br />';?>
                    <?php if (constant("AUTO_UPDATE_CHECK") == "true") { ?>
                    <div id="CheckUpdate"><?php echo $version; system('/usr/local/sbin/pistar-check4updates'); ?></div></div>
                    <?php } else { ?>
                    <div id="CheckUpdate"><?php echo $version; ?></div></div>
                    <?php } ?>
                    <h1>Pi-Star <?php echo $lang['digital_voice']; ?> Credits</h1>
                    <p>
		     <?php include $_SERVER['DOCUMENT_ROOT'].'/includes/navbar.php'; ?> 
                    </p>
            </div>
            <div class="contentwide">

		    <div class="divTableCellSans">
			<h2 style="color:inherit;"><code>W0CHP-PiStar-Dash</code> Credits</h2>
			<p><code>W0CHP-PiStar-Dash</code> (WPSD) <em>used</em> to be a one-man show (me), but many people
			have contributed code, etc. to the project, and we now have an official <a href="https://repo.w0chp.net/org/WPSD-Dev/teams/dev-peepz">core
			dev.  team</a>.  Thank you
			all! With the exponential growth, doing this alone would have sucked. I am
			grateful for all of you!</p>
			<p>Of course, lots of credit goes to the venerable and skilled, Andy Taylor,
			<code>MW0MWZ</code>, for creating the wonderful Pi-Star software in the first place.</p>
			<p>Credit also goes to the awesome Daniel Caujolle-Bert, <code>F1RMB</code>, for creating his
			personal and customized fork of Pi-Star; as his fork was foundational and
			inspirational to my <code>W0CHP-PiStar-Dash</code>.</p>
			<p>The USA callsign lookup fallback function uses a terrific API,
			<a href="https://callook.info/">callook.info</a>, provided by Josh Dick, <code>W1JDD</code>.</p>
			<p>The callsign-to-country flag GeoLookup code was adopted from
			<a href="https://github.com/LX3JL/xlxd">xlxd</a>&hellip; authored by Jean-Luc Deltombe,
			<code>LX3JL</code>; and Luc Engelmann, <code>LX1IQ</code>. <a href="https://w0chp.net/xlx493-reflector/">I run an XLX(d)
			reflector</a>, <em>plus</em>, I was able to adopt some of its code
			for <code>W0CHP-PiStar-Dash</code>, ergo, I am very grateful.
			The excellent country flag images are courtesy of <a href="https://github.com/hampusborgos/country-flags">Hampus Joakim
			Borgos</a>.</p>
			<p>A big &ldquo;thank you&rdquo; goes to the amazing people/devs/sysadmins from the
			wonderful <a href="https://m17project.org/">M17 Project</a> for hosting the WPSD disk
			image mirror server!</p>
			<p>Credit must also be given to to Kim Heinz Hübel; <code>DG9VH</code>, and Hans-Juergen
			Barthen; <code>DL5DI</code>, both of whom arguably created the very first MMDVM and
			ircDDBGateway dashboards (respectively); of which, spawned the entire Pi-Star
			concept.</p>
			<p>So much credit goes toward the venerable José Uribe (&ldquo;Andy&rdquo;), <code>CA6JAU</code>, for his
			amazing work and providing the game-changing <code>MMDVM_HS</code> hotspot firmware suite,
			as well as his <code>MMDVM_CM</code> cross-mode suite.</p>
			<p>Lastly, but certainly not least; I owe an <em>enormous</em> amount of gratitude toward
			a true gentleman, scholar and incredibly talented hacker&hellip;Jonathan Naylor,
			<code>G4KLX</code>; for the suite of MMDVM and related client tools. Pi-Star would have
			no reason to exist, without Jonathan&rsquo;s incredible and prolific contributions
			and gifts to the ham community.</p>
		    </div>
	    </div>

	    <div class="footer">
	    <?php include $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>
	    </div>
	    
	</div>
    </body>
</html>
