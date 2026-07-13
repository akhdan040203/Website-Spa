@extends('admin.layouts.app')
@section('title','Artikel | CMS Ungu Spa')
@section('content')
<div class="cms-page-head"><div><p>Manajemen Konten</p><h1>Artikel</h1><span>Kelola artikel, publikasi, dan metadata SEO.</span></div><a href="{{ route('admin.posts.create') }}">+ Artikel Baru</a></div>
<div class="cms-table-card"><div class="cms-table-scroll"><table><thead><tr><th>Artikel</th><th>Status</th><th>Publikasi</th><th>SEO</th><th>Aksi</th></tr></thead><tbody>
@forelse($posts as $post)<tr><td><strong>{{ $post->title }}</strong><small>/artikel/{{ $post->slug }}</small></td><td><span class="cms-status cms-status-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td><td>{{ $post->published_at?->format('d M Y H:i') ?? '—' }}</td><td>{{ $post->meta_title && $post->meta_description ? 'Lengkap' : 'Belum lengkap' }}</td><td><div class="cms-actions"><a href="{{ route('admin.posts.edit',$post) }}">Edit</a><form method="POST" action="{{ route('admin.posts.destroy',$post) }}" onsubmit="return confirm('Hapus artikel ini?')">@csrf @method('DELETE')<button type="submit">Hapus</button></form></div></td></tr>@empty<tr><td colspan="5" class="cms-empty">Belum ada artikel. Buat artikel pertama Anda.</td></tr>@endforelse
</tbody></table></div></div>{{ $posts->links() }}
@endsection
