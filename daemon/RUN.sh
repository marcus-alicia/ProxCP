#!/bin/sh
# Using this script to run proxcp-daemon will monitor the process for errors and keep it alive
# forever start -a -l forever.log -e err.log -c /bin/bash RUN.sh
exec ./proxcp-daemon
