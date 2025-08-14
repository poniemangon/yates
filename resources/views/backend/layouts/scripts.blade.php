<script>
    var url = "{{ url('/ssy-administration') }}"; 
    var clear_base_url = "{{ url('/') }}";   
</script>


<script src="{{ asset('public/backend/js/vendor.js') }}"></script>

<!-- page plugins js -->

<script src="{{ asset('public/backend/vendors/bower-jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>

<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

<script src="{{ asset('public/backend/js/maps/jquery-jvectormap-us-aea.js') }}"></script>

<script src="{{ asset('public/backend/vendors/d3/d3.min.js') }}"></script>

<script src="{{ asset('public/backend/vendors/nvd3/build/nv.d3.min.js') }}"></script>

<script src="{{ asset('public/backend/vendors/jquery.sparkline/index.js') }}"></script>

<script src="{{ asset('public/backend/vendors/chart.js/dist/Chart.min.js') }}"></script>

<script src="{{ asset('public/backend/js/app.min.js') }}"></script>

<script src="{{ asset('public/backend/js/jscolor.js') }}"></script>

<script src="{{ asset('public/backend/js/dropzone.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="{{ asset('public/backend/js/bootstrap-tagsinput.js') }}"></script>

<script src="{{ asset('public/backend/js/typeahead.bundle.js') }}"></script>

<script src="{{ asset('public/backend/js/ckeditor/ckeditor.js') }}"></script> 

<!-- Global CKEditor configuration -->
<script type="text/javascript">
    // Set CKEditor to English globally
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.config.language = 'en';
        CKEDITOR.config.defaultLanguage = 'en';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

@if (isset($scripts) && !empty($scripts))

    @foreach ($scripts as $script)

        <script type="text/javascript" src="{{ asset('public/backend/js/functions/' . $script) }}?v=5.1"></script>

    @endforeach

@endif


@if (isset($usesCKeditor) && $usesCKeditor)

	<script type="text/javascript">
		if (typeof($('#long-description')) !== 'undefined' && $('#long-description').length > 0) {
			CKEDITOR.replace('long-description', {
				removeButtons: 'Image'
			});
		}
	</script>

@endif

<script src="{{ asset('public/backend/js/dashboard/dashboard.js') }}"></script>

<script type="text/javascript">
	$(document).ready(function() {
	    $(document).on('change', 'input[type="file"]', function(e) {
	        var fileName = e.target.files[0].name;
            $(this).closest('.form-group').find('.preview-uploaded-file-name').text(fileName);
	    });
	});
</script>

<script type="text/javascript">
	$(function() {
	    $('[data-toggle="tooltip"]').tooltip()
	});
</script>
</body>
</html>