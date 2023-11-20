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

## 🗒️ Notes:

- The daemon is a closed-source binary. We painfully modified it to check at `https://proxcp.free-tools.club/socket-license-check`. The easiest solution would be to simply create an entry in `/etc/hosts` and redirect my domain to the IP of your hosted server.

- <a href="https://github.com/marcus-alicia/ProxCP/blob/main/web/lilib/proxcp_lilib_c.php" target="_blank">The web config file</a> contains a section for the IP address of the server. You'll have to change that as well, otherwise ProxCP will complain.

- Lastly, theres <a href="https://github.com/marcus-alicia/ProxCP/blob/server/var/www/proxcp/apl_callbacks/license_verify.php" target="_blank">this file</a> which uses `gethostbynamel`. You'll have to replace that with just the IP as well (hopefully, never tested).

## ❤️ Enjoy!
