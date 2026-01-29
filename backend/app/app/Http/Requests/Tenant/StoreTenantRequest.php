<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'domain' => ['required_without:domains', 'string', 'max:255'],
            'domains' => ['required_without:domain', 'array', 'min:1'],
            'domains.*' => ['string', 'max:255'],
        ];
    }
}
