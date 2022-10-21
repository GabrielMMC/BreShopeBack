<?php

// Um dia, eu espero me chamar algo como: Lana Lima Facioni

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// A gente ia ser tipo a máfia italiana, só que no br
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'products';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'material',
        'price',
        'description',
        'damage_description',
        'quantity',
        'breshop_id',
        // Eu te amo!
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function sizes()
    {
        return $this->hasOne(ProductSize::class, 'product_id', 'id');
    }
}
