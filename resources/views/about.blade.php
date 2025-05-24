@extends('main')

<link rel="stylesheet" href="{{ asset('css/about.css') }}">

@section('content')
<div class="about-hero">
    <div class="about-hero-img">
        <img src="/images/about-hero.jpg" alt="Команда Bloomon">
    </div>
    <div class="about-hero-content">
        <div class="about-hero-label">О НАС</div>
        <div class="about-hero-title">Вдохновляем на радость каждый день</div>
        <div class="about-hero-desc">
            Мы создаём не просто букеты, а эмоции и атмосферу. Наша команда флористов и курьеров заботится о каждом заказе, чтобы ваши цветы были свежими, а впечатления — незабываемыми.
        </div>
        <ul class="about-hero-list">
            <li><svg viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/><path d="M6 10l3 3 5-5" stroke="#fff" stroke-width="2" fill="none"/></svg> Только свежие цветы</li>
            <li><svg viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/><path d="M6 10l3 3 5-5" stroke="#fff" stroke-width="2" fill="none"/></svg> Индивидуальный подход</li>
            <li><svg viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/><path d="M6 10l3 3 5-5" stroke="#fff" stroke-width="2" fill="none"/></svg> Быстрая доставка по городу и области</li>
            <li><svg viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/><path d="M6 10l3 3 5-5" stroke="#fff" stroke-width="2" fill="none"/></svg> Фотоотчёт перед отправкой</li>
        </ul>
    </div>
</div>

<div class="about-milestones-section">
    <div class="about-milestones-label">НАШ ПУТЬ</div>
    <div class="about-milestones-title">История Bloomon: этапы и достижения</div>
    <div class="about-milestones-desc">
        Мы гордимся своим развитием и тем, что каждый год становимся ближе к нашим клиентам. Вот ключевые этапы, которые сделали нас теми, кто мы есть.
    </div>
    <div class="about-milestones-grid">
        <div class="about-milestone-card active">
            <div class="about-milestone-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/><path d="M12 7v5l4 2" stroke="#fff" stroke-width="2" fill="none"/></svg></div>
            <div class="about-milestone-title">Основание компании</div>
            <div class="about-milestone-desc">Bloomon появился из любви к цветам и желанию дарить радость каждый день.</div>
        </div>
        <div class="about-milestone-card">
            <div class="about-milestone-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/><path d="M7 12l3 3 7-7" stroke="#fff" stroke-width="2" fill="none"/></svg></div>
            <div class="about-milestone-title">Первая 1000 заказов</div>
            <div class="about-milestone-desc">Доверие клиентов — наш главный успех. Мы ценим каждого, кто выбирает нас.</div>
        </div>
        <div class="about-milestone-card">
            <div class="about-milestone-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/><path d="M12 6v6l4 2" stroke="#fff" stroke-width="2" fill="none"/></svg></div>
            <div class="about-milestone-title">Расширение доставки</div>
            <div class="about-milestone-desc">Теперь мы доставляем букеты не только по городу, но и по области.</div>
        </div>
        <div class="about-milestone-card">
            <div class="about-milestone-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="12"/><path d="M8 12l3 3 5-5" stroke="#fff" stroke-width="2" fill="none"/></svg></div>
            <div class="about-milestone-title">Собственная студия флористики</div>
            <div class="about-milestone-desc">Открыли современное пространство для творчества и обучения новых флористов.</div>
        </div>
    </div>
</div>
@endsection