<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
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
        'number',
        'description',
        'cep',
        'state',
        'city',
        'active',
        'file',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
