<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_post_appears_on_home_list_and_detail_with_dynamic_seo(): void
    {
        $post = $this->createPost(['title' => 'Manfaat Spa untuk Relaksasi', 'slug' => 'manfaat-spa-relaksasi']);

        $this->get('/')->assertOk()->assertSee($post->title);
        $this->get('/artikel')->assertOk()->assertSee($post->title);
        $this->get('/artikel/'.$post->slug)
            ->assertOk()
            ->assertSee('<title>Manfaat Spa untuk Relaksasi | Ungu Spa</title>', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('BlogPosting');
    }

    public function test_homepage_article_cards_have_pagination_after_testimonials(): void
    {
        foreach (range(1, 4) as $number) {
            $this->createPost(['title' => 'Homepage Artikel '.$number, 'slug' => 'homepage-artikel-'.$number]);
        }

        $response = $this->get('/');
        $response->assertOk()->assertSee('Halaman 1 dari 2')->assertSee('artikel_page=2');
        $this->assertLessThan(
            strpos($response->getContent(), 'id="artikel"'),
            strpos($response->getContent(), 'id="testimoni"')
        );
    }

    public function test_draft_and_future_posts_are_not_public_or_in_sitemap(): void
    {
        $draft = $this->createPost(['title' => 'Masih Draft', 'slug' => 'masih-draft', 'status' => 'draft', 'published_at' => null]);
        $future = $this->createPost(['title' => 'Artikel Masa Depan', 'slug' => 'artikel-masa-depan', 'published_at' => now()->addDay()]);

        $this->get('/artikel')->assertDontSee($draft->title)->assertDontSee($future->title);
        $this->get('/artikel/'.$draft->slug)->assertNotFound();
        $this->get('/artikel/'.$future->slug)->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee($draft->slug)->assertDontSee($future->slug);
    }

    public function test_sitemap_automatically_contains_published_posts(): void
    {
        $post = $this->createPost(['title' => 'Panduan Massage', 'slug' => 'panduan-massage']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/artikel/panduan-massage');
    }

    public function test_article_list_is_paginated(): void
    {
        foreach (range(1, 10) as $number) {
            $this->createPost(['title' => 'Artikel '.$number, 'slug' => 'artikel-'.$number]);
        }

        $this->get('/artikel')->assertOk()->assertSee('Halaman 1 dari 2');
        $this->get('/artikel?page=2')->assertOk()->assertSee('Halaman 2 dari 2');
    }

    private function createPost(array $attributes = []): Post
    {
        return Post::create(array_merge([
            'user_id' => User::factory()->create()->id,
            'title' => 'Artikel Ungu Spa',
            'slug' => 'artikel-ungu-spa',
            'content' => '<h2>Isi artikel</h2><p>Konten artikel untuk pengunjung.</p>',
            'excerpt' => 'Ringkasan artikel untuk pengunjung.',
            'status' => 'published',
            'published_at' => now(),
            'robots_index' => true,
            'robots_follow' => true,
        ], $attributes));
    }
}
