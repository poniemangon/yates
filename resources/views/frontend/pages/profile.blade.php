@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="contact-us-page profile-page">
    <section class="banner-section">
        <div class="container">
            <div class="content d-flex">
                @if (session()->has('loggedUser'))
                    @php
                        $user = session('loggedUser');
                    @endphp
                    <h1>Profile</h1>
                    <h3>{{ $user['name'] }} {{ $user['surname'] }}</h3>
                    
                @else
                    <h1>No profile</h1>
                @endif
            </div>
        </div>  
    </section>
    <section class="profile-data-section">
        <div class="container">
            <div class="content">
            @if (session()->has('loggedUser'))
                        @php
                            $user = session('loggedUser');
                        @endphp

                        <h3>{{ $user['user_role'] }} </h3>
                        
                    @else
                        
                    @endif
                <a class="m-auto btn-lightblue" href="{{route('logout')}}">LOG OUT</a>
            </div>

        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>


@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')