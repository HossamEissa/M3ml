<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name' ,'name', 'title', 'description', 'active' , 'facebook','whatsApp'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_factory_pivots'
            , 'factory_id', 'user_id');
    }

    public function offers()
    {
        return $this->hasMany(FactoryOffer::class, 'factory_id');
    }
}
