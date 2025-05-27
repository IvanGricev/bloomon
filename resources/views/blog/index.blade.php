@extends('main')
@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Блог</h1>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('blog.create') }}" class="btn btn-success">Добавить пост</a>
            @endif
        @endauth
    </div>
    <div class="row">
        @foreach($posts as $post)
            <div class="col-12 mb-4">
                <div class="card h-100">
                    @if($post->image)
                        <img src="/uploads/blog/{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <div class="card-text blog-body" id="blog-body-{{ $post->id }}" style="max-height: 300px; overflow: hidden; position: relative;">
                            {!! $post->body !!}
                        </div>
                        @if(strlen(strip_tags($post->body)) > 800)
                            <button class="btn btn-link p-0 mt-2 show-more-btn" data-post-id="{{ $post->id }}">Читать далее</button>
                        @endif
                    </div>
                    <div class="card-footer text-muted small d-flex justify-content-between align-items-center">
                        <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-sm btn-outline-secondary ms-2">Редактировать</a>
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