<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
// Amo como você é inteligente e empenhado com tudo <3
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Breshop extends Model
{
    use HasApiTokens, HasFactory, Notifiable, Uuid;
    protected $keyType = 'string';
    protected $table = 'breshops';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'active',
        'file',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    // Eu amo você!
}
