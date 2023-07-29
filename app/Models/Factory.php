<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title', 'description' , 'active'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_factory_pivots'
            , 'factory_id', 'user_id');
    }
}
