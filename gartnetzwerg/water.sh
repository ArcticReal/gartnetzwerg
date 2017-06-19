#!/bin/bash

ip=$1
path=$2

sudo ssh -i /home/pi/.ssh/id_rsa pi@$ip -t $path