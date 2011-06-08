import zmq
bufSz = 3; ctx = zmq.Context()
inp = ctx.socket(zmq.PULL)
out = ctx.socket(zmq.PUSH)
inp.bind("ipc:///tmp/logger")
out.connect("tcp://localhost:5555")
msgs = []

while True:
  msg = inp.recv()
  print "Received Log"
  msgs.append(msg)
  if(len(msgs) == bufSz):
    print "Forwarding Buffer"
    for i, msg in enumerate(msgs):
      out.send(msg, (0, zmq.SNDMORE)[i < bufSz-1])
    msgs = []