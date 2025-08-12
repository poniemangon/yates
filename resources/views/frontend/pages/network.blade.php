@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="network-page">
    <section class="banner-section">
        <div class="container">
            <div class="content d-flex">
                <h1>Expand your reach with FSCA</h1>
                <h3>Join our global network</h3>
                <a href="" class="btn-lightblue">JOIN OUR NETWORK TODAY</a>
            </div>
        </div>
    </section>

    <section class="form-section">
        <img class="logo-bg" src="{{ asset('public/frontend/images/form-logo-bg.png') }}" alt="FSC logo">
        
        <div class="container">
            <div class="content d-flex">
                <div class="col">
                    <h2>Connect with Us</h2>
                    <p class="sub">FSCA invites funeral homes and consular service providers to become part of our expansive global network. By joining, you’ll gain access to a wealth of resources and support that can help you better serve your clients.</p>

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
                                <p>Company Name*</p>
                                <input class="form-input" type="text" name="company-name" placeholder="Type here..." required>
                            </div>
                            <div class="input-div">
                                <p>Phone Number*</p>
                                <input class="form-input" type="text" name="phone" placeholder="Type here..." required>
                            </div>
                            <div class="full-input-div">
                                <p>Adress*</p>
                                <select class="form-input" name="country" required>
                                    <option class="grey-option" value="" disabled selected class="disabled">Select here...</option>
                                    <option class="black-option" value="USA">USA</option>
                                    <option class="black-option" value="Canada">Canada</option>
                                    <option class="black-option" value="UK">UK</option>
                                </select>
                            </div>
                            <div class="full-input-div">
                                <p>describe services your company provide </p>
                                <textarea class="form-input" name="serviceOrInfo" placeholder="Briefly describe your organization and what you" required></textarea>
                                
                            </div>
                        
                            <div class="full-input-div d-flex">
                                <button type="submit">customize your solution</button>
                            </div>
                        </div>
                    </form>
                </div>

                <img class="col" src="{{ asset('public/frontend/images/network-form-image.jpg') }}" alt="FSC network form image">
            </div>
        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')