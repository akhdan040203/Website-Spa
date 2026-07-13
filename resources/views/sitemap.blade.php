{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ route('home') }}</loc><lastmod>{{ now()->toDateString() }}</lastmod><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc>{{ route('posts.index') }}</loc><lastmod>{{ $posts->max('updated_at')?->toDateString() ?: now()->toDateString() }}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
@foreach($posts as $post)
  <url><loc>{{ route('posts.show', $post) }}</loc><lastmod>{{ $post->updated_at->toDateString() }}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
@endforeach
</urlset>
