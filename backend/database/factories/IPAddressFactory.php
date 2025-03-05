<?php

namespace Database\Factories;

use App\Models\IPAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IPAddress>
 */
class IPAddressFactory extends Factory
{
    protected $model = IPAddress::class;

    public function definition(): array
    {
        return [
            'ip_address' => $this->faker->ipv4(), // Generates random IPv4
            'ip_type' => 'IPv4',
            'user_id' => \App\Models\User::factory(), // Ensure user exists
        ];
    }
}
