<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\ParticipateRequest;
use App\Http\Requests\CancelEventRequest;
use App\Models\User;
use App\Models\UserEvent;
use Auth;

class EventController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
     $user = Auth::user();
     $events = Event::all();
     $userEvent = User::with('events')->get();
     return response()->json([
       "result" => $events,
       "userResult" => $userEvent,
       'user' => $user
     ]);
   } catch (\Exception $e){
     return response()->json([
       "error" => $e->getMessage(),
       "result" => null
     ]);
   }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreEventRequest $request)
  {
    try {
      $input = $request->all();

      $event = new Event;
      $event->title = $input['title'];
      $event->text = $input['text'];
      $event->creator_id = Auth::user()->id;
      $event->save();

      return response()->json([
          "result" => "Мероприятие успешно создано!"
      ]);
    } catch (\Exception $e){
      return response()->json([
        "error" => $e->getMessage(),
        "result" => null
      ]);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function participate(ParticipateRequest $request)
  {
    try {
      $input = $request->all();

      $participate = new UserEvent;
      $participate->event_id = $input['event_id'];
      $participate->user_id = Auth::user()->id;
      $participate->save();

      return response()->json([
        "result" => "Вы сейчас участвуете в мероприятии!"
      ]);
    } catch (\Exception $e){
      return response()->json([
        "error" => $e->getMessage(),
        "result" => null
      ]);
    }
  }

  public function cancel_event(CancelEventRequest $request)
  {
    try {
      $input = $request->all();

      $participate = UserEvent::where('event_id', $input['event_id'])
                              ->where('user_id', Auth::user()->id);
      $participate->destroy();

      return response()->json([
        "result" => "Вы сейчас отказались от мероприятии!"
      ]);
    } catch (\Exception $e){
      return response()->json([
        "error" => $e->getMessage(),
        "result" => null
      ]);
    }

  }

  /**
   * Display the specified resource.
   */
  public function show(Event $event)
  {
    try {
      $eventSource = Event::where('id', $event->id)
                          ->with('users')
                          ->first();

      return response()->json([
        "eventSource" => $eventSource
      ]);
    } catch (\Exception $e){
      return response()->json([
        "error" => $e->getMessage(),
        "result" => null
      ]);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateEventRequest $request, Event $event)
  {
    dd(__METHOD__);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Event $event)
  {
    try {
      if($event->creator_id == Auth::user()->id) {
        Event::destroy($event->id);
        $userEvents = UserEvent::where('event_id', $event->id);
        $userEvents->destroy();

        return response()->json([
            "result" => "Мероприятие успешно удалено!"
        ]);
      } else {
        return response()->json([
            "result" => "Вы не можете удалить это мероприятие"
        ]);
      }
    } catch (\Exception $e){
      return response()->json([
        "error" => $e->getMessage(),
        "result" => null
      ]);
    }
  }
}
