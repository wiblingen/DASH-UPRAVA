**NOTE: If you are reading this on a site other than its official
*https://repo.w0chp.net* domain (such as GitHub etc.), then this is not my
official program / code, and is likely outdated, and is unsupported. The
canonical and official site for this program is:
https://w0chp.net/w0chp-pistar-dash/.**

[Please don’t upload my code to GitHub](https://nogithub.codeberg.page) [![Please don't upload my code to GitHub](https://nogithub.codeberg.page/badge.svg)](https://nogithub.codeberg.page)

## About `W0CHP-PiStar-Dash`, and Some Warnings

This is my very highly modified and customized fork of MW0MWZ’s Pi-Star
software, and I call it “W0CHP-PiStar-Dash”. There are so many large changes,
divergences and new features, it merited my own fork/version.

In fact, it’s pretty much its own distribution at this point; especially now
that the W0CHP-PiStar-Dash disk/OS images are now bullseye-based; and is now
its own beast, so-to-speak.

## Warnings, Caveats and FAQs {#caveats}

* [Read The FAQs!](https://w0chp.net/wpsd-faqs/)
* This code/project is a moving target, has bugs (like any code does) and can be
  unstable. It also consumes more system resources due to the myriad improvements.
* If you plan on running my software on a single-core Pi-Zero 1st gen. or very old hardware, be prepared
  for my software to run terribly slowly. I created this software specifically for
  modern, multi-core CPU hardware.
* [Read The FAQs again!](https://w0chp.net/wpsd-faqs/)

## Getting Help/Support
* Do *NOT* ask for support for `W0CHP-PiStar-Dash` on any official or unofficial
  Pi-Star support page/forum/medium/etc. This is not Andy’s (`MW0MWZ`) Pi-Star
  software!
* Before you ask for help, [**read how to *properly* ask for help**](https://w0chp.net/musings/how-to-report-a-wpsd-issue/).
* Some really great users, fans and contributors of `W0CHP-PiStar-Dash` have setup
  a [Facebook Group](https://www.facebook.com/groups/w0chppistardash/) and a
  [Discord Server](https://discord.gg/mjgUky8hze) to get community support, etc. These are the only *official* online support mediums for my software.
* [<code>XLX-493</code> ; Module <code>E</code>](https://w0chp.net/xlx493-reflector/) is the `W0CHP-PiStar-Dash` Chat Module.
  A direct DMR conference to this module/room is bridged with BrandMeister 
  and TGIF Networks; BM: simply call TalkGroup `3170603`, and TGIF: simply call TalkGroup `493`.
* **Issue / Bug Tracker and Pull Requests / Patches:**
  * *DO NOT ASK FOR SUPPORT.* Repo and issue tracker access is for developers/hackers and contributors only, as well as *verified* bugs.
  * The issue tracker is *NOT* for:
    1. Support requests
    2. Feature requests
    3. Other topics not germane to bugs, issues, etc.
  * You *may* be directed to file an issue report here by developers when necessary.

## Known Issues & Incompatibilities

**Raspberry Pi Zero v1.x (1st Gen, single-core)**

: If you have a first-generation Raspberry Pi Zero (Pi Zero (W) Rev.1.1 armv6l) with the
  single-core processor\*, and have downloaded my RPi Bullseye
  disk image, you need to do a couple of things before you can access it:
    1. [Install a WiFi config file before you boot the image](https://w0chp.net/wpa-config-generator/), or connect it to Ethernet. Network connectivity is required for first boot-up.
    2. Let the image boot and configure for about 30 minutes, otherwise you will not be able to access the dashboard.

  If you fail to do these things, you will get a "502 Bad Gateway" error when attempting to access the dashboard.

  \* *Note:* The official ZUMSpot Mini 1.3 Disk Image (below) does not have this issue, since I built the disk image for that specific hardware.

**DVMega**

: If you have a DVmega EuroNode or the like, and install my NanoPi NEO Bullseye
  disk image on it, you will likely lose all wireless functionality (Ethernet still works,
  however). This is because the DVmega folks create their own older Buster image,
  tailored to their custom hardware.

  If you still want to use my software on DVMega
  hardware, you absolutely can; and it's best to use the [existing hotspot installation
  method](#hotspot-installation), which will leave the operating system alone as
  the older Buster version and wireless functionality will remain intact.

**TGIF Spots with Nextion Screens**

: Using the WPSD installation script will detect this device and disallow installation; We don't support TGIF Spots running "buster".
  This is because "buster" TGIF Spots use weird and hacky scripts and modifications etc., which WPSD does not support.

  "Bullseye" [disk image installations *are* supported](#disk-image-installation), however, you may lose some of the superfluous Nextion screen
  functionality.

  If you install WPSD on TGIF Spots with Nextion screens, and the screens don't work the way you want, don't complain about it; as their strange hacks
  are not a part of WPSD.

**Restoring and/or Using Configurations from "OG" Pi-Star Software May Not Always Work**

: Sometimes, configurations from the original Pi-Star software are *not* compatible with WPSD. This is because
  WPSD always uses the latest versions of upstream software, like MMDVHhost and its related ancillary gateway programs; and these newer versions are not always backward-compatible with
  old configurations. The best ways to deal with this, it to use an appropriate disk image and setup/configure from scratch, or perform a "Factory Reset" and configure from scratch.

## Installing `W0CHP-PiStar-Dash`

Now that you've been adequately informed of the rules, caveats and the risks, keep reading to learn how to
install `W0CHP-PiStar-Dash`.

There are two methods of installation...

1. [Installation via a disk image](#disk-image-installation)
2. [Installation on an existing Pi-Star hotspot](#hotspot-installation)

### Installing `W0CHP-PiStar-Dash` from a Bullseye-based Disk Image {#disk-image-installation}

The `W0CHP-PiStar-Dash` disk images use "Bullseye" as the core operating
system; far newer and better than the legacy "Buster"-based OS that Pi-Star
uses.

The Bullseye disk images are ready-to-go; with `W0CHP-PiStar-Dash` installed.

**Raspberry Pi Disk Image (for RPi Zero, Zero 2 and Models 2, 3, 4, etc.):**

: Raspberry Pi Disk Image Download: [<code>**WPSD_RPi_Latest.img.xz**</code>](https://w0chp.net/WPSD_RPi_Latest.img.xz)

**Orange Pi Zero Disk Image:**

: Orange Pi Zero Disk Image Download: [<code>**WPSD_OrangePiZero_Latest.img.xz**</code>](https://w0chp.net/WPSD_OrangePiZero_Latest.img.xz)

**Nano Pi Neo Disk Image:**

: Nano Pi Neo Disk Image Download: [<code>**WPSD_NanoPiNeo_Latest.img.xz**</code>](https://w0chp.net/WPSD_NanoPiNeo_Latest.img.xz)

**ZUMSpot Mini 1.3 Disk Image:**

: This is an *official* and custom-built disk image specific to the ZUMspot Mini 1.3 Hotspot.

: ZUMSpot Mini Disk Image Download: [<code>**WPSD_ZUMspot-Mini_Latest.img.xz**</code>](https://w0chp.net/WPSD_ZUMspot-Mini_Latest.img.xz)


**ZUMSpot Elite 3.5 Disk Image:**

: This is an *official* and custom-built disk image specific to the ZUMspot Elite 3.5 Hotspot.

: ZUMSpot Elite Disk Image Download: [<code>**WPSD_ZUMspot-Elite_Latest.img.xz**</code>](https://w0chp.net/WPSD_ZUMspot-Elite_Latest.img.xz)


**SHA-256 Checksums for Disk Image Files:**

: [<code>WPSD_SHA256-SUMS.txt</code>](https://w0chp.net/WPSD_SHA256-SUMS.txt)

<i class="fas fa-exclamation-triangle"></i>  You will need an SD card of at
least 4GB to install these disk images.

The setup of the Bullseye image is similar to that of Pi-Star's:

1. Download the image.
2. Use a tool such as [Balena Etcher](https://www.balena.io/etcher) to write the image to your SD-Card. This tool will automatically decompress the `.xz` file as well.
3. Optional: Use my [WPA Config File Generator](https://w0chp.net/wpa-config-generator/) to automatically connect the dashboard to your WiFi...
4. Otherwise: After about 5+ minutes post-bootup, you can connect to the "`Pi-Star-Setup`" WiFi network to login to the dashboard and configure your hotspot after it's booted...
5. Insert the SD-Card into your hotspot and bootup!
6. The default login is;

   User: `pi-star`

   Password: `raspberry`

**<i class="fas fa-exclamation-circle"></i> Important Info for First Bootup:**

1. When first booting from the Bullseye-based disk image, go grab a coffee,
   drink, etc. and let the file-system auto-expand and the rest of the system
   initialize. Be patient.
2. When installing from the Bullseye-based disk image, it's a best practice (and better) to *run an update
   **before** setting up or making configuration changes* to your hotspot. This ensures that setup/configuration changes you make
   are the most tested and up-to-date.

### Installing `W0CHP-PiStar-Dash` on an Existing Pi-Star Hotspot {#hotspot-installation}

<i class="fas fa-exclamation-triangle"></i> You need to have a Pi-Star hotspot
**running at least v4.1.6!**[^1]

1. Make a backup of your configuration if you wish -- just in case.

2. Open an SSH session to your Pi-Star instance.

3. Run this to familiarize yourself with the available options/arguments:[^2]

    ```text
    curl -Ls https://w0chp.net/WPSD-Install | sudo env NO_SELF_UPDATE=1 bash -s -- -h
    ```

    You will be presented with...

    ```
	[i] W0CHP PiStar-Dash Installer Command Usage:

	  -h,   --help                   :  Display this help text


	  -id,  --install-dashboard      :  Install W0CHP dashboard


	  -idc, --install-dashboard-css  :  Install W0CHP dashboard
                                   	    WITH custom stylesheet

	  -rd,  --restore-dashboard      :  Restore original dashboard


	  -s,   --status                 :  Display version status/info
    ```

4. When ready to install, run the above command again with the option/argument you wish...e.g:

    ```text
    curl -Ls https://w0chp.net/WPSD-Install | sudo env NO_SELF_UPDATE=1 bash -s -- -id
    ```

	(...to install the dashboard *without* the `W0CHP` custom CSS)

5. When the installer completes, refresh your dashboard home page to see the changes.

<i class="fas fa-exclamation-triangle"></i> You **must** run the aforementioned
commands with the exact syntax. Note the spaces and extra `--` (dashes), etc.
Otherwise, the commands will fail.

## Updating `W0CHP-PiStar-Dash`

Once you install `W0CHP-PiStar-Dash`, it will automatically be kept up-to-date
with any new features/versions/etc. This is made possible via the native,
nightly updating process.[^3]

If you do not leave your hotspot powered on during the night, you can also
manually invoke the update process via the dashboard admin section (`Admin ->
Update`), or by command line:

```text
sudo pistar-update
```

*Tip:* It is recommended that you simply leave your
hotspot(s) powered on at night, since WPSD is [rolling release
software](https://w0chp.net/musings/new-w0chp-pistar-dash-versioning-scheme/) - updates are
rapid and frequent!

## Uninstalling `W0CHP-PiStar-Dash`

If you installed my software over an existing Pi-Star installation, it's super-simple...

1. Run:

    ```text
    sudo WPSD-Installer -rd
    ```

    ...And the original Pi-Star Dashboard will be restored. Not sure why anyone would want to do this, though. `;^)`

## Features, Enhancements and Omissions (not an exhaustive list)

### Functionality Features

* Full APRSGateway Support: Selectable APRS Data Sharing with specific modes.
* Full DGId Support.
* "Live Caller" screen; similar to a "virtual Nextion screen"; displays current caller information in real-time.
* Current/Last Caller Details on Main Dashboard (name/location, when available).
* Talkgroup Names display in target fields (Brandmeister DMR, NXDN and P25 support only).
* YSF/NXDN/P25/M17 link managers gives the ability to change links/rooms/reflectors/TGs  on-the-fly, rather than going through the configuration page.
* DMR Network Manager allows instant disabling/enabling of configured DMR networks/masters; and fast switching of XLX reflectors and modules. Handy for "pausing" busy networks, talkgroups, timeslots, etc.
* Full M17 Protocol Support. ([See M17 Notes below...](#m17-notes))
* BrandMeister Manager revamps galore:
  * Now displays connected actual talk group names.
  * Connected dynamic talk groups now display idle-timeout time (due to no TX).
  * Added ability to mass-drop your static talk groups; and mass re-add the previously
    linked static talk groups.
  * Added ability to batch add/delete up to 10 static talk groups at a time.
* ~~TGIF Manager; now displays connected actual talk group names.~~ (**NOTE**: Since TGIF has moved to a new platform with no complete API available, this currently does not work until TGIF's API is made available.)
* "Instant Mode Manager" added to admin page; allows you to instantly pause or resume selected radio modes. Handy for attending
  nets, quieting a busy mode, to temporarily eliminate "mode monopolization", etc.
* "System Manager" added to admin page; allows you to instantly:
  * Disable / Enable the intrusive and slow Pi-Star Firewall.
  * Disable / Enable Cron, in order to prevent updates and Pi-Star services restarting during middle-of-the-night/early AM operation.
* Ability to configure POCSAG hang-time from the config page.
* Native Nextion screen support built-in; no futzing around with Nextion drivers/scripts.
* Selectable DMR Roaming Beacon Support: Network or Interval Mode (or disabled) - for actual repeaters only.

### User Interface / Design Features

* Updated user interface elements galore, styling, wider, bigger, updated fonts, etc.
* Optional "Simple View"; shows only activity: no mode status, hardware status, etc. Just activiy data. Accessed via `http://your-hotspot-url/simple/`
* Country-of-origin flags for callsigns.
* Improved and graphical CSS/color styling configuration page; easily change the look and feel of the dashboard.
* User-Configurable number of displayed Last Heard dashboard rows (defaults to 40, and 100 is the maximum).
* User-Configurable font size for most of the pertinent dashboard information.
* Reorganized and sectioned configuration page for better usability.
* System process status reorganized into clean grid pattern, with more core service status being displayed.
* User-Configurable 24 or 12 hour time display across the dashboard.
* Connected FCS and YSF reflector names and numerical ID both displayed in dashboard left panel.
* Additional hardware, radio and system information displayed in top header; which can be toggled.
* Admin page split up into logical sub-sections/sub-pages, in order to present
  better feedback messages when making changes.
  * Note: Last-Heard and other dynamic tables are hidden in the admin sections by default, allowing users
    to focus on the tasks-at-hand and their outputs. The Last-Heard data can be toggled in these areas, however.

### Features in Official Pi-Star Which are Intentionally Omitted in `W0CHP-PiStar-Dash`

* Upgrade notice/nag in header (unnecessary and a hacky implementation). This has been replaced by my own
  unobtrusive and configurable dashboard update notifier; displayed in the upper-right hand side of the top header.
* Custom `BannerH2` (etc.) text options have been removed (added clutter and I never used it). Instead, the hostname is displayed in the browser title.
* "GPS" link in Call Sign column of dashboard (superfluous and unreliable).
* CPU Temp. in header; when CPU is running "cool" or "normal" recommended temps, the cell background
  is no longer colored green. Only when the CPU is running beyond recommended temps, is the cell colored
  orange or red.
* No reboot/shutdown nag screen/warning from admin page (Superfluous; you
  click it, it will reboot/shutdown without warning.).
* Yellow DMR Mode cell in left panel when there's a DMR network password/login
  issue (poor/inaccurate and taxing implementation, and can confuse power users that
  utilize my Instant Mode Manager, where the default cell is amber colored for
  paused modes [color is user-configurable].).
  Instead, the *actual* network name is highlighted in red when there's a login issue.

## Notes about CSS, and custom CSS you may have previously applied {#css-notes}

<i class="fa fa-info-circle" aria-hidden="true"></i> These notes only apply to installations that used my installation script; not the disk images.

1. When using the `-id` option, the "normal" Pi-Star colors are used, and no CSS is installed. Any custom CSS
   you may have had, is removed but backed up. See bullet #4 below.
2. When using the `-idc` option, the `W0CHP` CSS is installed, and any of your custom CSS settings
  before installing the `W0CHP` dashboard, are backed up in the event you want to restore the official dashboard
  (see bullet #4). This is done because the CSS in the official Pi-Star is incompatible. You can still
  manually map/change your CSS back when running `W0CHP-PiStar-Dash` (see bullet #4 for details).
3. If you are already running `W0CHP-PiStar-Dash`, AND you have custom or `W0CHP-PiStar-Dash` CSS, no CSS changes, no matter which
  option you run this command with.
4. When using the `-id` option, your custom CSS settings are backed up (in the event you want to revert back
  to the official dashboard -- see  bullet #6), and the `W0CHP` dashboard uses the standard Pi-Star colors.
  This means that if you want your previous custom CSS applied to the `W0CHP` dashboard, you will need to manually
  customize your colors; You can reference the color values you had previously used, by viewing the backup file of
  your custom CSS...

        /etc/.pistar-css.ini.user

5. ...the reason for bullets #4 and #1, is because the `W0CHP` dashboard is vastly different than the official upstream version
  (completely different CSS mappings). Since this is for my personal use, I haven't added any logic to suck-in
  the user CSS values to the new mappings.
6. If you had customized CSS settings before installing the `W0CHP` dashboard, they will be restored when
  using the `-rd` option.
7. You can at any time start over and reset to the "normal" Pi-Star colors, by performing a CSS Factory Reset (`Configuration -> Advanced -> Tools -> CSS Tool`).
8. If you'd like to start over with the custom `W0CHP` colors/CSS, you can copy/paste [the following values](https://repo.w0chp.net/WPSD-Dev/W0CHP-PiStar-Installer/src/branch/master/supporting-files/pistar-css-W0CHP.ini) into your `/etc/pistar-css.ini`.

## Notes about M17 Protocol Support {#m17-notes}

M17 protocol support requires updated MMDVM Modem Firmware or MMDVM HotSpot
Firmware of at least v1.6.0. Ergo, you will need to download, compile and
install the [MMDVM modem firmware](https://github.com/g4klx/MMDVM) or the
[MMDVM hotspot firmware](https://github.com/juribeparada/MMDVM_HS) yourself in
order to gain full M17 protocol support.

Please note, that if you uninstall `W0CHP-PiStar-Dash`, you will need to
downgrade the MMDVM modem or hotspot firmware back to its original firmware. For MMDVM HS
HAT users, you can simply run the following command:

```text
sudo pistar-mmdvmhshatdowngrade
```

<i class="fas fa-exclamation-triangle"></i> Failure to downgrade the modem
firmware when uninstalling `W0CHP-PiStar-Dash` will result in a non-functional
hot spot, since the official current Pi-Star `MMDVMHost` binary is not compatible with
newer MMDVM firmware.

## Screenshots

*Not all pages shown here. Note, that you can customize the colors to your preferences...*

### Main Dashboard
![alt text](https://w0chp.net/w0chp-pistar-dash/Main.png "Dashboard")

### Main Admin Landing Page
![alt text](https://w0chp.net/w0chp-pistar-dash/Admin.png "Admin Page")

### DMR Network Manager
![alt text](https://w0chp.net/w0chp-pistar-dash/DMRman.png "DMR Network Manager")

### BrandMeister Manager
![alt text](https://w0chp.net/w0chp-pistar-dash/BM.png "BrandMeister Manager")

### D-Star Manager
![alt text](https://w0chp.net/w0chp-pistar-dash/DSman.png "D-Star Manager")

### Instant Mode Manager
![alt text](https://w0chp.net/w0chp-pistar-dash/IMM.png "Mode Manager")

### Live Caller Screen
![alt-text](https://w0chp.net/w0chp-pistar-dash/LC.png "Live Caller Screen")

### Moblie Device View
![alt-text](https://w0chp.net/w0chp-pistar-dash/Mobile.png "Mobile Device View")

## Credits

`W0CHP-PiStar-Dash` (WPSD) *used* to be a one-man show (me), but many people
have contributed code, etc. to the project, and we now have an official [core
dev.  team](https://repo.w0chp.net/org/WPSD-Dev/teams/dev-peepz).  Thank you
all! With the exponential growth, doing this alone would have sucked. I am
grateful for all of you!

Of course, lots of credit goes to the venerable and skilled, Andy Taylor,
`MW0MWZ`, for creating the wonderful Pi-Star software in the first place.

Credit also goes to the awesome Daniel Caujolle-Bert, `F1RMB`, for creating his
personal and customized fork of Pi-Star; as his fork was foundational and
inspirational to my `W0CHP-PiStar-Dash`.

The USA callsign lookup fallback function uses a terrific API,
[callook.info](https://callook.info/), provided by Josh Dick, `W1JDD`.

The callsign-to-country flag GeoLookup code was adopted from
[xlxd](https://github.com/LX3JL/xlxd)... authored by Jean-Luc Deltombe,
`LX3JL`; and Luc Engelmann, `LX1IQ`. [I run an XLX(d)
reflector](https://w0chp.net/xlx493-reflector/), *plus*, I was able to adopt some of its code
for `W0CHP-PiStar-Dash`, ergo, I am very grateful.
The excellent country flag images are courtesy of [Hampus Joakim
Borgos](https://github.com/hampusborgos/country-flags).

Credit must also be given to to Kim Heinz Hübel; `DG9VH`, and Hans-Juergen
Barthen; `DL5DI`, both of whom arguably created the very first MMDVM and
ircDDBGateway dashboards (respectively); of which, spawned the entire Pi-Star
concept.

The very cool and welcome MMDVMhost log backup/restore and re-application on
reboot code, is courtesy of Mark, `KN2TOD`.

So much credit goes toward the venerable José Uribe ("Andy"), `CA6JAU`, for his
amazing work and providing the game-changing `MMDVM_HS` hotspot firmware suite,
as well as his `MMDVM_CM` cross-mode suite.

Lastly, but certainly not least; I owe an *enormous* amount of gratitude toward
a true gentleman, scholar and incredibly talented hacker...Jonathan Naylor,
`G4KLX`; for the suite of MMDVM and related client tools. Pi-Star would have
no reason to exist, without Jonathan's incredible and prolific contributions
and gifts to the ham community.

[^1]: `W0CHP-PiStar-Dash` was not created for single-core and low-powered hardware; such as
      the first generation RPi Zero, etc. (`armv6l`). This software will run very slow on under-powered hardware.
      Please consider yourself warned. Also, please ignore all of the hams on various
      support mediums saying, *"anything more than a Pi Zero is overkill"*. These ignoramuses
      have no idea what goes on under the hood in order to display meaningful info on the
      dashboard. Hint: it's a lot, and it's very resource-intensive. Ignore them...they have no idea what they are talking about.

[^2]: Piping to `bash`/shells/etc. from an online source is controversial (do
      a google search about it). However it's convenient, and one can [view & inspect
      the full & actual source code of the installer](https://repo.w0chp.net/WPSD-Dev/W0CHP-PiStar-Installer/src/branch/master/WPSD-Installer)
      prior to piping to `bash` or installing.

[^3]: `W0CHP-PiStar-Dash` occasionally queries our servers in
      order to determine if updates are available. In the spirit of full-disclosure,
      I wanted to mention this. This is no different than how the official Pi-Star
      software functions (but doesn't make this well-known). Additionally, every
      `W0CHP-PiStar-Dash` installation has a unique UUID generated for it; for
      web/repo-traffic capacity planning/analytics, as well as for troubleshooting
      user issues and bugs.
      This data is used internally, exclusively; and is *not* shared. If you do not
      want this data collected, simply do not install or use `W0CHP-PiStar-Dash`.
      You can find the unique UUID within the `/etc/pistar-release` file.
      The UUID is derived from the devices' unique processor serial number:
      ```
      $ cat /proc/cpuinfo | grep Serial | cut -d ' ' -f 2
      ```

