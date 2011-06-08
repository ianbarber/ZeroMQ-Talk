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
	
% using my fork of the bindings here, as multipart support in 
% erlzmq2 works slightly differently in master
loop(Front, Back) ->
  receive 
    {zmq, Front, Msg} ->
      io:format("Sending Back: ~p~n",[Msg]),
      sendall(Back, Msg),
      loop(Front, Back);
    {zmq, Back, Msg} ->
      io:format("Sending Front: ~p~n",[Msg]),
      sendall(Front, Msg),
      loop(Front, Back)
  end.
  
sendall(To, [Part|[]]) ->
  erlzmq:send(To, Part);
  
sendall(To, [Part|Msg]) ->
  erlzmq:send(To, Part, [sndmore]),
  sendall(To, Msg).