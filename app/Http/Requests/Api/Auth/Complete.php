<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\Currency;
use App\Enums\StoreActivityType;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class Complete extends ApiRequest
{
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'store_name' => ['required', 'string'],
            'activity' => ['required', 'in:' . implode(',', StoreActivityType::toArray())],
            'lat' => ['required', 'string'],
            'lng' => ['required', 'string'],
            'address' => ['required', 'string'],
            'commercial_register' => ['required', 'string'],
            'currency' => ['required', 'in:' . implode(',', Currency::toArray())],
            'store_size' => ['required', 'in:small,medium,large'],
        ];
    }
}
