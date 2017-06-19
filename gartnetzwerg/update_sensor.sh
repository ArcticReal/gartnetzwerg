#!/bin/bash

ip=$1
path=$2

output=`sudo ssh -i /home/pi/.ssh/id_rsa pi@$ip -t $path`
echo -n $output
		