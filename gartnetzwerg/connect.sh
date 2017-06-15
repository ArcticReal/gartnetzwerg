#!/bin/sh

output=""
ip=""
VERBOSE=1 #debug mode (yes/no)
DEVICE=$1 #mac-address to search
REMOTE=$2 #the remote command to execute

# if parameter 3 (debug) is there, set VERBOSE-Mode
if [ "$#" -gt "2" ]; then
    if [ "$3" != "debug" ]; then
        VERBOSE=0
    else
        VERBOSE=1
    fi
else
    VERBOSE=0
fi

connect () {
    # connects to Basisstation via ssh and executes the remote command
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         trying to connect to saved ip..."
    fi
    pi="pi@"
    ip_with_user=$pi$ip
    if [ "$#" -eq 1 ]; then
        ssh $ip_with_user -p 22
    else
        ssh $ip_with_user -p 22 -t "$REMOTE"
    fi
}

clean_up () {
    # cleans up all variables
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         cleaning up variables..."
    fi
    unset host_ip_connection_test
    unset pi
    unset ip_with_user
    unset output
    unset node_ip
    unset ip
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         bye."
    fi
}

search_thingy () {
    # searches subnet from the host-device (internally prints all the devices that are in that subnet)
    # saves output into variable
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         searching subnet \"$subnet\"..."
    fi
    output=`sudo arp-scan -l -T $DEVICE`

    # greps the ip from output
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         checking output..."
    fi
    ip=`echo $output | grep -m1 -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}' | tr -d '\n'`
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         IP found."
    fi
    connect
}

init () {
    # checks for hostname to check for internet connection
    if [ "$VERBOSE" -eq 1 ]; then
        echo "[GNW RC] check for internet connection..."
    fi
    host_connection_test=`hostname -I | grep -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.'`
    if [ -z "$host_ip_connection_test" ]; then
        echo "         GNW Error-Code #RC1 - Host-Gerät scheint keine Internetverbindung zu besitzen."
       # exit 1
	search_thingy
    else
        if [ "$VERBOSE" -eq 1 ]; then 
            echo "         moving on..."
        fi
        search_thingy
    fi
    clean_up
}

init0 () {
    if [ $# -eq 0 ]; then
        echo "[GNW RC] Usage: sh ./connect.sh GERÄT SENSOR [debug]"
        echo "         GERÄT: <MAC-Adresse>"
        echo "                Basis: B8:27:EB:BD:F1:A7"
        echo "                Node1: B8:27:EB:A6:6E:F5"
        echo "                Node2: B8:27:EB:65:CB:2B"
        echo "                Node3: B8:27:EB:6E:9D:DD"
        echo "         SENSOR: at|ah|l|ws|st|sh|cam"
        echo "                 zB. 'sudo python3 /home/pi/Adafruit_Python_DHT/sensor_at.py'"
        echo "         debug (for debug-output)"
    else
        init
    fi
}
init0 $1 $2
