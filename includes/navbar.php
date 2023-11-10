                <div class="navbar">
                  <div class="headerClock">
                    <span id="DateTime"></span>
                  </div>
                  <a class="menuconfig" href="/admin/configure.php"><?php echo $lang['configuration'];?></a>
                  <?php if ($_SERVER["PHP_SELF"] == "/admin/index.php") {
                      echo ' <a class="menuupdate noMob" href="/admin/update.php">'.$lang['update'].'</a>'."\n";
                      echo ' <a class="menuexpert noMob" href="/admin/advanced/">Advanced</a>'."\n";
                      echo ' <a class="menupower" href="/admin/power.php">'.$lang['power'].'</a>'."\n";
                      echo ' <a class="menusysinfo noMob" href="/admin/sysinfo.php">System Details</a>'."\n";
                      echo ' <a class="menulogs noMob" href="/admin/live_log.php">'.$lang['live_logs'].'</a>'."\n";
                    }
                    if ($_SERVER["PHP_SELF"] !== "/admin/index.php") {
                        echo '<a class="menuadmin" href="/admin/">'.$lang['admin'].'</a>'."\n";
                    } ?>
                    <?php if (file_exists("/etc/dstar-radio.mmdvmhost")) { ?>
                    <a class="menulive" href="/live/">Live Caller</a>
                    <?php } ?>
                    <a class="menuhwinfo noMob" href='#'>SysInfo</a>
                    <a class="menusimple noMob" href="/simple/">Simple View</a>
                    <a class="menudashboard" href="/"><?php echo $lang['dashboard'];?></a>
                </div>
                </div>
