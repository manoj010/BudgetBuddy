<?php

namespace App\Http\Requests;

use App\Traits\AppResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseCategoryRequest extends FormRequest
{
    use AppResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('income_categories', 'name')
                    ->ignore($this->income_category)
                    ->where(function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->whereNull('deleted_at')
            ],
            'description' => 'nullable|string|max:1000'
        ];
    }
}
