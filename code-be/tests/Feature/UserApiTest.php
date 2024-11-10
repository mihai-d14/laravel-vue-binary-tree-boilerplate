<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class UserApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tree_type' => 'bst',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'tree_type',
                        '_lft',
                        '_rgt'
                    ]
                ]);
    }

    public function test_can_retrieve_users()
    {
        User::factory()->create(['tree_type' => 'bst']);
        User::factory()->create(['tree_type' => 'avl']);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                                'email',
                                'tree_type'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create(['tree_type' => 'bst']);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'tree_type' => 'bst'
        ];

        $response = $this->putJson("/api/v1/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'Success',
                    'data' => [
                        'name' => 'Updated Name',
                        'email' => 'updated@example.com'
                    ]
                ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create(['tree_type' => 'bst']);

        $response = $this->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->postJson('/api/v1/users', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'tree_type']);
    }
}