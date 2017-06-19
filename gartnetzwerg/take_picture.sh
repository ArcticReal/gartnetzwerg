#!/bin/bash

ip=$1
foldername=$2


ssh -i /home/pi/.ssh/id_rsa pi@$ip -t /home/pi/gartnetzwerg/take_picture.py

sudo scp -i /home/pi/.ssh/id_rsa pi@$ip:/home/pi/Pictures/* /var/www/html/gartnetzwerg/Pictures/$foldername/

ssh -i /home/pi/.ssh/id_rsa pi@$ip -t /home/pi/gartnetzwerg/remove_picture.sh

sudo -u root chown -R www-data:www-data /var/www/html/gartnetzwerg/Pictures/