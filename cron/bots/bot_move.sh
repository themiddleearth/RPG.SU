#!/bin/sh

cd /home/vhosts/rpg.su/cron/bots

while [ 1 -eq 1 ];
do
echo "Starting..."
/usr/local/fcgi/php/bin/php bot_move.php
sleep 20


done