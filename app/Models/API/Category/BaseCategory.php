<?php

namespace App\Models\API\Category;

use App\Models\BaseModel;

class BaseCategory extends BaseModel
{
    protected $fillable = ['name', 'description'];
}
