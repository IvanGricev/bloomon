@extends('main')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление товарами</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Добавить товар</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Категория</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('uploads/products/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/50" alt="No image">
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 2, ',', ' ') }} руб.</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" 
                                           class="form-control quantity-input" 
                                           value="{{ $product->quantity }}" 
                                           min="0" 
                                           data-product-id="{{ $product->id }}"
                                           style="text-align: center;">
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            type="button"
                                            onclick="updateQuantity({{ $product->id }}, 'set')">
                                        ✓
                                    </button>
                                </div>
                                <button class="btn btn-success btn-sm ms-2" 
                                        type="button"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#addQuantityModal{{ $product->id }}">
                                    +
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">
                                {{ trans_choice('единица|единицы|единиц', $product->quantity) }}
                            </small>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn btn-sm btn-primary">Редактировать</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Удалить товар?');"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Модальное окно для пополнения склада -->
                    <div class="modal fade" id="addQuantityModal{{ $product->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Пополнить склад: {{ $product->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Добавить количество</label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="addQuantity{{ $product->id }}" 
                                                   min="1" 
                                                   value="1">
                                            <span class="input-group-text">{{ trans_choice('единица|единицы|единиц', 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                    <button type="button" 
                                            class="btn btn-primary" 
                                            onclick="updateQuantity({{ $product->id }}, 'add')">
                                        Добавить
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Функция для обновления количества товара
    function updateQuantity(productId, action) {
        let quantity;
        if (action === 'add') {
            quantity = document.getElementById('addQuantity' + productId).value;
        } else {
            quantity = document.querySelector(`.quantity-input[data-product-id="${productId}"]`).value;
        }

        fetch(`/admin/products/${productId}/quantity`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                quantity: parseInt(quantity),
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновляем отображение количества
                const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
                input.value = data.new_quantity;
                
                // Обновляем текст с единицами измерения
                const unitsText = input.closest('td').querySelector('small');
                unitsText.textContent = getUnitsText(data.new_quantity);
                
                // Закрываем модальное окно, если оно открыто
                const modal = document.getElementById('addQuantityModal' + productId);
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
                
                // Показываем уведомление об успехе
                showNotification('success', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Произошла ошибка при обновлении количества');
        });
    }

    // Функция для получения правильного склонения слова "единица"
    function getUnitsText(quantity) {
        const cases = [2, 0, 1, 1, 1, 2];
        const titles = ['единица', 'единицы', 'единиц'];
        const index = (quantity % 100 > 4 && quantity % 100 < 20) ? 2 : cases[(quantity % 10 < 5) ? quantity % 10 : 5];
        return titles[index];
    }

    // Функция для показа уведомлений
    function showNotification(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '1050';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    // Обработчик Enter для полей ввода количества
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const productId = this.dataset.productId;
                updateQuantity(productId, 'set');
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .quantity-input {
        width: 70px !important;
    }
    
    .input-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .modal-dialog {
        max-width: 400px;
    }
</style>
@endpush
@endsection