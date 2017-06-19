#!/bin/bash

DEVICE=$1   # mac_address;


ip=`sudo arp-scan -l -q -g -T $DEVICE | grep -m1 -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}' | tr -d '\n'`
echo -n $ip
