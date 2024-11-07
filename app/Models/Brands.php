<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Laptop;

class Brands extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function products():HasMany {
        return $this->hasMany(Laptop::class);
    }
}
