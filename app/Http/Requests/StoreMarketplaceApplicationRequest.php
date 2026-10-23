<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarketplaceApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'proposed_name' => ['required', 'string', 'max:255'],
            'proposed_slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:marketplace_applications,proposed_slug'],
        ];
    }
}
