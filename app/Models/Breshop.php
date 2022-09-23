<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cep',
        'state',
        'city',
        'rate',
        'active',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}