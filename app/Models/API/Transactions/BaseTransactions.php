<?php

namespace App\Models\API\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseTransactions extends Model
{
    use HasFactory, SoftDeletes;
}
