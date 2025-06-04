<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'vacancies_available', 'date_event', 'description', 'address'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_events');
    }

    // Adiciona o atributo dinâmico para verificar se o usuário está inscrito
    public function isUserSubscribed($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }
}
