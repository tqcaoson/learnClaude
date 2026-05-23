<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // Creating Comments
    public function test_can_add_comment_when_authenticated()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Great post! Very informative.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Great post! Very informative.',
        ]);
    }

    public function test_cannot_add_comment_when_guest()
    {
        $post = Post::factory()->create();

        $response = $this->post("/posts/{$post->id}/comments", [
            'content' => 'Comment text',
        ]);

        $response->assertStatus(403);
    }

    public function test_cannot_add_empty_comment()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_cannot_add_comment_exceeding_length()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => str_repeat('a', 1001),
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_comment_must_have_content()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", []);

        $response->assertSessionHasErrors('content');
    }

    // Deleting Comments
    public function test_can_delete_own_comment()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($user)->delete("/comments/{$comment->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function test_cannot_delete_others_comment()
    {
        $user1 = User::factory()->create(['email_verified_at' => now()]);
        $user2 = User::factory()->create(['email_verified_at' => now()]);
        $comment = Comment::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete("/comments/{$comment->id}");

        $response->assertStatus(403);
    }

    public function test_guest_cannot_delete_comment()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->delete("/comments/{$comment->id}");

        $response->assertStatus(403);
    }

    // Comment Relationships
    public function test_comment_displays_on_post()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create(['status' => 'published']);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Excellent explanation!',
        ]);

        $response = $this->get("/posts/{$post->id}");
        $response->assertSee('Excellent explanation!');
    }

    public function test_comment_shows_author_name()
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'name' => 'John Doe']);
        $post = Post::factory()->create(['status' => 'published']);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get("/posts/{$post->id}");
        $response->assertSee('John Doe');
    }

    // Comment Count
    public function test_post_shows_comment_count()
    {
        $post = Post::factory()->create(['status' => 'published']);
        Comment::factory(5)->create(['post_id' => $post->id]);

        $response = $this->get("/posts/{$post->id}");
        $response->assertSee('5');
    }

    // Comment Timestamps
    public function test_comment_has_timestamps()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();
        
        $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Test comment',
        ]);

        $comment = Comment::first();
        $this->assertNotNull($comment->created_at);
        $this->assertNotNull($comment->updated_at);
    }

    // Comment Soft Deletes
    public function test_deleted_comment_uses_soft_delete()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->delete("/comments/{$comment->id}");

        $this->assertSoftDeleted($comment);
    }

    // Multiple Comments
    public function test_post_can_have_multiple_comments()
    {
        $post = Post::factory()->create();
        $users = User::factory(3)->create(['email_verified_at' => now()]);

        foreach ($users as $user) {
            $this->actingAs($user)->post("/posts/{$post->id}/comments", [
                'content' => "Comment from {$user->name}",
            ]);
        }

        $this->assertEquals(3, $post->refresh()->comments->count());
    }

    // Comment Content Validation
    public function test_comment_with_valid_content_accepted()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'This is a valid comment with enough content.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a valid comment with enough content.',
        ]);
    }
}
