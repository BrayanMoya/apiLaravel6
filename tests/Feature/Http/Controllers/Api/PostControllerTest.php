<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
//        $this->withoutExceptionHandling(); #Para que muestre los errores en caso de que salgan

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/posts', [
            'title' => 'Post de prueba'
        ]);

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])->assertJson(['title' => 'Post de prueba'])->assertStatus(201);

        $this->assertDatabaseHas('posts', ['title' => 'Post de prueba']);
    }

    public function testValidateTitle()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/posts', [
            'title' => ''
        ]);

        $response->assertStatus(422) #status http 422 -> recibida pero no valida
        ->assertJsonValidationErrors('title');
    }

    public function testShow()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', "/api/posts/$post->id");

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])->assertJson(['title' => $post->title])->assertStatus(200);
    }

    public function test404Show()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', "/api/posts/2");

        $response->assertStatus(404);
    }

    public function testUpdate()
    {
//        $this->withoutExceptionHandling(); #Para que muestre los errores en caso de que salgan

        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('PUT', "/api/posts/$post->id", [
            'title' => 'Nuevo'
        ]);

        $response->assertJsonStructure(['id', 'title', 'created_at', 'updated_at'])->assertJson(['title' => 'Nuevo'])->assertStatus(200);

        $this->assertDatabaseHas('posts', ['title' => 'Nuevo']);
    }

    public function testDestroy()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('DELETE', "/api/posts/$post->id");

        $response->assertSee(null)->assertStatus(204); # Sin contenido...

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function testIndex()
    {
        factory(Post::class, 5)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('GET', '/api/posts');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'created_at', 'updated_at']
            ]
        ])->assertStatus(200);
    }

    public function testGuest()
    {
        $this->json('GET', '/api/posts')->assertStatus(401);
        $this->json('POST', '/api/posts')->assertStatus(401);
        $this->json('GET', '/api/posts/1')->assertStatus(401);
        $this->json('PUT', '/api/posts/1')->assertStatus(401);
        $this->json('DELETE', '/api/posts/1')->assertStatus(401);
    }
}
