#include <zmq.h>

int main(void) {
  void *ctx = zmq_init(1);
  void *in = zmq_socket(ctx, ZMQ_SUB);
  void *out = zmq_socket(ctx, ZMQ_PUB);
  zmq_setsockopt(in, ZMQ_SUBSCRIBE, "", 0);
  int rcin = zmq_connect(in, "epgm://;239.192.0.1:7601");
  int rcout = zmq_bind(out, "ipc:///tmp/events");
  
  int rcd = zmq_device(ZMQ_FORWARDER, in, out);
  
  zmq_close(in); zmq_close(out); zmq_term(ctx);
  return 0;
}