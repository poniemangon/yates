@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="contact-us-page">
    <section class="banner-section">
        <div class="container">
            <div class="content d-flex">
                <h1>Log In</h1>
            </div>
        </div>  
    </section>

    <section class="form-section">
        <img class="logo-bg" src="{{ asset('public/frontend/images/form-logo-bg.png') }}" alt="FSC logo">
        
        <div class="container">
            <form id="user-login-form" action="{{ route('login-user') }}" method="post">
                <div class="d-flex form-wrapper">
                    <div class="input-div">
                        <p>Email Address*</p>
                        <input class="form-input" type="email" name="email" placeholder="Type here..." required>
                    </div>
                    <div class="input-div">
                        <p>Password*</p>
                        <input class="form-input" type="password" name="password" placeholder="Type here..." required>
                    </div>
                
                    <div class="full-input-div d-flex justify-content-center">
                        <button id="user-login-button" type="submit">SEND</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')