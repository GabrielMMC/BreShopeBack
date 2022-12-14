<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'customers';

    public $incrementing = false;

    protected $fillable = [
        'customer_id',
        'user_id',
    ];

    public function address()
    {
        return $this->hasMany(UserAddress::class, 'customer_id', 'id');
    }

    public function card()
    {
        return $this->hasMany(Card::class, 'customer_id', 'id');
    }
}
