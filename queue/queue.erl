-module(queue).
-export([run/0]).

run() ->
  {ok, Ctx} = erlzmq:context(),
  {ok, Front} = erlzmq:socket(Ctx, [xrep, {active,true}]),
  {ok, Back} = erlzmq:socket(Ctx, [xreq, {active,true}]),
  ok = erlzmq:bind(Front, "tcp://*:5454"),
  ok = erlzmq:bind(Back, "tcp://*:5455"),
  
  loop(Front, Back),
  
  ok = erlzmq:close(Front),
	ok = erlzmq:close(Back),
	ok = erlzmq:term(Ctx).
	
loop(Front, Back) ->
  receive 
    {zmq, Front, Msg, Flags} ->
      io:format("Sending Back: ~p~n",[Msg]),
      sendall(Back, Msg, Flags),
      loop(Front, Back);
    {zmq, Back, Msg, Flags} ->
      io:format("Sending Front: ~p~n",[Msg]),
      sendall(Front, Msg, Flags),
      loop(Front, Back)
  end.
  
sendall(To, Part, [rcvmore|_Flags]) ->
  erlzmq:send(To, Part, [sndmore]);
sendall(To, Part, _Flags) ->
  erlzmq:send(To, Part).