-module(repeater).
-export([init/1, terminate/1, handle_event/2]).
-export([run/0]).

init(_) ->
  spawn_link(?MODULE, run, []).

terminate(Pid) ->
  Pid ! {cmd, kill}.
  
handle_event(Event, Pid) ->
  Pid ! Event.

run() ->
  {ok, Ctx} = erlzmq:context(),
  {ok, Sock} = erlzmq:socket(Ctx, [pub]),
  ok = erlzmq:bind(Sock, "tcp://*:5656"),
  loop(Sock).

loop(Sock) ->
  receive
    {cmd, kill} ->
      ok;
    {Action, Id, Event} -> 
      Json = rfc4627:encode({obj, [{action, Action}, {id, Id}, {event, Event}]}),
      erlzmq:send(Sock, list_to_binary(Json)),
      loop(Sock)
  end.
  