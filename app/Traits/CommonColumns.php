<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait CommonColumns
{
    /**
     * Add the common columns to the given table.
     *
     * @param Blueprint $table
     * @return void
     */
    protected function addCommonColumns(Blueprint $table)
    {
        $table->id();
        $table->softDeletes();
        $table->timestamps();
    }
}
