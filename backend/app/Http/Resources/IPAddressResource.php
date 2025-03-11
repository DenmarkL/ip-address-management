<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IPAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ip_address' => $this->ip_address,
            'ip_type' => $this->ip_type, // Return IPv4 or IPv6
            'label' => $this->label,
            'comment' => $this->comment,
            'user_id' => $this->user->id
            // 'user' => [
            //     'id' => $this->user->id,
            //     'name' => $this->user->name,
            // ],
            // 'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
