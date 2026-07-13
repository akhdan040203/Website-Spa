<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_post(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/posts', [
            'title' => 'Manfaat Massage', 'slug' => '', 'content' => '<h2>Isi artikel</h2>', 'status' => 'draft',
            'robots_index' => '1', 'robots_follow' => '1',
        ])->assertRedirect('/admin/posts');

        $post = Post::firstOrFail();
        $this->assertSame('manfaat-massage', $post->slug);
        $this->assertSame('manfaat-massage-gambar', $post->image_alt);
        $this->assertTrue($post->robots_index);
        $this->assertTrue($post->robots_follow);
        $this->actingAs($user)->put('/admin/posts/'.$post->slug, [
            'title' => 'Manfaat Massage Tubuh', 'slug' => $post->slug, 'content' => '<h2>Isi baru</h2>', 'status' => 'published',
            'robots_index' => '1', 'robots_follow' => '1',
        ])->assertRedirect('/admin/posts');
        $post = $post->fresh();
        $this->assertSame('published', $post->status);
        $this->assertSame('manfaat-massage-tubuh', $post->slug);
        $this->assertSame('manfaat-massage-tubuh-gambar', $post->image_alt);
        $this->actingAs($user)->delete('/admin/posts/'.$post->slug)->assertRedirect();
        $this->assertDatabaseCount('posts', 0);
    }
}
