<?php

namespace App\Models\API\Transactions;

use App\Models\API\Category\IncomeCategory;
use App\Models\BaseModel;

class BaseTransaction extends BaseModel
{
    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }
}
