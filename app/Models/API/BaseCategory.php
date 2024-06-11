<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'name', 'description'];
}
