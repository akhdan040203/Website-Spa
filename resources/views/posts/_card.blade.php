<article class="public-post-card">
  <a class="public-post-image" href="{{ route('posts.show', $post) }}" tabindex="-1" aria-hidden="true">
    <img src="{{ $post->publicImage() }}" alt="{{ $post->image_alt ?: \Illuminate\Support\Str::slug($post->title).'-gambar' }}" loading="lazy">
  </a>
  <div class="public-post-card-body">
    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>
    <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
    <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 125) }}</p>
    <a class="public-post-more" href="{{ route('posts.show', $post) }}">Baca artikel <span aria-hidden="true">&rarr;</span></a>
  </div>
</article>
