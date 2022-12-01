<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPhone extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'user_phones';

    public $incrementing = false;

    protected $fillable = [
        'country_code',
        'area_code',
        'number',
        'user_data_id',
    ];
}
