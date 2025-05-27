@extends('main')
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                @if($post->image)
                    <img src="/uploads/blog/{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body">
                    <h1 class="card-title mb-3">{{ $post->title }}</h1>
                    <div class="mb-3 text-muted small">{{ $post->created_at->format('d.m.Y H:i') }}</div>
                    <div class="card-text" style="font-size:1.15rem;line-height:1.7">{!! $post->body !!}</div>
                </div>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">← Ко всем постам</a>
        </div>
    </div>
</div>
@endsection 