-module(erlrep).
-export[run/0].

run() -> 
  {ok, Context} = erlzmq:context(),
  {ok, Socket} = erlzmq:socket(Context, rep),
  ok = erlzmq:bind(Socket, "tcp://*:5454"),
  loop(Socket).
  
loop(Socket) -> 
  {ok, Msg, _F} = erlzmq:recv(Socket),
  Reply = binary_to_list(Msg) ++ " World",
  io:format("Sending ~s~n", [Reply]),
  ok = erlzmq:send(Socket, list_to_binary(Reply)),
  loop(Socket).