<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('admin.posts.index', ['posts' => Post::latest()->paginate(12)]);
    }

    public function create(): View { return view('admin.posts.create', ['post' => new Post]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['image_alt'] = Str::slug($data['title']).'-gambar';
        $data['featured_image'] = $request->file('featured_image')?->store('posts', 'public');
        $data['published_at'] = $data['status'] === 'published' ? (($data['published_at'] ?? null) ?: now()) : null;
        $data['robots_index'] = true;
        $data['robots_follow'] = true;
        $data['canonical_url'] = null;
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Post $post): View { return view('admin.posts.edit', compact('post')); }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request, $post);
        $data['slug'] = $this->uniqueSlug($data['title'], $post);
        $data['image_alt'] = Str::slug($data['title']).'-gambar';
        if ($request->hasFile('featured_image')) { Storage::disk('public')->delete($post->featured_image); $data['featured_image'] = $request->file('featured_image')->store('posts', 'public'); }
        $data['published_at'] = $data['status'] === 'published' ? (($data['published_at'] ?? null) ?: $post->published_at ?: now()) : null;
        $data['robots_index'] = true;
        $data['robots_follow'] = true;
        $data['canonical_url'] = null;
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter([$post->featured_image, $post->og_image]));
        $post->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:1000'],
            'content' => ['required','string'],
            'featured_image' => ['nullable','image','max:4096'],
            'status' => ['required',Rule::in(['draft','published'])],
            'published_at' => ['nullable','date'],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:320'],
            'focus_keyword' => ['nullable','string','max:255'],
        ]);
    }

    private function uniqueSlug(string $value, ?Post $ignore = null): string
    {
        $base = Str::slug($value) ?: 'artikel'; $slug = $base; $i = 2;
        while (Post::where('slug', $slug)->when($ignore, fn ($q) => $q->whereKeyNot($ignore->id))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
