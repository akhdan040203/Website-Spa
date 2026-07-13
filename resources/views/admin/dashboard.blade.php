@extends('admin.layouts.app')
@section('title','Dashboard | CMS Ungu Spa')
@section('content')
<div class="cms-page-head"><div><p>Dashboard</p><h1>Selamat datang, {{ auth()->user()->name }}</h1><span>Kelola artikel dan optimasi SEO Ungu Spa dari satu tempat.</span></div><a href="{{ route('admin.posts.create') }}">+ Artikel Baru</a></div>
<div class="grid gap-5 md:grid-cols-3">@foreach([['Artikel',$stats['total']],['Published',$stats['published']],['Draft',$stats['draft']]] as [$label,$value])<article class="cms-panel"><span class="text-sm text-slate-500">{{ $label }}</span><strong class="mt-2 block text-4xl text-[#26003f]">{{ $value }}</strong></article>@endforeach</div>
<div class="cms-panel mt-5"><h2>Artikel Terbaru</h2>@forelse($latestPosts as $post)<div class="flex items-center justify-between gap-4 border-t border-slate-100 py-3"><div><strong class="block text-sm">{{ $post->title }}</strong><small class="text-slate-400">{{ ucfirst($post->status) }} · {{ $post->updated_at->diffForHumans() }}</small></div><a class="text-sm font-bold text-[#6b21a8]" href="{{ route('admin.posts.edit',$post) }}">Edit</a></div>@empty<p class="text-sm text-slate-500">Belum ada artikel.</p>@endforelse</div>
@endsection
