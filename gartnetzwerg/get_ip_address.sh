#!/bin/bash

DEVICE=$1   # mac_address;

output=`sudo arp-scan -l -q -g -T $DEVICE`
ip=`echo $output | grep -m1 -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}' | tr -d '\n'`
echo -n $ip
