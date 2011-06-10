-module(logserv).
-export([run/0]).

run() -> 
  {ok, Ctx} = erlzmq:context(),
  {ok, In} = erlzmq:socket(Ctx, [pull, {active,true}]),
  ok = erlzmq:bind(In, "tcp://*:5555"),
  loop(In).

loop(In) ->
  receive
    {zmq, In, Msg, _Flags} ->
      {ok,{obj, [{_, Time}, {_, Log}] }, [] } = rfc4627:decode(Msg),
      io:format("~B ~s~n", [Time, Log]),
      loop(In)
  end.