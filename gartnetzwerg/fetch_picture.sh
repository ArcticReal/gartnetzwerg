#!/bin/sh

if [ "$1" != '' ]
	then
		if [ "$2" != '' ]
			then
				scp -i /home/pi/.ssh/id_rsa pi@$1:/home/pi/Pictures/* /home/pi/Bilder/$2
		fi
fi
		
