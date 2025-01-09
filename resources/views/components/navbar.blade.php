<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'home' ? 'active' : '' }}" aria-current="page"
                        href="{{ route('homepage') }}">{{ __('nav.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'shop' ? 'active' : '' }}"
                        href="{{ route('shop') }}">{{ __('nav.shop') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'wishlist' ? 'active' : '' }}"
                        href="{{ route('wishlist.index') }}">{{ __('nav.wishlist') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'explore' ? 'active' : '' }}"
                        href="{{ route('explore.index') }}">{{ __('nav.explore') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'chat' ? 'active' : '' }}"
                        href="{{ route('chat') }}">{{ __('nav.chat') }}</a>
                </li>
            </ul>

            <ul class="navbar-nav gap-4">
                <li class="nav-item dropdown ms-auto" style="margin-top: 1.5px">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="localeDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('nav.language') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="localeDropdown">
                        <li><a class="dropdown-item" href="{{ route('set-locale', ['locale' => 'id']) }}">ID</a></li>
                        <li><a class="dropdown-item" href="{{ route('set-locale', ['locale' => 'en']) }}">EN</a></li>
                    </ul>
                </li>
                @if (!Auth::check())
                    <li class="nav-item">
                        <a href="{{ route('login') }}"
                            class="btn {{ $active == 'login' ? 'btn-info' : 'btn-primary' }}">{{ __('nav.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register_index') }}"
                            class="btn {{ $active == 'register' ? 'btn-info' : 'btn-primary' }}">{{ __('nav.register') }}</a>
                    </li>
                @else
                    <li class="nav-item d-flex align-items-center">
                        <a class="bi bi-bell fs-3" style="color: #000" href="{{ route('notification') }}"></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->image ? 'data:image/jpeg;base64,' . base64_encode(Auth::user()->image) : 'https://via.placeholder.com/30' }}"
                                alt="Profile" class="rounded-circle me-2" style="width: 30px; height: 30px;">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item"
                                    href="{{ route('profile.view') }}">{{ __('nav.profile') }}</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('profile.edit') }}">{{ __('nav.edit_profile') }}</a></li>
                            <li class="">
                                <a class="dropdown-item {{ $active == 'friend' ? 'active' : '' }}"
                                    href="{{ route('friends.add') }}">{{ __('nav.add_friends') }}</a>
                            </li>
                            <li class="">
                                <a class="dropdown-item {{ $active == 'friend' ? 'active' : '' }}"
                                    href="{{ route('friends.requests') }}">{{ __('nav.friend_requests') }}</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <li><button type="submit"
                                        class="dropdown-item text-danger">{{ __('nav.logout') }}</button></li>
                            </form>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
