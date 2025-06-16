document.addEventListener('DOMContentLoaded', function() {
    const addressInput = document.getElementById('address');
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'address-suggestions';
    addressInput.parentNode.appendChild(suggestionsContainer);

    let timeoutId;
    let selectedIndex = -1;

    // Список городов Беларуси
    const belarusCities = [
        'Минск', 'Гомель', 'Могилев', 'Витебск', 'Гродно', 'Брест', 'Барановичи', 
        'Борисов', 'Пинск', 'Орша', 'Мозырь', 'Солигорск', 'Новополоцк', 'Лида', 
        'Молодечно', 'Полоцк', 'Жлобин', 'Светлогорск', 'Речица', 'Жодино'
    ];

    // Список улиц для Минска (можно расширить для других городов)
    const minksStreets = [
        'проспект Независимости', 'улица Ленина', 'проспект Победителей', 
        'улица Немига', 'проспект Дзержинского', 'улица Калиновского',
        'проспект Машерова', 'улица Сурганова', 'проспект Пушкина',
        'улица Толстого', 'проспект Рокоссовского', 'улица Богдановича'
    ];

    function createSuggestionElement(text) {
        const div = document.createElement('div');
        div.className = 'suggestion-item';
        div.textContent = text;
        return div;
    }

    function showSuggestions(input) {
        const value = input.toLowerCase();
        let suggestions = [];

        // Если ввод начинается с названия города
        const cityMatch = belarusCities.find(city => 
            city.toLowerCase().startsWith(value)
        );

        if (cityMatch) {
            suggestions.push(cityMatch);
            // Если это Минск, добавляем улицы
            if (cityMatch.toLowerCase() === 'минск') {
                suggestions = suggestions.concat(
                    minksStreets.map(street => `${cityMatch}, ${street}`)
                );
            }
        } else {
            // Если ввод не совпадает с началом названия города,
            // проверяем, содержит ли он название города
            const cityInInput = belarusCities.find(city => 
                input.toLowerCase().includes(city.toLowerCase())
            );

            if (cityInInput && cityInInput.toLowerCase() === 'минск') {
                // Фильтруем улицы Минска по вводу
                const streetInput = input.toLowerCase().replace('минск', '').trim();
                suggestions = minksStreets
                    .filter(street => street.toLowerCase().includes(streetInput))
                    .map(street => `${cityInInput}, ${street}`);
            }
        }

        // Очищаем предыдущие предложения
        suggestionsContainer.innerHTML = '';
        selectedIndex = -1;

        // Добавляем новые предложения
        suggestions.slice(0, 5).forEach(suggestion => {
            const element = createSuggestionElement(suggestion);
            element.addEventListener('click', () => {
                addressInput.value = suggestion;
                suggestionsContainer.innerHTML = '';
            });
            element.addEventListener('mouseover', () => {
                const items = suggestionsContainer.getElementsByClassName('suggestion-item');
                Array.from(items).forEach(item => item.classList.remove('selected'));
                element.classList.add('selected');
            });
            suggestionsContainer.appendChild(element);
        });

        // Показываем или скрываем контейнер с предложениями
        suggestionsContainer.style.display = suggestions.length > 0 ? 'block' : 'none';
    }

    // Обработка ввода
    addressInput.addEventListener('input', function(e) {
        clearTimeout(timeoutId);
        const value = e.target.value.trim();

        if (value.length >= 2) {
            timeoutId = setTimeout(() => showSuggestions(value), 300);
        } else {
            suggestionsContainer.style.display = 'none';
        }
    });

    // Обработка клавиатуры
    addressInput.addEventListener('keydown', function(e) {
        const items = suggestionsContainer.getElementsByClassName('suggestion-item');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                break;
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                break;
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    addressInput.value = items[selectedIndex].textContent;
                    suggestionsContainer.style.display = 'none';
                }
                break;
            case 'Escape':
                suggestionsContainer.style.display = 'none';
                break;
        }

        // Обновляем выделение
        Array.from(items).forEach((item, index) => {
            item.classList.toggle('selected', index === selectedIndex);
        });
    });

    // Скрываем предложения при клике вне поля ввода
    document.addEventListener('click', function(e) {
        if (!addressInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });
}); 