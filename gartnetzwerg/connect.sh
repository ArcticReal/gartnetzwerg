#!/bin/bash

subnet=""
nmap_output=""
test_old_ip=""
VERBOSE=1
DEVICE=$1
REMOTE=$2 #the remote kommand to execute

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

# get's the hosts-device IP and greps it into a file (without \n at the end and without the last number)
# adds 0/24 instead of the last number, for later subnet-search
find_host_id () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         asking hostname..."
    fi
    
    host_ip=`hostname -I | grep -Eo -m 1 '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.'| head -1`
    ip_append="0/24" #TODO 0/24
    #ip_append2="1/24"
    #ip_append3="*"
    subnet=$host_ip$ip_append
    #subnet2=$host_ip$ip_append2
    #subnet3=$host_ip$ip_append3
}

# searches subnet from the host-device 
# (internally prints all the devices that are in that subnet)
# saves output into variable
search_nmap () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         searching subnet \"$subnet\" (this might take a while) ..."
    fi
    
    nmap_output=`sudo nmap -sn $subnet`
}

# greps the nmap_output (all the devices in the subnet) and seperates it into a single IP (for the remote-device)
grep_remote_ip () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         searching nmap_output..."
    fi
    
    #fritz_test=`$nmap_output | grep -Eo -i "fritz.box"`
    #echo $nmap_output | grep -i "Intel"
    #if [ echo "$nmap_output" | grep -q "fritz.box" ]; then
        #if router == something else (Hochschule/Pierre's)
        #node_ip=`echo $nmap_output | tr ')' '\n' | grep -B 1 -i "$1"` #SensorEinheit #Basiseinheit
      #else
        #if router == fritz.box (Horn's/Bratrich's)
    node_ip=`echo $nmap_output | tr ')' '\n' | grep -B 2 -i "$DEVICE"` #SensorEinheit #Basiseinheit
    #fi

    if [ "$VERBOSE" -eq 1 ]; then
        echo "         cleaning up nmap_output..."
    fi
    
    test_old_ip=`echo $node_ip | grep -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}' | tr -d '\n'`
}

# connects to Basisstation via ssh and executes the remote command
connect () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         trying to connect to saved ip..."
    fi
   
    old_ip=`cat last_ip.txt`
    pi="pi@"
    old_ip_with_user=$pi$old_ip

    if [ "$#" -eq 1 ]; then
        ssh $old_ip_with_user -p 22
      else
        ssh $old_ip_with_user -p 22 -t "$REMOTE"
    fi
}

# checks if variable from above is empty (no remote-node found) and - if not - saves it.
save_last_ip () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         check nmap_output..."
    fi

    if [ -z "$test_old_ip" ]
      then
        echo -e "\n         Gartnetzerg-Fehlercode #RC2 -- Das Gerät '$DEVICE' konnte"
        echo -e "         nicht gefunden werden. Bitte stellen Sie sicher, dass das"
        echo -e "         '$DEVICE' korrekt angeschlossen und aktiviert ist,"
        echo -e "         und dass diese sich auch im gleichen Netzwerk wie"
        echo -e "         ihr Endgerät befindet.\n"
      else
        if [ "$VERBOSE" -eq 1 ]; then
            echo "         IP saved."
        fi
        echo $test_old_ip > last_ip.txt
        connect
    fi
}

# cleans up all variables
clean_up () {
    if [ "$VERBOSE" -eq 1 ]; then
        echo "         cleaning up variables..."
    fi

    unset host_ip
    unset ip_append
    unset subnet

    unset old_ip
    unset pi
    unset old_ip_with_user

    unset nmap_output
    unset node_ip
    unset test_old_ip

    unset host_ip_connection_test

    unset fritz_test

    if [ "$VERBOSE" -eq 1 ]; then
        echo "         bye."
    fi
}

search_thingy () {
    find_host_id
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
