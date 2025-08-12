@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="about-us-page">
    <section class="banner-section">
        <div class="container">
            <div class="content d-flex">
                <h1>Supporting funeral homes worldwide</h1>
                <h2>Always your partner</h2>
                <a href="" class="btn-lightblue">CONNECT WITH OUR SPECIALISTS</a>
            </div>
        </div>
    </section>

    <section class="secondary-banner-section">
        <div class="container">
            <div class="content d-flex">
                <img src="{{ asset('public/frontend/images/logo.png') }}" alt="">
                <div class="texts">
                    <h2>+10 YEARS</h2>
                    <p>OF PARTNERSHIP</p>
                </div>
            </div>
        </div>
    </section>

    <section class="funeral-texts-section">
        <div class="row d-flex">
            <img src="{{ asset('public/frontend/images/about-us-half-image.jpg') }}" alt="" class="col">
            <div class="col text-col">
                <div class="texts">
                    <h3>We provide peace of mind, allowing
                        you to focus on supporting your clients
                        during their time of need.</h3>
                    <p>
                        For over a decade, FSCA has been the go-to partner for funeral homes managing the complex process of international repatriation. Our deep expertise ensures that the remains of your client's loved ones are transported smoothly across borders, with every logistical detail handled with care and precision.
                        <br><br>
                        We recognize that each funeral home has unique needs when it comes to international transport. That’s why we offer custom solutions that cater specifically to your requirements, whether it’s managing consular documentation, coordinating air transport, or ensuring compliance with local regulations. Our dedicated team works closely with you to streamline the process, making it as efficient and stress-free as possible. 
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cards-section">
        <div class="container">
            <div class="content">
                <h2>Personalized support for every funeral home</h2>
                <h3>Customized support, every step of the way</h3>
                <div class="cards d-flex">
                    <div class="card d-flex">
                        <div class="texts">
                            <p class="secondary-color">Documents by country</p>
                            <p>Access and manage all necessary documents required for funeral shipping, tailored to specific country regulations.</p>
                        </div>
                        <img src="{{ asset('public/frontend/images/about-us-cards/first.png') }}" alt="FSC about us card first">
                    </div>

                    <div class="card d-flex">
                        <div class="texts">
                            <p class="secondary-color">Consular Translations</p>
                            <p>Obtain certified translations for all consular documents, ensuring compliance with international requirements.</p>
                        </div>
                        <img src="{{ asset('public/frontend/images/about-us-cards/second.png') }}" alt="FSC about us card second">
                    </div>

                    <div class="card d-flex">
                        <div class="texts">
                            <p class="secondary-color">Air Transport</p>
                            <p>Coordinate the safe and efficient air transportation of remains to their final destination, with full logistical support.</p>
                        </div>
                        <img src="{{ asset('public/frontend/images/about-us-cards/third.png') }}" alt="FSC about us card third">
                    </div>

                    <div class="card d-flex">
                        <div class="texts">
                            <p class="secondary-color">Request Local Agent</p>
                            <p>Connect with trusted local agents to facilitate on-the-ground assistance and ensure a smooth service delivery.</p>
                        </div>
                        <img src="{{ asset('public/frontend/images/about-us-cards/fourth.png') }}" alt="FSC about us card fourth">
                    </div>
                </div>

                <div class="button">
                    <a href="" class="btn-lightblue">customize your solution</a>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')