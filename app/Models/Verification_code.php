<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification_code extends Model
{
    use HasFactory;

    protected $table = "user_verification_code";
    protected $fillable = ['user_id', 'code', 'created_at', 'updated_at'];
}
