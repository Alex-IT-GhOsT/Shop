<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Brands;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'current_price',
        'old_price',
        'href',
        'image_src',
        'image_alt',
        'reviews_link',
        'discount',
        'superprice'
    ];

    public function brand():BelongsTo {
        return $this->belongsTo(Brands::class);
    }
}
