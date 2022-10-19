<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'product_sizes';
    // Obrigada por se esforçar no nosso TG!

    public $incrementing = false;

    protected $fillable = [
        'pp',
        'p',
        'm',
        'g',
        'gg',
        'xg',
        'product_id',
        // Eu te amo!
    ];
}
