<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFactoryPivot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'factory_id'
    ];

    public function images()
    {
        return $this->hasMany(Document::class, 'user_factory_id');
    }
}
