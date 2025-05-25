@extends('main')

<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">

@section('content')
<div class="container my-5">
    <h1>Оформление заказа</h1>
    
    <!-- Existing cart items section remains the same -->
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Адрес доставки</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Контактный телефон</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        
        <!-- Updated delivery date and time section -->
        <div class="mb-3">
        <label for="delivery_date" class="form-label">Дата доставки</label>
        <input type="date" name="delivery_date" id="delivery_date" class="form-control">
        </div>

        <!-- Add this new time slot section -->
        <div class="mb-3">
            <label for="delivery_time_slot" class="form-label">Время доставки</label>
            <select name="delivery_time_slot" id="delivery_time_slot" class="form-control" required disabled>
                <option value="">Сначала выберите дату доставки</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="delivery_preferences" class="form-label">Пожелания к доставке</label>
            <textarea name="delivery_preferences" id="delivery_preferences" class="form-control" rows="2" 
                    placeholder="Например: позвонить за час до доставки, оставить у консьержа и т.д."></textarea>
        </div>

        <!-- Existing payment method section -->
        <div class="mb-3">
            <label class="form-label">Способ оплаты</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="cash" id="cash" checked>
                <label class="form-check-label" for="cash">
                    Наличными при получении
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
                <label class="form-check-label" for="card">
                    Оплата картой онлайн
                </label>
            </div>
        </div>

        <input type="hidden" name="total_price" value="{{ $totalDiscounted }}">
        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
    
    <!-- Existing payment note -->
    <p class="mt-3 text-muted">
        Если вы выбрали оплату картой, после оформления заказа вы будете перенаправлены на страницу для ввода данных карты.
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('delivery_date');
    const timeSlotSelect = document.getElementById('delivery_time_slot');
    
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    dateInput.min = tomorrow.toISOString().split('T')[0];

    dateInput.addEventListener('change', async function() {
        timeSlotSelect.disabled = true;
        
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
                option.textContent = `${slot.slot}`;
                timeSlotSelect.appendChild(option);
            });
            timeSlotSelect.disabled = false;
        } catch (error) {
            console.error('Error fetching time slots:', error);
            timeSlotSelect.innerHTML = '<option value="">Ошибка загрузки временных слотов</option>';
            timeSlotSelect.disabled = true;
        }
    });
});
</script>
@endsection