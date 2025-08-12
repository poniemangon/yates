
        <div class="files d-flex flex-wrap justify-content-space-btwn">
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

