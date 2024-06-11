<?php

namespace App\Http\Resources\Category\IncomeCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item = $this -> resource;
        return [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'name' => $item->name,
            'description' => $item->description,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    }
}
