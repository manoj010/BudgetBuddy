<?php

namespace App\Http\Requests;

use App\Traits\AppResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0',
            'category_id' => [
                'required',
                Rule::exists('expense_categories', 'id')->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->whereNull('deleted_at');
                }),
            ],
            'date_spent' => 'required|date',
            'notes' => 'nullable|string',
            'recurring' => 'boolean|required',
        ];
    }
}
