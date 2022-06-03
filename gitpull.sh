#!/bin/bash
cd /home/ubuntu/www/diary
/usr/bin/git pull
# crontab -e
# * * * * * /home/ubuntu/www/diary/gitpull.sh;
# * * * * * cd /home/ubuntu/www/diary; /usr/bin/git pull