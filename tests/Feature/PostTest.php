<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_send_not_found_on_non_existent_post(): void
    {
        $response = $this->get('/postscvlkc');

        $response->assertStatus(404);
    }

    public function test_ok_on_post_post(): void
    {
        // Créer et authentifier un utilisateur
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Test',
            'body' => 'Test',
            'slug' => 'test',
            'categories' => [1, 2],
        ]);

        $response->assertRedirect('/posts');
    }
}
