@extends('main')

    <link rel="stylesheet" href="{{ asset('css/support-create.css') }}">

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Обратиться в поддержку</h2>
                </div>
                <div class="card-body">
                    <x-error-alert />
                    <x-success-message />

                    <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Тема</label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   required>
                            <x-error-message field="subject" />
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Сообщение</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="5" 
                                      required>{{ old('message') }}</textarea>
                            <x-error-message field="message" />
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label">Вложения (опционально)</label>
                            <input type="file" 
                                   class="form-control @error('attachments.*') is-invalid @enderror" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple 
                                   accept="image/*">
                            <div class="form-text">Можно загрузить несколько изображений. Максимальный размер каждого файла - 5MB.</div>
                            <x-error-message field="attachments" />
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('support.index') }}" class=" btn-secondary">Назад</a>
                            <button type="submit" class=" btn-primary">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 