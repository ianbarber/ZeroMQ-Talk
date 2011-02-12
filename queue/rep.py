import zmq

context = zmq.Context()
server = context.socket(zmq.REP)
server.connect("tcp://localhost:5455")

while True:
    message = server.recv()
    print "Sending", message, "World\n"
    server.send(message + " World")