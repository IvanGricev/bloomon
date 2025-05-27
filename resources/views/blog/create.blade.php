@extends('main')
<link rel="stylesheet" href="/css/blog-create.css">
@section('content')
<div class="container my-5">
    <h1 class="mb-4">Добавить пост в блог</h1>
    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Основной текст</label>
            <textarea class="form-control" id="body" name="body" rows="8"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Изображение</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
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