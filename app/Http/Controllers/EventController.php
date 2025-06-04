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

}
