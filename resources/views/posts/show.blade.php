@extends('layouts.public')

@section('title', $post->seoTitle())
@section('description', $post->seoDescription())
@section('canonical', $post->canonical_url ?: route('posts.show', $post))
@section('og_type', 'article')
@section('og_title', $post->og_title ?: $post->seoTitle())
@section('og_description', $post->og_description ?: $post->seoDescription())
@section('og_image', $post->publicImage())

@push('schema')
<script type="application/ld+json">{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BlogPosting',
  'headline' => $post->title,
  'description' => $post->seoDescription(),
  'image' => [$post->publicImage()],
  'datePublished' => $post->published_at?->toIso8601String(),
  'dateModified' => $post->updated_at?->toIso8601String(),
  'mainEntityOfPage' => $post->canonical_url ?: route('posts.show', $post),
  'author' => ['@type' => 'Organization', 'name' => 'Ungu Spa'],
  'publisher' => ['@type' => 'Organization', 'name' => 'Ungu Spa', 'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/ganbar/logo-ungu-spa-transparent.png')]],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<article class="article-detail">
  <header class="article-detail-head">
    <a href="{{ route('posts.index') }}">Artikel Ungu Spa</a>
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->excerpt ?: $post->seoDescription() }}</p>
    <div><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time><span>&bull;</span><span>Ungu Spa</span></div>
  </header>
  <img class="article-detail-image" src="{{ $post->publicImage() }}" alt="{{ $post->image_alt ?: \Illuminate\Support\Str::slug($post->title).'-gambar' }}">
  <div class="article-content">{!! $post->content !!}</div>
</article>
@if($relatedPosts->count())
<section class="related-posts" aria-labelledby="related-title">
  <div class="article-list-heading"><div><p>Bacaan Lainnya</p><h2 id="related-title">Artikel terbaru dari Ungu Spa</h2></div><a href="{{ route('posts.index') }}">Lihat semua &rarr;</a></div>
  <div class="public-post-grid">@foreach($relatedPosts as $related) @include('posts._card', ['post' => $related]) @endforeach</div>
</section>
@endif
@endsection
