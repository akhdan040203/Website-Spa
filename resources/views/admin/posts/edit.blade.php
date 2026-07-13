@extends('admin.layouts.app')
@section('title','Edit Artikel | CMS Ungu Spa')
@section('content')<div class="cms-page-head"><div><p>Artikel</p><h1>Edit Artikel</h1><span>Perbarui konten dan pengaturan SEO.</span></div></div><form method="POST" action="{{ route('admin.posts.update',$post) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.posts.form')</form>@endsection
