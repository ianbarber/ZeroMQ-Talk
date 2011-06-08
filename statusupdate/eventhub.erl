-module(eventhub).
-export([run/0]).

run() ->
  {ok, Ctx} = erlzmq:context(),
  {ok, Out} = erlzmq:socket(Ctx, [pub]),
  ok = erlzmq:connect(Out, "epgm://eth0;239.192.0.1:7601"),
  erlzmq:setsockopt(Out, [{rate, 10000}]),
  {ok, In} = erlzmq:socket(Ctx, [pull]),
  ok = erlzmq:bind(In, "tcp://*:6767"),
  loop(In, Out).
  
loop(In, Out) ->
  {ok, Msg} = erlzmq:recv(In),
  ok = erlzmq:send(Out, Msg),
  loop(In, Out).