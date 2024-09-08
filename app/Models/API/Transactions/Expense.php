<?php

namespace App\Models\API\Transactions;

class Expense extends BaseTransaction
{
    protected $fillable = ['amount', 'category_id', 'date_spent', 'notes', 'recurring'];
}
