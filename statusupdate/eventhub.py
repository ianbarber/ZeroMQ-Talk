import time
import zmq
from zmq.devices.basedevice import ProcessDevice

pd = ProcessDevice(zmq.STREAMER, zmq.PULL, zmq.PUB)
pd.bind_in("tcp://*:6767")
pd.connect_out("epgm://eth0;239.192.0.1:7601")
pd.setsockopt_out(zmq.RATE, 10000)
pd.start()

# Do other things here

# This is just to pretend do some foreground work. 
while True:
    time.sleep(100)
