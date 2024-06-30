<?php

namespace App\Models\API\Transactions;

class Income extends BaseTransactions
{
    protected $fillable = ['user_id', 'amount', 'category_id', 'date_received', 'notes'];
}
