<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseCategoryResource extends JsonResource
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
            'name' => $item->name,
            'description' => $item->description,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
    }
}
