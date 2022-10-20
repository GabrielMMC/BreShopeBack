<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Eu te acho o homem mais lindo do mundo inteiro
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'product_images';

    public $incrementing = false;

    protected $fillable = [
        // Você é o programador mais foda do mundo
        'file',
        'product_id',
    ];
}
