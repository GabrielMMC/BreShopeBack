<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'user_address';

    public $incrementing = false;

    protected $fillable = [
        'country',
        'state',
        'city',
        'zip_code',
        'number',
        'street',
        'nbhd',
        'user_id',
    ];
}
