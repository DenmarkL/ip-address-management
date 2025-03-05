<?php

namespace Tests\Feature;

use App\Models\IPAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class IPAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_it_requires_authentication_to_create_an_ip_address()
    {
        $response = $this->postJson('/api/ip-addresses', [
            'ip_address' => '192.168.1.1',
            'label' => 'test'
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    public function test_it_can_store_an_ip_address()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/ip-addresses', [
            'ip_address' => '192.168.1.1',
            'label' => 'test'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'ip_address', 'ip_type']]);
    }

    public function test_it_can_update_an_ip_address()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api'); // Authenticate user

        $ip = IPAddress::factory()->create([
            'ip_address' => '192.168.1.100',
            'label' => 'Old Label',
            'user_id' => $user->id, // Ensure the user owns this IP
        ]);

        $response = $this->putJson("/api/ip-addresses/{$ip->id}", [
            'ip_address' => '10.0.0.1',
            'label' => 'Updated Label',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.ip_address', '10.0.0.1')
            ->assertJsonPath('data.label', 'Updated Label');

        $this->assertDatabaseHas('ip_addresses', [
            'id' => $ip->id,
            'ip_address' => '10.0.0.1',
            'label' => 'Updated Label',
        ]);
    }

    public function test_it_can_only_be_deleted_by_a_super_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);
        $ip = IPAddress::factory()->create();

        // Try to delete as a normal user
        $this->actingAs($user, 'api');
        $response = $this->deleteJson("/api/ip-addresses/{$ip->id}");
        $response->assertStatus(403); // Should be forbidden

        // Try to delete as a super admin
        $this->actingAs($admin, 'api');
        $response = $this->deleteJson("/api/ip-addresses/{$ip->id}");
        $response->assertOk()
            ->assertJson(['message' => 'Deleted successfully']);

        $this->assertDatabaseMissing('ip_addresses', ['id' => $ip->id]);
    }

    public function test_user_can_fetch_ip_addresses()
    {
        // Create a user (if authentication is required)
        $user = User::factory()->create();

        // Create sample IP addresses
        IPAddress::factory()->create([
            'ip_address' => '192.168.1.1',
            'label' => 'Home Network',
            'ip_type' => 'IPv4'
        ]);

        IPAddress::factory()->create([
            'ip_address' => '2001:db8::ff00:42:8329',
            'label' => 'Office Network',
            'ip_type' => 'IPv6'
        ]);

        // Make the request (authenticate if needed)
        $response = $this->actingAs($user, 'api')->getJson('/api/ip-addresses');

        // Assert response status
        $response->assertOk()
            ->assertJsonCount(2, 'data') // Expect 2 results
            ->assertJsonFragment(['ip_address' => '192.168.1.1'])
            ->assertJsonFragment(['ip_address' => '2001:db8::ff00:42:8329']);
    }

    public function test_user_can_search_ip_addresses()
    {
        $user = User::factory()->create();

        IPAddress::factory()->create([
            'ip_address' => '192.168.1.1',
            'label' => 'Home Network',
            'ip_type' => 'IPv4'
        ]);

        IPAddress::factory()->create([
            'ip_address' => '10.0.0.1',
            'label' => 'Work Network',
            'ip_type' => 'IPv4'
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/ip-addresses?search=Work Network');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['label' => 'Work Network']);
    }
}
