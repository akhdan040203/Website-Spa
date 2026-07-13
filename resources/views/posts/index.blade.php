@extends('layouts.public')

@section('title', 'Artikel Spa dan Perawatan Tubuh | Ungu Spa')
@section('description', 'Temukan artikel massage, spa, relaksasi, dan panduan perawatan tubuh dari Ungu Spa.')
@section('canonical', route('posts.index'))
@section('og_title', 'Artikel Spa dan Perawatan Tubuh | Ungu Spa')
@section('og_description', 'Panduan massage, spa, relaksasi, dan perawatan tubuh dari Ungu Spa.')

@push('schema')
<script type="application/ld+json">{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'CollectionPage',
  'name' => 'Artikel Ungu Spa',
  'url' => route('posts.index'),
  'description' => 'Artikel massage, spa, relaksasi, dan perawatan tubuh dari Ungu Spa.',
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<section class="article-list-hero">
  <p>Wawasan &amp; Perawatan</p>
  <h1>Artikel Ungu Spa</h1>
  <span>Informasi terpercaya untuk membantu tubuh tetap rileks, segar, dan terawat.</span>
</section>
<section class="article-list-section" aria-labelledby="all-posts-title">
  <div class="article-list-heading">
    <div><p>Artikel Terbaru</p><h2 id="all-posts-title">Temukan informasi yang Anda butuhkan</h2></div>
    <span>{{ $posts->total() }} artikel tersedia</span>
  </div>
  @if($posts->count())
    <div class="public-post-grid">
      @foreach($posts as $post) @include('posts._card', ['post' => $post]) @endforeach
    </div>
    <nav class="article-pagination" aria-label="Navigasi halaman artikel">
      @if($posts->onFirstPage()) <span class="is-disabled">&larr; Sebelumnya</span> @else <a href="{{ $posts->previousPageUrl() }}" rel="prev">&larr; Sebelumnya</a> @endif
      <span>Halaman {{ $posts->currentPage() }} dari {{ $posts->lastPage() }}</span>
      @if($posts->hasMorePages()) <a href="{{ $posts->nextPageUrl() }}" rel="next">Selanjutnya &rarr;</a> @else <span class="is-disabled">Selanjutnya &rarr;</span> @endif
    </nav>
  @else
    <div class="article-empty"><h2>Artikel segera hadir</h2><p>Artikel yang sudah diterbitkan akan tampil otomatis di halaman ini.</p></div>
  @endif
</section>
@endsection
