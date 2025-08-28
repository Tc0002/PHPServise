<nav class="navbar">
    <div class="navbar-container">
        <a href="/" class="navbar-brand">
            🏪 Laravel Product System
        </a>
        <ul class="navbar-nav">
            <li><a href="/" class="{{ request()->routeIs('welcome') ? 'active' : '' }}">ホーム</a></li>
            <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}">登録一覧</a></li>
            <li><a href="{{ route('products.create') }}" class="{{ request()->routeIs('products.create') ? 'active' : '' }}">新規登録</a></li>
        </ul>
    </div>
</nav> 