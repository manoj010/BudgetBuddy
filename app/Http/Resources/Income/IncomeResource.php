<?php

namespace App\Http\Resources\Income;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $income = $this->resource;
        return [
            'category_id' => $income->category_id,
            'category_title' => $income->category->name,
            'date_received' => $income->date_received, 
            'amount' => $income->amount,
            'notes' => $income->notes
        ];
    }
}
