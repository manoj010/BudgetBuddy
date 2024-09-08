<?php

namespace App\Models\API\Transactions;

class Income extends BaseTransaction
{
    protected $fillable = ['amount', 'category_id', 'date_received', 'notes', 'created_by', 'updated_by'];
}
