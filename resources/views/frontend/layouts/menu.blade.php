<header @if (isset($hasDarkNav) && $hasDarkNav) class="dark-nav" @elseif (isset($hasBlueNav) && $hasBlueNav) class="blue-nav" @else class="light-nav" @endif>
    <section class="navbar-section">
        <div class="container">
            <ul class="d-flex justify-content-space-btwn align-items-center desktop-menu">
                <li><a href="{{ route('/') }}"><img src="{{ asset('public/frontend/images/navbar-logo.png') }}" alt="FSC Logo"></a></li>
                <li><a href="{{ route('about-us') }}">About Us</a></li>
                <li><a href="{{ route('search') }}#documents">Documents</a></li>
                <li><a href="{{ route('network') }}">Network</a></li>
                @if (session()->has('loggedUser'))
                    <li><a href="{{ route('profile') }}">Profile</a></li>
                @else
                    <li><a href="{{ route('registration-user') }}">Register</a></li>
                @endif
                <li><a class="btn" href="{{ route('contact-us') }}">Contact Us</a></li>
            </ul>
            <div class="mobile-navbar d-flex justify-content-space-btwn align-items-center">
                <a class="navbar-logo-mobile" href="{{ route('/') }}"><img src="{{ asset('public/frontend/images/navbar-logo.png') }}" alt="FSC Logo"></a>
                <i class="fa-solid fa-bars"></i>
            </div>
            <div class="mobile-menu">
                <i class="fa-solid fa-x"></i>
                <ul>
                    <li><a href="{{ route('about-us') }}">About Us</a></li>
                    <li><a href="{{ route('search') }}#documents">Documents</a></li>
                    <li><a href="{{ route('network') }}">Network</a></li>
                    @if (session()->has('loggedUser'))
                        <li><a href="{{ route('profile') }}">Profile</a></li>
                    @else
                        <li><a href="{{ route('registration-user') }}">Register</a></li>
                    @endif
                    <li class="btn"><a class="btn" href="{{ route('contact-us') }}">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </section>
</header>
