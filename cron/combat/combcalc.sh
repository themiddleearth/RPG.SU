#!/bin/sh

cd /home/vhosts/rpg.su/cron/combat

while [ 1 -eq 1 ];
do
/usr/local/fcgi/php/bin/php combcalc.php
sleep 10

done