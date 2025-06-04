<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\UserEvent;



class EventController extends Controller
{
    function index()
    {
        $userId = session('user');

        $ativos = 0;
        $agendados = 0;
        $concluidos = 0;
        $incritos = UserEvent::where('user_id', $userId)->count();

        $events = Event::all()->map(function ($event) use ($userId, $ativos, $agendados, $concluidos) {
            $event->is_subscribed = $event->isUserSubscribed($userId);
            return $event;
        });

        foreach($events as $event){
            if(strtotime($event->date_event) == strtotime(date("Y-m-d"))){
                $ativos++;
            }else if(strtotime($event->date_event) < strtotime(date("Y-m-d"))){
                $concluidos++;
            }else if(strtotime($event->date_event) > strtotime(date("Y-m-d"))){
                $agendados++;
            }
        }
        
        return view('index', ['events' => $events, 'ativos' => $ativos, 'agendados' => $agendados, 'incritos' => $incritos, 'concluidos' => $concluidos, 'user_id' => session('user')]);
    }

    function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'vacancies_available' => 'required|integer|max:1000',
            'date_event' => 'required|date',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
        ], [], [
                'name' => 'nome do evento',
                'vacancies_available' => 'vagas disponíveis',
                'date_event' => 'data do evento',
                'description' => 'descrição',
                'address' => 'endereço',
        ]);

        try{
            Event::create($attributes);

            return redirect()->route('dashboard')->with('status_success', 'Evento criado com sucesso!');
        }catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors(['message_error' => 'Erro ao cadastrar evento', 'error' => $e->errors()]);
        }
    }

    function subscribeEvent(Request $request)
    {
        $attributes = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'event_id' => 'required|integer|exists:events,id',
        ]);

        try{
            if(UserEvent::where('user_id', $attributes['user_id'])->where('event_id', $attributes['event_id'])->count() > 0){
                return back()->withErrors(['message_error' => 'Você ja esta inscrito nesse evento']);
            }

            $event = Event::where('id', $attributes['event_id'])->first();

            if( strtotime(date("Y-m-d")) > strtotime($event['date_event'])){
                return back()->withErrors(['message_error' => 'Não é possível se inscrever em evento que ja tem prazo terminado']);
            }

            UserEvent::create($attributes);
            
            return back()->with(['status_success' => 'Parabéns! Você se inscreveu no evento']);
        }catch (\Throwable $th) {
            return back()->withErrors(['message_error' => 'Erro ao se inscrever no evento']);
        }
    }
}
