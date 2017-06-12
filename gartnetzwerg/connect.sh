#!/bin/sh

output=""
ip=""
VERBOSE=1
DEVICE=$1
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

# searches subnet from the host-device 
# (internally prints all the devices that are in that subnet)
# saves output into variable
search_nmap () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         searching subnet \"$subnet\" (this might take a while) ..."
    fi
    
    output=`sudo arp-scan -l -T $DEVICE`
}

# greps the nmap_output (all the devices in the subnet) and seperates it into a single IP (for the remote-device)
grep_remote_ip () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         searching output..."
    fi
   
    
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         cleaning up output..."
    fi
    
    ip=`echo $output | grep -m1 -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}' | tr -d '\n'`
    
}

# connects to Basisstation via ssh and executes the remote command
connect () {
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

# checks if variable from above is empty (no remote-node found) and - if not - saves it.
save_last_ip () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         check nmap_output..."
    fi

    if [ "$VERBOSE" -eq 1 ]; then
        echo "         IP saved."
    fi
    connect
}

# cleans up all variables
clean_up () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         cleaning up variables..."
    fi
    
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
    search_nmap
    grep_remote_ip
    save_last_ip
}

# checks, if the ip in the file is responding, and either trys to connect fully, or searches manually
check_last_ip_file () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         takes IP from txt..."
    fi
    old_ip=`cat last_ip.txt`

    if [ "$VERBOSE" -eq 1 ]; then
        echo "         checks if ssh works or not..."
    fi
    check=`nmap $old_ip -PN -p ssh | grep open`
    if [ -z "$check" ]
      then
        if [ "$VERBOSE" -eq 1 ]; then
            echo "         IP not responding, remove file and search manually..."
        fi
        rm last_ip.txt
        search_thingy $1
      else
        if [ "$VERBOSE" -eq 1 ]; then
            echo "         IP responds. try to connect..."
        fi
        connect
    fi
}

# checks, if there is a last_ip file, and either checks it, or searches manually
check_for_ip_file () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         checks for \"last_ip.txt\"..."
    fi

    if [ -f last_ip.txt ]
      then
        if [ "$VERBOSE" -eq 1 ]; then
            echo "         file found."
        fi
        #check_last_ip_file $1
	    search_thingy
      else
        if [ "$VERBOSE" -eq 1 ]; then
            echo "         file not found."
        fi
        search_thingy
    fi
}

# checks for hostname to check for internet connection
init () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "[GNW RC] check for internet connection..."
    fi
    # get's the laptop's IP and greps it into a variable
    # adds 0/24 instead of the last number, for later subnet-search
    host_ip_connection_test=`hostname -I | grep -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.'`
    if [ -z "$host_ip_connection_test" ]
      then
        echo "         GNW Error-Code #RC1 - Host-Gerät scheint keine Internetverbindung zu besitzen."
        exit 1
      else
        if [ "$VERBOSE" -eq 1 ]; then 
            echo "         moving on..."
        fi
        check_for_ip_file
    fi
    clean_up
}

init0 () {
    if [ $# -eq 0 ]
      then
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

init0 $1 $2 $3

#exit 0
