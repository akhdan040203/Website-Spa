<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PublicPostController extends Controller
{
    public function index(): View
    {
        return view('posts.index', [
            'posts' => Post::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->status === 'published' && $post->published_at?->lte(now()),
            404
        );

        return view('posts.show', [
            'post' => $post,
            'relatedPosts' => Post::published()
                ->whereKeyNot($post->getKey())
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
