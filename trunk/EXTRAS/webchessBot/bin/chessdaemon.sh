#!/bin/sh

while [ 1 == 1 ]; do
        /scripts/chess/computer/play.php >>/scripts/chess/computer/log.txt
        sleep 300

        if [ -f "/scripts/chess/computer/stop" ] ; then
                echo -e "Stopping...\n" >> /scripts/chess/computer/log.txt
                exit 1;
        fi

done
