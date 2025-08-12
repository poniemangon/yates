@include('frontend.layouts.head')

@include('frontend.layouts.menu')
    
<main class="search-page">
    <section class="form-section">
        <div class="container">
            <h1>Buscador</h1>
            <div class="row d-flex">
                <div class="map col">
                    <p class="bold-blue">Buscador por región</p>
                    <img src="{{ asset('public/frontend/images/finder-map.png') }}" alt="">
                    <p>Seleccionar la región de interés</p>
                </div>

                <form class="col" action="">
                    <p class="bold-blue">Buscador por filtro</p>
                    <div class="d-flex form-wrapper">
                        <div class="input-div margin-extra">
                            <select class="form-input" name="country" required>
                                <option class="black-option" value="" disabled selected class="disabled">Certificado</option>
                                <option class="black-option" value="USA">USA</option>
                                <option class="black-option" value="Canada">Canada</option>
                                <option class="black-option" value="UK">UK</option>
                            </select>
                        </div>
                        <div class="input-div margin-extra">
                            <select class="form-input" name="country" required>
                                <option class="black-option" value="" disabled selected class="disabled">Etiqueta de Envío</option>
                                <option class="black-option" value="USA">USA</option>
                                <option class="black-option" value="Canada">Canada</option>
                                <option class="black-option" value="UK">UK</option>
                            </select>
                        </div>
                        <div class="input-div">
                            <select class="form-input" name="country" required>
                                <option class="black-option" value="" disabled selected class="disabled">Funeraria</option>
                                <option class="black-option" value="USA">USA</option>
                                <option class="black-option" value="Canada">Canada</option>
                                <option class="black-option" value="UK">UK</option>
                            </select>
                        </div>
                        <div class="input-div">
                            <select class="form-input" name="country" required>
                                <option class="black-option" value="" disabled selected class="disabled">Formulario</option>
                                <option class="black-option" value="USA">USA</option>
                                <option class="black-option" value="Canada">Canada</option>
                                <option class="black-option" value="UK">UK</option>
                            </select>
                        </div>
                    
                        <div class="full-input-div d-flex search-input">
                            <input type="text" placeholder="Buscador">
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
    </section>

    <section class="files-section" id="documents">
        <div class="container">
        <div class="selects d-flex justify-content-space-btwn">
        <div class="places d-flex">
    <div class="select-block align-items-center d-flex">
            <i class="fa-solid fa-location-dot"></i>
            <select class="outlined-select" id="country" name="country">
                <option value="" disabled selected>Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->country_id }}">{{ $country->country }}</option>
                @endforeach
            </select>
    </div>

    </div>
    </div>
    <div class="files d-flex justify-content-space-btwn">
            @if (!empty($documents) && count($documents) > 0)
                @foreach ($documents as $document)
                <div class="file files-item">
                    <img class="elypsis" src="{{ asset('public/frontend/images/elipse.png') }}" alt="">
                    <img src="{{ asset('public/frontend/images/pdf-rectangle.png') }}" class="pdf-rectangle" alt="FSC - pdf rectangle">
                    <div class="texts">
                        <h5 class="bold">{{ $document->document_name }}</h5>
                        <h5>{{ $document->document_short_description }}</h5>
                    </div>
                    <div class="download-modal">
                        @if(isset($document->document_file))
                            <a href="{{ asset('public/backend/documents/' . $document->document_file) }}" download>Download</a>
                        @else
                            <p>Unavailable for download</p>
                        @endif
                </div>
                </div>
                @endforeach
            @else
                <p>No documents available.</p>
            @endif
        </div>
    </section>

    @include('frontend.layouts.newsletter')
</main>

@include('frontend.layouts.footer')

@include('frontend.layouts.scripts')