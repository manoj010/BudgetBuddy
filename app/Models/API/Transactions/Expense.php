<?php

namespace App\Models\API\Transactions;

class Expense extends BaseTransaction
{
    protected $fillable = ['user_id', 'amount', 'category_id', 'date_spent', 'notes', 'recurring'];
}
