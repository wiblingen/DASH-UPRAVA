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

// Load the Pi-Star Release file
$pistarReleaseConfig = '/etc/pistar-release';
$configPistarRelease = array();
$configPistarRelease = parse_ini_file($pistarReleaseConfig, true);
// Load the Version Info

// Sanity Check that this file has been opened correctly
if ($_SERVER["PHP_SELF"] == "/admin/expert/calibration.php") {

  if (isset($_GET['action'])) {
    if ($_GET['action'] === 'start') {
      system('sudo kill $(sudo lsof -t -a -c nc -i UDP:33273) > /dev/null 2>&1');
      system('/bin/nc -dlu 33273 | sudo -i script -qfc "/usr/local/sbin/pistar-mmdvmcal" /tmp/pi-star_mmdvmcal.log > /dev/null 2>&1 &');
    }
    else if (($_GET['action'] === 'saveoffset')) {
      if (isset($_GET['param']) && strlen($_GET['param'])) {
        system('sudo mount -o remount,rw /');
        system('sudo sed -i "/RXOffset=/c\\RXOffset='.intval($_GET['param']).'" /etc/mmdvmhost');
        system('sudo sed -i "/TXOffset=/c\\TXOffset='.intval($_GET['param']).'" /etc/mmdvmhost');
      }
    }
    exit();
  }

  if (isset($_GET['cmd']) && strlen($_GET['cmd'])) {
    system('/bin/echo -ne '. escapeshellarg($_GET['cmd']) .' | nc -u -q1 -w0 -p33272 127.0.0.1 33273 > /dev/null 2>&1');
    if (isset($_GET['param']) && strlen($_GET['param'])) {
      usleep(500*1000);
      system('/bin/echo -ne '. escapeshellarg($_GET['param'].'\n') .' | nc -u -q1 -w0 -p33272 127.0.0.1 33273 > /dev/null 2>&1');
    }
    if ($_GET['cmd'] === 'q') {
      sleep(1);
      system('/bin/echo -ne "\n" | nc -u -q1 -w0 -p33272 127.0.0.1 33273 > /dev/null 2>&1'); //send something to kill the pipe, also \n may be useful if something went wrong and mmdvmcal is waiting some param input
    }
    exit();
  }

  // Sanity Check Passed.
  header('Cache-Control: no-cache');
  session_start();

  if (!isset($_GET['ajax'])) {
    //unset($_SESSION['mmdvmcal_offset']);
    if (file_exists('/tmp/pi-star_mmdvmcal.log')) {
      $_SESSION['mmdvmcal_offset'] = filesize('/tmp/pi-star_mmdvmcal.log');
    } else {
      $_SESSION['mmdvmcal_offset'] = 0;
    }
  }
  
  if (isset($_GET['ajax'])) {
    //session_start();
    if (!file_exists('/tmp/pi-star_mmdvmcal.log')) {
      exit();
    }
    
    $handle = fopen('/tmp/pi-star_mmdvmcal.log', 'rb');
    if (isset($_SESSION['mmdvmcal_offset'])) {
      fseek($handle, 0, SEEK_END);
      if ($_SESSION['mmdvmcal_offset'] > ftell($handle)) //log rotated/truncated
        $_SESSION['mmdvmcal_offset'] = 0; //continue at beginning of the new log
      $data = stream_get_contents($handle, -1, $_SESSION['mmdvmcal_offset']);
      $_SESSION['mmdvmcal_offset'] += strlen($data);
      echo nl2br($data);
      }
    else {
      fseek($handle, 0, SEEK_END);
      $_SESSION['mmdvmcal_offset'] = ftell($handle);
      } 
  exit();
  }

  $RXFrequency = exec('grep "RXFrequency" /etc/mmdvmhost | awk -F "=" \'{print $2}\'');
  $RXOffset = exec('grep "RXOffset" /etc/mmdvmhost | awk -F "=" \'{print $2}\'');
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
    <head>
        <meta name="robots" content="index" />
        <meta name="robots" content="follow" />
        <meta name="language" content="English" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="Author" content="Andrew Taylor (MW0MWZ), Chip Cuccio (W0CHP)" />
        <meta name="Description" content="Pi-Star Expert" />
        <meta name="KeyWords" content="MMDVMHost,ircDDBGateway,D-Star,ircDDB,DMRGateway,DMR,YSFGateway,YSF,C4FM,NXDNGateway,NXDN,P25Gateway,P25,Pi-Star,DL5DI,DG9VH,MW0MWZ,W0CHP" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="pragma" content="no-cache" />
        <link rel="shortcut icon" href="//images/favicon.ico" type="image/x-icon" />
        <meta http-equiv="Expires" content="0" />
        <title>Pi-Star - Digital Voice Dashboard - MMDVM Calibration</title>
        <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/pistar-css.php?version=<?php echo $versionCmd; ?>" />
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jquery-timing.min.js"></script>
        <script type="text/javascript" src="/js/plotly-basic.min.js"></script>
        <script type="text/javascript">

    var rxoffset = ~~'<?php echo $RXOffset; ?>';

    function sendaction(action='', param='') {
      if (action === 'start') { document.getElementById("btnStart").disabled = true; }
      if (action === 'saveoffset') { rxoffset = ~~param }
      $.ajax({
        url: 'calibration.php',
        type: 'GET',
        data: {
          'action': action,
          'param': param
        },
        cache: false,
        success: function(msg) {}
      });
      return false;
    }
    
    var sendcmd_lock=false;

    function sendcmd(cmd='', param='') {
      if (sendcmd_lock) { return false; }
      if (param !== '') { sendcmd_lock = true; } //if we have param, lock to prevent cmd overlap while waiting param
      $.ajax({
        url: 'calibration.php',
        type: 'GET',
        data: {
          'cmd': cmd,
          'param': param
        },
        cache: false,
        success: function(msg) {},
        complete: function() { sendcmd_lock = false; }
      });
      return false;
    }
    
    var cnt=0; tcnt=0;
    var cfrms=0; cbits=0, cberr=0;
    var tfrms=0; tbits=0, tberr=0;
    var eot=false;

    $(function() {
      $.repeat(1000, function() {
        $.get('/admin/expert/calibration.php?ajax', function(data) {
         if (data.length > 0) {
<?php if (isset($_GET['debug'])) { ?>
          var objDiv = document.getElementById("tail");
          var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
          $('#tail').append(data);
          if (isScrolledToBottom)
            objDiv.scrollTop = objDiv.scrollHeight;
<?php } ?>
          
          if (("\n"+data).includes("Version:")) {
            setTimeout(function(){ sendcmd('e', (~~'<?php echo $RXFrequency; ?>'+rxoffset).toString() ); }, 1000);
          }
          if (("\n"+data).includes("Complete...")) {
            $('#ledStart').attr("src", '/images/20red.png');
            $('#ledDStar').attr("src", '/images/20red.png');
            $('#ledDMR').attr("src", '/images/20red.png');
            $('#ledYSF').attr("src", '/images/20red.png');
            $('#ledP25').attr("src", '/images/20red.png');
            $('#ledNXDN').attr("src", '/images/20red.png');
            document.getElementById("btnStart").disabled = false;
          }

          if (("\n"+data).includes("\nBER Test Mode (FEC) for D-Star")) {
            $('#ledStart').attr("src", '/images/20green.png');
            $('#ledDStar').attr("src", '/images/20green.png');
            $('#ledDMR').attr("src", '/images/20red.png');
            $('#ledYSF').attr("src", '/images/20red.png');
            $('#ledP25').attr("src", '/images/20red.png');
            $('#ledNXDN').attr("src", '/images/20red.png');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for DMR Simplex")) {
            $('#ledStart').attr("src", '/images/20green.png');
            $('#ledDStar').attr("src", '/images/20red.png');
            $('#ledDMR').attr("src", '/images/20green.png');
            $('#ledYSF').attr("src", '/images/20red.png');
            $('#ledP25').attr("src", '/images/20red.png');
            $('#ledNXDN').attr("src", '/images/20red.png');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for YSF")) {
            $('#ledStart').attr("src", '/images/20green.png');
            $('#ledDStar').attr("src", '/images/20red.png');
            $('#ledDMR').attr("src", '/images/20red.png');
            $('#ledYSF').attr("src", '/images/20green.png');
            $('#ledP25').attr("src", '/images/20red.png');
            $('#ledNXDN').attr("src", '/images/20red.png');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for P25")) {
            $('#ledStart').attr("src", '/images/20green.png');
            $('#ledDStar').attr("src", '/images/20red.png');
            $('#ledDMR').attr("src", '/images/20red.png');
            $('#ledYSF').attr("src", '/images/20red.png');
            $('#ledP25').attr("src", '/images/20green.png');
            $('#ledNXDN').attr("src", '/images/20red.png');
          }
          if (("\n"+data).includes("\nBER Test Mode (FEC) for NXDN")) {
            $('#ledStart').attr("src", '/images/20green.png');
            $('#ledDStar').attr("src", '/images/20red.png');
            $('#ledDMR').attr("src", '/images/20red.png');
            $('#ledYSF').attr("src", '/images/20red.png');
            $('#ledP25').attr("src", '/images/20red.png');
            $('#ledNXDN').attr("src", '/images/20green.png');
          }
          
          if (data.includes("voice end received,")) {
            eot=true;
          }

          var regex = / frequency: (\d+)/g
          while (match = regex.exec(data)) {
            $('#ledStart').attr("src", '/images/20green.png');
            $("#lblFrequency").text(match[1] + ' Hz');
            $("#lblOffset").text(~~match[1] - ~~'<?php echo $RXFrequency; ?>');
          }

          var regex = /\% \((\d+)\/(\d+)\)/g
          while (match = regex.exec(data)) {
            cfrms += 1;
            cberr += ~~match[1];
            cbits += ~~match[2];
            tfrms += 1;
            tberr += ~~match[1];
            tbits += ~~match[2];
          }
         }

          if (cbits > 0) {
            cnt++; tcnt++;
            var updfrq = $('#sltUpdFrq').val();
            if ((tcnt % updfrq == 0) || eot) {
                //$('#tail').append(cfrms +' , '+ cberr +' / '+ cbits +' , '+ (cberr/cbits*100).toFixed(2) + '%<br>');
                $("#lblFrames").text(cfrms);
                $("#lblBits").text(cbits);
                $("#lblErrors").text(cberr);
                $("#lblBER").text((cberr/cbits*100).toFixed(2)+'%');
                Plotly.extendTraces('chart', { x:[[cnt]], y:[[cberr/cbits*100]] }, [0]);
                if(cnt > 60*3) {
                    Plotly.relayout('chart', {
                        xaxis: {range: [cnt-60*3,cnt]}
                    });
                }
                cfrms=0; cbits=0; cberr=0;

                //$('#tail').append('total: ' + tfrms +' , '+ tberr +' / '+ tbits +' , '+ (tberr/tbits*100).toFixed(2) + '%<br>');
                $("#lblTFrames").text(tfrms);
                $("#lblTBits").text(tbits);
                $("#lblTErrors").text(tberr);
                $("#lblTBER").text((tberr/tbits*100).toFixed(2)+'%');
                $("#lblTSec").text(tcnt);
                if (eot) {
                  eot=false;
                  tfrms=0; tbits=0; tberr=0; tcnt=0;
                }
            }
          }

        });
      });
    });
    </script>
  </head>
  <body>
  <div class="container">
<?php include './header-menu.inc'; ?>
  </div>
  <div class="contentwide">
  <table width="100%">
  <tr><th>MMDVM Calibration Tool</th></tr>
  <tr><td align="left">
  
<table width="800" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><input name="btnStart" type="button" id="btnStart" onclick="sendaction('start');" value="Start" /></td>
        <td><img src="/images/20red.png" name="ledStart" width="20" height="20" id="ledStart" /></td>
      </tr>
      <tr>
        <td><input name="btnStop" type="button" id="btnStop" onclick="sendcmd('q');" value="Stop" /></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>

    <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td><input name="btnDStar" type="button" id="btnDStar" onclick="sendcmd('k');" value="D-Star" /></td>
        <td><img src="/images/20red.png" name="ledDStar" width="20" height="20" id="ledDStar" /></td>
        </tr>
      <tr>
        <td><input name="btnDMR" type="button" id="btnDMR" onclick="sendcmd('b');" value="DMR" /></td>
        <td><img src="/images/20red.png" name="ledDMR" width="20" height="20" id="ledDMR" /></td>
        </tr>
      <tr>
        <td><input name="btnYSF" type="button" id="btnYSF" onclick="sendcmd('J');" value="YSF" /></td>
        <td><img src="/images/20red.png" name="ledYSF" width="20" height="20" id="ledYSF" /></td>
        </tr>
      <tr>
        <td><input name="btnP25" type="button" id="btnP25" onclick="sendcmd('j');" value="P25" /></td>
        <td><img src="/images/20red.png" name="ledP25" width="20" height="20" id="ledP25" /></td>
        </tr>
      <tr>
        <td><input name="btnNXDN" type="button" id="btnNXDN" onclick="sendcmd('n');" value="NXDN" /></td>
        <td><img src="/images/20red.png" name="ledNXDN" width="20" height="20" id="ledNXDN" /></td>
        </tr>
    </table></td>

    <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td align="left">Base Freq.:</td>
        <td colspan="3" id="lblBaseFreq"><?php echo $RXFrequency; ?> Hz</td>
      </tr>
      <tr>
        <td align="left">Frequency:</td>
        <td colspan="3" id="lblFrequency"><?php echo $RXFrequency + $RXOffset; ?> Hz</td>
      </tr>
      <tr>
        <td align="left">Offset:</td>
        <td><input name="btnFreqM" type="button" id="btnFreqM" onclick="sendcmd('f');" value="-" /></td>
        <td id="lblOffset" style="width:5ch"><?php echo $RXOffset; ?></td>
        <td><input name="btnFreqP" type="button" id="btnFreqP" onclick="sendcmd('F');" value="+" /></td>
      </tr>
      <tr>
        <td align="left">Step:</td>
        <td colspan="3"><input type="button" onclick="sendcmd('z','25');" value="25" /> <input type="button" onclick="sendcmd('z','50');" value="50" /> <input type="button" onclick="sendcmd('z','100');" value="100" /></td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td colspan="3"><input name="button8" type="button" onclick="sendaction('saveoffset',$('#lblOffset').text());" value="Save Offset" /></td>
      </tr>
    </table></td>

    <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th style="width:8ch">&nbsp;</th>
        <th style="width:9ch">Current</th>
        <th style="width:9ch">Total</th>
      </tr>
      <tr>
        <td align="left">Frames:</td>
        <td id="lblFrames">&nbsp;</td>
        <td id="lblTFrames">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Bits:</td>
        <td id="lblBits">&nbsp;</td>
        <td id="lblTBits">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Errors:</td>
        <td id="lblErrors">&nbsp;</td>
        <td id="lblTErrors">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">BER:</td>
        <td id="lblBER">&nbsp;</td>
        <td id="lblTBER">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Seconds:</td>
        <td id="lblSec" style="padding:0;"><select name="sltUpdFrq" id="sltUpdFrq" style="margin:0;">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="5" selected="selected">5</option>
                          <option value="10">10</option>
                          <option value="30">30</option>
                        </select>
        </td>
        <td id="lblTSec">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>  

  </td></tr>
  <tr><td align="left">
        <div id="chart"></div>
        <script type="text/javascript">
            Plotly.newPlot('chart', [{
                x: [0],
                y: [0],
                type: 'scatter',
                mode: 'lines',
                fill: 'tozeroy',
                line: {color: '#fff'}
            }], {title:'Bit Error Rate (BER)', xaxis:{title:'Seconds',rangemode:'tozero'}, yaxis:{title:'%',rangemode:'tozero',range:[0,5]} }, {staticPlot: true});
        </script>
      </td></tr>
<?php if (isset($_GET['debug'])) { ?>
  <tr><td align="left"><div id="tail"></div></td></tr>
<?php } ?>
  </table>
  </div>
            <div class="footer">
                Pi-Star web config, &copy; Andy Taylor (MW0MWZ) 2014-<?php echo date("Y"); ?>.<br />
                <a href="https://w0chp.net/w0chp-pistar-dash/" style="color: #ffffff; text-decoration:underline;">W0CHP-PiStar-Dash</a> enhancements by W0CHP<br />
            </div>
  </div>
  </div>
  </body>
  </html>

<?php
}
?>
