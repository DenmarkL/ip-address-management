<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIPAddressRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ip_address' => ['required', 'ip'],
            'label' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string'],
            'ip_type' => ['nullable', 'string', 'in:IPv4,IPv6'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $ipAddress = $this->input('ip_address');

            if ($ipAddress) {
                $this->merge([
                    'ip_type' => filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'IPv4',
                ]);
            }
        });
    }
}
