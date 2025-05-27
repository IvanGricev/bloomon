@extends('main')
@section('content')
<div class="container my-5">
    <h1 class="mb-4">Редактировать пост</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Основной текст</label>
            <textarea class="form-control" id="body" name="body" rows="8">{{ old('body', $post->body) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Изображение</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if($post->image)
                <div class="mt-2">
                    <img src="/uploads/blog/{{ $post->image }}" alt="Текущее изображение" style="max-width: 200px;">
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary ms-2">Отмена</a>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let editorInstance;
    ClassicEditor
        .create(document.querySelector('#body'), {
            language: 'ru'
        })
        .then(editor => {
            editorInstance = editor;
            document.querySelector('form').addEventListener('submit', function() {
                document.querySelector('#body').value = editorInstance.getData();
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection 