@extends('main')

<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
<link rel="stylesheet" href="{{ asset('css/address-autocomplete.css') }}">
<script src="{{ asset('js/address-autocomplete.js') }}" defer></script>

@section('content')
<div class="container my-5">
    <h1>Оформление заказа</h1>
    
    <x-error-alert />
    <x-success-message />
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="mb-3 form-group">
            <label for="address" class="form-label">Адрес доставки</label>
            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                   value="{{ old('address') }}" placeholder="Начните вводить город или улицу" required>
            <x-error-message field="address" />
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Контактный телефон</label>
            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                   value="{{ old('phone') }}" required>
            <x-error-message field="phone" />
        </div>
        
        <div class="mb-3">
            <label for="delivery_date" class="form-label">Дата доставки</label>
            <input type="date" name="delivery_date" id="delivery_date" 
                   class="form-control @error('delivery_date') is-invalid @enderror" 
                   value="{{ old('delivery_date') }}" required>
            <x-error-message field="delivery_date" />
        </div>

        <div class="mb-3">
            <label for="delivery_time_slot" class="form-label">Время доставки</label>
            <select name="delivery_time_slot" id="delivery_time_slot" 
                    class="form-control @error('delivery_time_slot') is-invalid @enderror" required disabled>
                <option value="">Сначала выберите дату доставки</option>
            </select>
            <x-error-message field="delivery_time_slot" />
        </div>

        <div class="mb-3">
            <label for="delivery_preferences" class="form-label">Пожелания к доставке</label>
            <textarea name="delivery_preferences" id="delivery_preferences" 
                      class="form-control @error('delivery_preferences') is-invalid @enderror" 
                      rows="2" placeholder="Например: позвонить за час до доставки, оставить у консьержа и т.д."
            >{{ old('delivery_preferences') }}</textarea>
            <x-error-message field="delivery_preferences" />
        </div>

        <div class="mb-3">
            <label class="form-label">Способ оплаты</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="cash" id="cash" 
                       {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }}>
                <label class="form-check-label" for="cash">
                    Наличными при получении
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="card" id="card"
                       {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                <label class="form-check-label" for="card">
                    Оплата картой онлайн
                </label>
            </div>
            <x-error-message field="payment_method" />
        </div>

        <input type="hidden" name="total_price" value="{{ $totalDiscounted }}">
        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
    
    <p class="mt-3 text-muted">
        Если вы выбрали оплату картой, после оформления заказа вы будете перенаправлены на страницу для ввода данных карты.
    </p>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('delivery_date');
    const timeSlotSelect = document.getElementById('delivery_time_slot');
    const form = document.getElementById('checkoutForm');
    
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    dateInput.min = tomorrow.toISOString().split('T')[0];

    // Set maximum date to 2 weeks from now
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 14);
    dateInput.max = maxDate.toISOString().split('T')[0];

    dateInput.addEventListener('change', async function() {
        timeSlotSelect.disabled = true;
        timeSlotSelect.innerHTML = '<option value="">Загрузка доступных слотов...</option>';
        
        if (!this.value) {
            timeSlotSelect.innerHTML = '<option value="">Сначала выберите дату доставки</option>';
            return;
        }

        try {
            const response = await fetch(`/delivery-time-slots?date=${this.value}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            
            timeSlotSelect.innerHTML = '';
            
            if (!data.slots || data.slots.length === 0) {
                timeSlotSelect.innerHTML = '<option value="">Нет доступных слотов на эту дату</option>';
                timeSlotSelect.disabled = true;
                return;
            }

            timeSlotSelect.innerHTML = '<option value="">Выберите время доставки</option>';
            data.slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot.slot;
                option.textContent = slot.slot;
                timeSlotSelect.appendChild(option);
            });
            timeSlotSelect.disabled = false;
        } catch (error) {
            console.error('Error fetching time slots:', error);
            timeSlotSelect.innerHTML = '<option value="">Ошибка загрузки временных слотов</option>';
            timeSlotSelect.disabled = true;
        }
    });

    // Валидация формы перед отправкой
    form.addEventListener('submit', function(e) {
        if (!dateInput.value) {
            e.preventDefault();
            alert('Пожалуйста, выберите дату доставки');
            return;
        }

        if (!timeSlotSelect.value) {
            e.preventDefault();
            alert('Пожалуйста, выберите время доставки');
            return;
        }
    });
});
</script>
@endpush
@endsection