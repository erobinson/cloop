#!/bin/bash
cd /home/erobinson/diabetes/cloop/device/processes

# if usb not connected then skip
if [[ -n $(lsusb | grep Medtronic) ]]; then
    echo "[$(date)] : run_device_processes.sh : CarelinkUSB is connected." >> log/run_device_processes.log
else
    echo "[$(date)] : run_device_processes.sh : CarelinkUSB is not connected. Exiting..." >> log/run_device_processes.log
    exit 1
fi


# if already running then skip
for pid in $(pidof -x run_device_processes.sh); do
  if [ $pid != $$ ]; then
    echo "[$(date)] : run_device_processes.sh : Process is already running with PID $pid" >> log/run_device_processes.log
    exit 1
  fi
done

echo "[$(date)] : run_device_processes.sh : Starting run" >> log/run_device_processes.log

sudo timeout 600 python sync_device_pump.py
sleep 2
#sudo timeout 600 python sync_device_phone.py
#sleep 20
sudo timeout 600 python confirm_injection_process.py
sleep 2
sudo timeout 600 python injection_process.py
#sleep 30
#sudo timeout 600 python sync_device_phone.py
#sleep 2
#sudo timeout 600 python shutdown_process.py

echo "[$(date)] : run_device_processes.sh : End of run" >> log/run_device_processes.log

