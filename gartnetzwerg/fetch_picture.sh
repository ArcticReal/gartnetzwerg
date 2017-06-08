#!/bin/sh

if [ "$1" != '' ]
	then
		if [ "$2" != '' ]
			then
				scp pi@$1:/home/pi/Bilder/* /home/pi/Bilder/$2
		fi
fi
		
