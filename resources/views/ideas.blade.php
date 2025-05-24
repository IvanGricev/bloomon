<link rel="stylesheet" href="{{ url('css/ideas.css') }}">
@extends('main')

@section('content')
<div class="hero-ideas">
    <div class="hero-ideas-text">
        <div class="hero-ideas-title">ЦВЕТЫ</div>
        <div class="hero-ideas-desc">
            — это живое чудо, и при правильном подходе они будут радовать вас дольше. Делимся советами и вдохновением для создания уникальных композиций!
        </div>
        <ul class="hero-ideas-list">
            <li>
                @include('partials.flowicon')
                Обрежьте стебли под углом — так цветы лучше впитывают влагу.
            </li>
            <li>
                @include('partials.flowicon')
                Меняйте воду каждый день и тщательно мойте вазу.
            </li>
            <li>
                @include('partials.flowicon')
                Не ставьте цветы рядом с фруктами — этилен ускоряет увядание.
            </li>
            <li>
                @include('partials.flowicon')
                Удаляйте увядшие листья и бутоны — это сохранит свежесть букета.
            </li>
            <li>
                @include('partials.flowicon')
                Берегите от прямых солнечных лучей и сквозняков.
            </li>
        </ul>
        <div class="hero-ideas-note">
            А если сомневаетесь — просто загляните к нам. Мы с радостью подскажем, как ухаживать именно за вашими цветами!
        </div>
    </div>
    <div class="hero-ideas-image">
        <img src="/images/deliveryy.png" alt="Цветы и уход">
    </div>
</div>

<div class="compositions-section">
    <div class="compositions-title">Как рождаются</div>
    <div class="compositions-subtitle">К<span style="letter-spacing:0.04em;">О</span>МПОЗИЦИИ</div>
    <div class="compositions-desc">
        Каждый наш букет — это настроение, история и тщательно подобранные детали. Мы рассказываем, чем вдохновлялись, какие цветы использовали и какие эмоции хотели передать.<br>
        <strong>"Утренний сад"</strong> — это воспоминание о лете,<br>
        <strong>"Лунная пыль"</strong> — о прогулке под звёздами.
    </div>
    <div class="compositions-grid">
        <div class="composition-card">
            <img src="/images/composition1.jpg" alt="Рулоны упаковки">
        </div>
        <div class="composition-card">
            <img src="/images/composition2.jpg" alt="Флорист за работой">
        </div>
        <div class="composition-card">
            <img src="/images/composition3.png" alt="Цветы на столе">
        </div>
    </div>
</div>

<div class="flowers-section">
    <div class="flowers-title">ЦВЕТЫ</div>
    <div class="flowers-subtitle">говорят больше слов</div>
    <div class="flowers-block">
        <div class="flowers-img">
            <img src="/images/flower1.jpg" alt="Лилия">
        </div>
        <div class="flowers-content">
            <h3>ЛЮБОВЬ И ПРИЗНАНИЯ</h3>
            <h4>Лучше тысячи слов</h4>
            <ul class="flowers-list">
                <li>@include('partials.flowicon') Розы — классика, но не единственный вариант. <br>Красные — страсть, розовые — нежность, белые — искренность.</li>
                <li>@include('partials.flowicon') Для свежего романтического начала подойдут пионы, ранункулюсы или лилии.</li>
                <li>@include('partials.flowicon') А если хотите сказать: "Я не могу перестать о тебе думать" — подарите нежный микс в пастельных тонах с добавлением эвкалипта или лаванды.</li>
            </ul>
        </div>
    </div>
    <div class="flowers-block reverse">
        <div class="flowers-img">
            <img src="/images/flower2.jpg" alt="Альстромерия">
        </div>
        <div class="flowers-content">
            <h3>ДЕНЬ МАТЕРИ И БЛАГОДАРНОСТЬ</h3>
            <h4>Тёплые чувства в каждом лепестке</h4>
            <ul class="flowers-list">
                <li>@include('partials.flowicon') Хризантемы, герберы, альстромерии и нежные розы — идеальный способ сказать «Спасибо» или «Я тебя ценю».</li>
                <li>@include('partials.flowicon') Добавьте открытку с тёплыми словами — и букет станет ещё более личным подарком.</li>
            </ul>
        </div>
    </div>
    <div class="flowers-block">
        <div class="flowers-img">
            <img src="/images/flower3.jpg" alt="Каллы">
        </div>
        <div class="flowers-content">
            <h3>ПРАЗДНИКИ И ТОРЖЕСТВА</h3>
            <h4>Ярко и празднично</h4>
            <ul class="flowers-list">
                <li>@include('partials.flowicon') На день рождения, юбилей или выпускной подойдут эффектные, жизнерадостные композиции: экзотические цветы, яркие оттенки, нестандартная упаковка.</li>
                <li>@include('partials.flowicon') Здесь уместны сочетания с декоративной зеленью, ягодами, хлопком или даже сухоцветами.</li>
            </ul>
        </div>
    </div>
    <div class="flowers-block reverse">
        <div class="flowers-img">
            <img src="/images/flower4.jpg" alt="Хризантема">
        </div>
        <div class="flowers-content">
            <h3>БЕЗ ПОВОДА</h3>
            <h4>Просто потому что хочется порадовать</h4>
            <ul class="flowers-list">
                <li>@include('partials.flowicon') Цветы не обязательно должны быть к событию. Подарите букет «просто так» — чтобы порадовать близкого или себя.</li>
                <li>@include('partials.flowicon') Лёгкие композиции в корзинках, в мини-боксах или крафтовой бумаге всегда вызывают улыбку.</li>
            </ul>
        </div>
    </div>
</div>

@endsection