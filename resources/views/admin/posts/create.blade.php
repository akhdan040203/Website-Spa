@extends('admin.layouts.app')
@section('title','Artikel Baru | CMS Ungu Spa')
@section('content')<div class="cms-page-head"><div><p>Artikel</p><h1>Buat Artikel</h1><span>Tulis konten dan lengkapi pengaturan SEO.</span></div></div><form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">@csrf @include('admin.posts.form')</form>@endsection
