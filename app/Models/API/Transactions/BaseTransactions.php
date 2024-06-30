<?php

namespace App\Models\API\Transactions;

use App\Models\API\Category\IncomeCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseTransactions extends Model
{
    use HasFactory, SoftDeletes;

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }
}
