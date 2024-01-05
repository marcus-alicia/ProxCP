# Server branch:

### This branch contains the files for selfhosting your license server.


## 🌲 Structure:
- nginx:
    - nginx config

- var/www/proxcp:
    - index.html
    - apl_callbacks:
        - connection_test.php
        - license_verify.php
        - verify_envato_purcahse.php

<br><br>

## 🔎 Sample:

Server Request             |  Server Response
:-------------------------:|:-------------------------:
<img src="https://github.com/marcus-alicia/ProxCP/assets/104512346/bb9e776f-713f-4942-acae-c29f86d48536" height="350px"> | <img src="https://github.com/marcus-alicia/ProxCP/assets/104512346/3688b51e-5095-4cb6-b992-6960fb4053b9" height="350px" width="900px">

<br><br>

## 🗒️ Notes:

- The daemon is a closed-source binary. We painfully modified it to check at `https://license.proxcp.free-tools.club/?k=`.

- <a href="https://github.com/marcus-alicia/ProxCP/blob/main/web/lilib/proxcp_lilib_c.php" target="_blank">The web config file</a> contains a section for the IP address of the server. You'll have to change that as well, otherwise ProxCP will complain.

- Lastly, theres <a href="https://github.com/marcus-alicia/ProxCP/blob/server/var/www/proxcp/apl_callbacks/license_verify.php" target="_blank">this file</a> which uses `gethostbynamel`. You'll have to replace that with just the IP as well (hopefully, never tested).

## ❤️ Enjoy!
