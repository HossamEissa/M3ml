<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'factory_id', 'description', 'photo_path'
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

}
