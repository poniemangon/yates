@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="contact-us-page register-page">
    <section class="banner-section">
        <div class="container">
            <div class="content d-flex">
                <h1>Register</h1>
                <a href="{{ route('login') }}" class="btn"> Go to Log in</a>
                <!-- <h3>Get in touch with us</h3>
                <a href="" class="btn-lightblue">contact us</a> -->
            </div>
        </div>  
    </section>
    <section class="form-section">
    <img class="logo-bg" src="{{ asset('public/frontend/images/form-logo-bg.png') }}" alt="FSC logo">


    <div class="container">
        
        <form id="user-registration-form" action="{{ route('registration-user') }}" method="post">
            @csrf
            <div class="d-flex form-wrapper">
                <div class="input-div">
                    <p>Name*</p>
                    <input class="form-input" type="text" name="name" placeholder="Type here..." required>
                </div>
                <div class="input-div">
                    <p>Surname*</p>
                    <input class="form-input" type="text" name="surname" placeholder="Type here..." required>
                </div>
                <div class="input-div">
                    <p>Email Address*</p>
                    <input class="form-input" type="email" name="email" placeholder="Type here..." required>
                </div>
                <div class="input-div">
                    <p>Password*</p>
                    <input class="form-input" type="password" name="password" placeholder="Type here..." required>
                </div>
                <div class="input-div">
                    <p>Repeat Password*</p>
                    <input class="form-input" type="password" name="repeatPassword" placeholder="Type here..." required>
                </div>
                <div class="input-div">
                    <p>Role*</p>
                    <select class="form-input" name="role" required>
                        <option class="grey-option" value="" disabled selected>Choose Role*</option>
                        @foreach($userRoles as $role)
                            <option class="black-option" value="{{ $role->user_role_id }}">{{ $role->user_role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="full-input-div">
                    <p>Country*</p>
                    <select class="form-input" name="country" id="countrySelect">
                        <option class="grey-option" value="" disabled selected>Country*</option>
                        @foreach($countries as $country)
                            <option class="black-option" value="{{ $country->country_id }}">{{ $country->country }}</option>
                        @endforeach
                    </select>
                    <div class="selected-countries d-flex">
                        <ul class="d-flex">
                            <!-- <li class="selected-country">
                                <div>
                                    <p>Argentina</p>
                                </div>
                            </li>
                            <li class="selected-country">
                                <div>
                                    <p>Argentina</p>
                                </div>
                            </li>
                            <li class="selected-country">
                                <div>
                                    <p>Argentina</p>
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </div>

                <div class="full-input-div d-flex justify-content-center">
                    <button type="submit" class="user-registration-button">REGISTER</button>
                </div>
            </div>
        </form>
    </div>
</section>


    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')
<script>
    const countries = @json($countries);
  
</script>
@include('frontend.layouts.scripts')