@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="contact-us-page">
    <section class="banner-section">
        <div class="container">
            <div class="content d-flex">
                <h1>Tell us about your needs</h1>
                <h3>Get in touch with us</h3>
                <a href="" class="btn-lightblue">contact us</a>
            </div>
        </div>  
    </section>

    <section class="form-section">
        <img class="logo-bg" src="{{ asset('public/frontend/images/form-logo-bg.png') }}" alt="FSC logo">
        
        <div class="container">
            <form action="">
                <div class="d-flex form-wrapper">
                    <div class="input-div">
                        <p>Full Name*</p>
                        <input class="form-input" type="text" name="fullName" placeholder="Type here..." required>
                    </div>
                    <div class="input-div">
                        <p>Email Address*</p>
                        <input class="form-input" type="email" name="email" placeholder="Type here..." required>
                    </div>
                    <div class="input-div">
                        <p>Organization*</p>
                        <input class="form-input" type="text" name="organization" placeholder="Type here..." required>
                    </div>
                    <div class="input-div">
                        <p>Phone Number*</p>
                        <input class="form-input" type="text" name="phone" placeholder="Type here..." required>
                    </div>
                    <div class="full-input-div">
                        <p>Country*</p>
                        <select class="form-input" name="country" required>
                            <option class="grey-option" value="" disabled selected class="disabled">Country*</option>
                            <option class="black-option" value="USA">USA</option>
                            <option class="black-option" value="Canada">Canada</option>
                            <option class="black-option" value="UK">UK</option>
                        </select>
                    </div>
                    <div class="full-input-div">
                        <p>Are you seeking a specific service or just information*</p>
                        <textarea class="form-input" name="serviceOrInfo" placeholder="Type here..." required></textarea>
                        
                    </div>
                
                    <div class="full-input-div d-flex justify-content-center">
                        <button type="submit">SEND</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')