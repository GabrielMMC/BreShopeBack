<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserData extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'users_data';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'document',
        'birthdate',
        'gender',
        'file_path',
        'user_id',
    ];

    public function address()
    {
        return $this->hasOne(UserAddress::class, 'user_data_id', 'id');
    }

    public function phone()
    {
        return $this->hasOne(UserPhone::class, 'user_data_id', 'id');
    }
}
