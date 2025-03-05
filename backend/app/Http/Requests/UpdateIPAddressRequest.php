<?php

namespace App\Http\Requests;

use App\Models\IPAddress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateIPAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ipAddress = IPAddress::find($this->route('id')); // Manually get the model

        if (!$ipAddress) {
            return false; // Prevent unauthorized access if not found
        }

        return Auth::user()->isSuperAdmin() || $ipAddress->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'required|string|max:255',
            'ip_address' => [
                'sometimes',
                'ip',
                Rule::unique('ip_addresses')->ignore($this->route('id')),
            ],
            'comment' => 'nullable|string|max:500',
            'ip_type' => [
                'sometimes',
                'in:IPv4,IPv6',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('ip')) {
            $this->merge([
                'ip_type' => filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'IPv4',
            ]);
        }
    }

    public function ipAddress()
    {
        return $this->route('id') ? \App\Models\IPAddress::find($this->route('id')) : null;
    }
}
