@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main>
    <script>
        var countries = @json($countries);
    </script>
 <section class="banner-section">
        <div class="search-wrapper">
            <div class="container">
                <div class="content d-flex">
                    <h1>Search by country</h1>
                    <form action="{{ route('search') }}" method="post" id="searchForm" class="input-field d-flex align-items-center justify-content-space-btwn" required autocomplete="off">
                        @csrf 
                        <input type="text" name="search" id="countrySearch" placeholder="Type your country..." required>
                        <button type="submit" class="search-button" id="searchButton">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <div class="suggestions">
                            <ul id="suggestionList"></ul>
                        </div>
                    </form>
                    <img src="{{ asset('public/frontend/images/Avion.png') }}" alt="FSC - Plane over search banner" class="plane">
                </div>
            </div>
        </div>

            </div>

        <div class="carousel-banner">
            <div class="carousel-item">

            </div>
            <div class="carousel-item">
                <!-- <div class="container">
                    <div class="content d-flex">
                        <h1>Search by country</h1>
                        <form action="" class="input-field d-flex align-items-center justify-content-space-btwn">
                            <input type="text" placeholder="Type your country...">
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form> 
                        <img src="{{ asset('public/frontend/images/Avion.png') }}" alt="" class="plane">
                    </div>
                </div> -->
            </div> 
            <div class="carousel-item">
                <div class="container">
                    <div class="content d-flex">
                      <!-- 
                        <h1>Search by country</h1>
                        <form action="" class="input-field d-flex align-items-center justify-content-space-btwn">
                            <input type="text" placeholder="Type your country...">
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form> 
                        --> 
                        
                        <!-- <img src="{{ asset('public/frontend/images/Avion.png') }}" alt="" class="plane"> -->
                    </div>
                </div>
            </div>                  
        </div>
    </section>

    <section class="rounded-boxes-section">
        <div class="container">
            <div class="boxes d-flex justify-content-space-btwn align-items-center">
                <div class="col first-col">
                    <div class="box">
                        <div class="inner-text-left">
                            <p class="bold">Consular Translations</p>
                            <p>Certified Translations</p>
                        </div>
                    </div>
                </div>
                <div class="col second-col">
                    <div class="row first-row d-flex justify-content-space-btwn">
                        <div class="box">
                            <div class="inner-text-left">
                                <p class="bold">Transportation Logistics</p>
                                <p>Safe Transportation</p>
                            </div>
                        </div>
                        <div class="box text-right">
                            <div class="inner-text-right">
                                <p class="bold">Local Agent Coordination</p>
                                <p>Trusted Agents</p>
                            </div>
                        </div>
                        <div class="box text-right">
                            <div class="inner-text-right">
                                <p class="bold">Complete Assistance</p>
                                <p>Full Support</p>
                            </div>
                        </div>
                    </div>
                    <div class="row second-row d-flex justify-content-space-btwn">
                        <div class="box">
                            <div class="inner-text-left">
                                <p class="bold">Document Preparation</p>
                                <p>Accurate Documents</p>
                            </div>
                        </div>
                        <div class="box text-right">
                            <div class="inner-text-right">
                                <p class="bold">Global Network</p>
                                <p>Worldwide Network</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="texts-heading d-flex">
                <img class="m-auto" src="{{ asset('public/frontend/images/logo.png') }}" alt="FSC - Plane logo">
                <h2>Repatriate better, faster, smarter</h2>
            </div>
        </div>
    </section>

    <section class="files-section">
        <div class="container">
            <div class="selects d-flex justify-content-space-btwn">
                <div class="places d-flex">
                    <div class="select-block align-items-center d-flex">
                        <i class="fa-solid fa-location-dot"></i>
                        <select class="outlined-select" id="country" name="country">
                            <option value="" disabled selected>Country</option>
                            <option value="usa">United States</option>
                            <option value="canada">Canada</option>
                            <option value="mexico">Mexico</option>
                            
                        </select>
                    </div>
                    <div class="select-block align-items-center d-flex">
                        <i class="fa-solid fa-location-dot"></i>
                        <select class="outlined-select" id="country" name="country">
                            <option value="" disabled selected>City</option>
                            <option value="usa">United States</option>
                            <option value="canada">Canada</option>
                            <option value="mexico">Mexico</option>
                            
                        </select>
                    </div>
                </div>
                <div class="outlined select-block align-items-center d-flex">
                    <i class="fa-regular fa-note-sticky"></i>
                    <select  id="country" name="country">
                        <option value="" disabled selected>Files</option>
                        <option value="usa">United States</option>
                        <option value="canada">Canada</option>
                        <option value="mexico">Mexico</option>
                    </select>
                </div>
            </div>

            <div class="files d-flex justify-content-space-btwn">
                <div class="folder files-item">
                    <img src="{{ asset('public/frontend/images/folder.png') }}" alt="">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>

                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">Name</h5>
                        <h5>Description</h5>
                    </div>
                    <div class="download-modal">
                        <a href="">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')