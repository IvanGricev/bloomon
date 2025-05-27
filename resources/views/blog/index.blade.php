@extends('main')
<link rel="stylesheet" href="/css/blog.css">
@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Блог</h1>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('blog.create') }}" class=" btn-success">Добавить пост</a>
            @endif
        @endauth
    </div>
    <div class="blog-masonry">
        @foreach($posts as $post)
            <div class="blog-card">
                <div class="blog-card-inner">
                    @if($post->image)
                        <img src="/uploads/blog/{{ $post->image }}" class="blog-card-img" alt="{{ $post->title }}">
                    @endif
                    <div class="blog-card-body">
                        <h5 class="blog-card-title">{{ $post->title }}</h5>
                        <div class="blog-card-text blog-body" id="blog-body-{{ $post->id }}" style="max-height: 300px; overflow: hidden; position: relative;">
                            {!! $post->body !!}
                        </div>
                        @if(strlen(strip_tags($post->body)) > 800)
                            <button class="blog-card-more show-more-btn" data-post-id="{{ $post->id }}">Читать далее</button>
                        @endif
                    </div>
                    <div class="blog-card-footer">
                        <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('blog.edit', $post->id) }}" class="blog-card-edit">Редактировать</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.show-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var postId = this.getAttribute('data-post-id');
                var body = document.getElementById('blog-body-' + postId);
                if (body.style.maxHeight === 'none') {
                    body.style.maxHeight = '300px';
                    this.textContent = 'Читать далее';
                } else {
                    body.style.maxHeight = 'none';
                    this.textContent = 'Скрыть';
                }
            });
        });
    });
</script>
@endsection 