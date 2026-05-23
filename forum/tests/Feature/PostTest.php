<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_posts_index()
    {
        $response = $this->get('/posts');
        $response->assertStatus(200);
        $response->assertViewIs('posts.index');
    }

    public function test_can_create_post_when_authenticated()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Test Post',
            'content' => 'This is test content for the post',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Test Post',
        ]);
    }

    public function test_cannot_create_post_when_guest()
    {
        $response = $this->post('/posts', [
            'title' => 'Test Post',
            'content' => 'Content',
            'status' => 'published',
        ]);

        $response->assertStatus(403);
    }

    public function test_can_view_post()
    {
        $post = Post::factory()->create();

        $response = $this->get("/posts/{$post->id}");
        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_can_edit_own_post()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->patch("/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content here',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_cannot_edit_others_post()
    {
        $user1 = User::factory()->create(['email_verified_at' => now()]);
        $user2 = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->patch("/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'published',
        ]);

        $response->assertStatus(403);
    }

    public function test_can_delete_own_post()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/posts/{$post->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_cannot_delete_others_post()
    {
        $user1 = User::factory()->create(['email_verified_at' => now()]);
        $user2 = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete("/posts/{$post->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_draft_posts_only_visible_to_owner()
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $viewer = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $owner->id, 'status' => 'draft']);

        // Owner can view
        $response = $this->actingAs($owner)->get("/posts/{$post->id}");
        $response->assertStatus(200);

        // Other user cannot view
        $response = $this->actingAs($viewer)->get("/posts/{$post->id}");
        $response->assertStatus(404);

        // Guest cannot view
        $response = $this->get("/posts/{$post->id}");
        $response->assertStatus(404);
    }

    public function test_can_change_post_status()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user->id, 'status' => 'draft']);

        $response = $this->actingAs($user)->patch("/posts/{$post->id}", [
            'title' => $post->title,
            'content' => $post->content,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => 'published',
        ]);
    }

    public function test_cannot_create_post_with_invalid_data()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // Title too short
        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Hi',
            'content' => 'This is content that is long enough',
            'status' => 'published',
        ]);
        $response->assertSessionHasErrors('title');

        // Content too short
        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Valid Title',
            'content' => 'short',
            'status' => 'published',
        ]);
        $response->assertSessionHasErrors('content');

        // Invalid status
        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Valid Title',
            'content' => 'Content that is long enough',
            'status' => 'invalid',
        ]);
        $response->assertSessionHasErrors('status');
    }

    public function test_published_posts_visible_to_guests()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        $response = $this->get("/posts/{$post->id}");
        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_posts_list_is_paginated()
    {
        Post::factory(15)->create(['status' => 'published']);

        $response = $this->get('/posts');
        $response->assertStatus(200);
        $response->assertViewHas('posts');
    }

    public function test_post_timestamps_are_correct()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertNotNull($post->created_at);
        $this->assertNotNull($post->updated_at);
    }

    public function test_post_soft_delete_works()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->delete("/posts/{$post->id}");

        $this->assertSoftDeleted($post);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
