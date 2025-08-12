<script>
    var url = "{{ url('/') }}"; 
    var clear_base_url = "{{ url('/') }}";   
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="{{ asset('public/frontend/js/scripts.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
@if (isset($scripts) && !empty($scripts))

    @foreach ($scripts as $script)

        <script type="text/javascript" src="{{ asset('public/frontend/js/functions/' . $script) }}?v=5.1"></script>

    @endforeach

@endif
</body>
</html>