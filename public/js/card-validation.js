document.addEventListener('DOMContentLoaded', function() {
    const cardNumberInput = document.getElementById('card_number');
    const expiryDateInput = document.getElementById('expiry_date');
    const cvvInput = document.getElementById('cvv');

    // Форматирование номера карты (добавление пробелов)
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        let formattedValue = '';
        
        for(let i = 0; i < value.length; i++) {
            if(i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });

    // Валидация номера карты (алгоритм Луна)
    function validateCardNumber(number) {
        number = number.replace(/\s/g, '');
        if (!/^\d{16}$/.test(number)) return false;

        let sum = 0;
        let isEven = false;

        for (let i = number.length - 1; i >= 0; i--) {
            let digit = parseInt(number[i]);

            if (isEven) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }

            sum += digit;
            isEven = !isEven;
        }

        return sum % 10 === 0;
    }

    // Валидация срока действия карты
    function validateExpiryDate(date) {
        if (!/^\d{2}\/\d{2}$/.test(date)) return false;

        const [month, year] = date.split('/');
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear() % 100;
        const currentMonth = currentDate.getMonth() + 1;

        const expMonth = parseInt(month);
        const expYear = parseInt(year);

        if (expMonth < 1 || expMonth > 12) return false;
        if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) return false;

        return true;
    }

    // Обработка ввода срока действия
    expiryDateInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        e.target.value = value;
    });

    // Валидация CVV
    function validateCVV(cvv) {
        return /^\d{3}$/.test(cvv);
    }

    // Обработка отправки формы
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const cardNumber = cardNumberInput.value.replace(/\s/g, '');
        const expiryDate = expiryDateInput.value;
        const cvv = cvvInput.value;

        let isValid = true;
        let errorMessage = '';

        if (!validateCardNumber(cardNumber)) {
            isValid = false;
            errorMessage = 'Неверный номер карты';
            cardNumberInput.classList.add('is-invalid');
        } else {
            cardNumberInput.classList.remove('is-invalid');
        }

        if (!validateExpiryDate(expiryDate)) {
            isValid = false;
            errorMessage = 'Неверный срок действия карты';
            expiryDateInput.classList.add('is-invalid');
        } else {
            expiryDateInput.classList.remove('is-invalid');
        }

        if (!validateCVV(cvv)) {
            isValid = false;
            errorMessage = 'Неверный CVV код';
            cvvInput.classList.add('is-invalid');
        } else {
            cvvInput.classList.remove('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });
}); 