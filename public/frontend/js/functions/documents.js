

// ELIPSIS DESCARGA

$(document).ready(function() {
    // Usar delegación de eventos para manejar clicks en los elementos generados dinámicamente
    $(document).on('click', '.files-section .container .files .file .elypsis', function(event) {
        console.log('elipsis');
        event.stopPropagation(); // Prevent click event from bubbling up

        // Close all other download-modal divs
        $('.download-modal').not($(this).closest('.file').find('.download-modal')).hide();

        // Toggle the current download-modal
        $(this).closest('.file').find('.download-modal').toggle();
    });

    // Close all download-modal divs if clicked anywhere else
    $(document).on('click', function() {
        $('.download-modal').hide();
    });

    // Prevent closing the modal when clicking inside the download-modal
    $(document).on('click', '.files-section .container .files .file .download-modal', function(event) {
        event.stopPropagation();
    });
});
// ELIPSIS DESCARGA

$(document).ready(function() {

    var urlPath = window.location.pathname;


   
    if (urlPath === '/search/' || urlPath === '/search') {

       
        $('#country').prop('selectedIndex', 1); 
    } else {

        var urlParts = urlPath.split('/');
        var countryId = urlParts[urlParts.length - 1]; 
        

        if (countryId && countryId !== 'search') {

            $('#country').val(countryId);
      
        } else {
 
 
            $('#country').prop('selectedIndex', 1);
        }
    }
    $('.select-block #country').change(function() {
        var selectedCountryId = $(this).val();

        if (selectedCountryId) {
            $.ajax({
                url: url + '/search/' + selectedCountryId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Actualiza el contenido de documentos con el HTML recibido
                        $('.files').html(data.documentsHtml);
                    } else {
                        swal({
                            title: 'Ups...',
                            text: 'No se encontraron documentos para este país',
                            type: 'warning'
                        });
                    }
                },
                error: function(xhr) {
                    swal({
                        title: 'Error',
                        text: 'Hubo un problema al cargar los documentos',
                        type: 'error'
                    });
                }
            });
        }
    });
});



// BUSCADOR Y SUGESTION

// Variables para el manejo de selección
var selectedIndex = -1; // Indice de la opción seleccionada actualmente
var suggestionItems = [];

// Function to filter and display suggestions
function suggestCountries(query) {
    // Clear previous suggestions
    $('#suggestionList').empty();
    selectedIndex = -1; // Reset index
    suggestionItems = []; // Reset items array

    // Filter countries based on the input query (case-insensitive)
    var filteredCountries = countries.filter(function(country) {
        return country.country.toLowerCase().includes(query.toLowerCase());
    });

    // Append filtered results as list items
    filteredCountries.forEach(function(country) {
        $('#suggestionList').append('<li><p>' + country.country + '</p></li>');
        suggestionItems.push(country.country); // Add to items array
    });

    // Show suggestions if there are any
    if (filteredCountries.length > 0) {
        $('#suggestionList').addClass('active'); // Add active class for visibility
    } else {
        $('#suggestionList').removeClass('active'); // Remove active class if no results
    }
}

// Capture input event on the search field
$('#countrySearch').on('input', function() {
    var query = $(this).val();

    // Only suggest when there is input
    if (query.length > 0) {
        suggestCountries(query);
    } else {
        $('#suggestionList').empty(); // Clear suggestions if input is empty
        $('#suggestionList').removeClass('active'); // Hide suggestions
    }
});

// Handle keydown event for navigation
$('#countrySearch').on('keydown', function(e) {
    var suggestionCount = $('#suggestionList li').length;

    if (e.key === 'ArrowDown') {
        selectedIndex = (selectedIndex + 1) % suggestionCount; // Move down
        highlightSuggestion();
        e.preventDefault(); // Prevent the cursor from moving
    } else if (e.key === 'ArrowUp') {
        selectedIndex = (selectedIndex - 1 + suggestionCount) % suggestionCount; // Move up
        highlightSuggestion();
        e.preventDefault(); // Prevent the cursor from moving
    } else if (e.key === 'Enter') {
        // Prevent form submission if suggestions are visible
        if ($('#suggestionList').hasClass('active') && selectedIndex >= 0) {
            e.preventDefault(); // Prevent form submission
            $('#suggestionList li').eq(selectedIndex).trigger('click'); // Select highlighted suggestion
        }
    }
});

// Highlight the currently selected suggestion
function highlightSuggestion() {
    $('#suggestionList li').removeClass('highlighted'); // Remove previous highlights
    if (selectedIndex >= 0) {
        $('#suggestionList li').eq(selectedIndex).addClass('highlighted'); // Highlight current
    }
}

// Handle click event on each suggestion
$(document).on('click', '#suggestionList li', function() {
    // Get the text of the clicked suggestion (inside the <p> tag)
    var selectedCountry = $(this).find('p').text();

    // Set the input field value to the selected suggestion
    $('#countrySearch').val(selectedCountry);

    // Submit the form automatically
    $('#searchForm').submit();
});

// Optional: Handle mouse hover for suggestions
$(document).on('mouseenter', '#suggestionList li', function() {
    selectedIndex = $('#suggestionList li').index(this);
    highlightSuggestion();
});

// Close suggestions on clicking outside
$(document).on('click', function(event) {
    if (!$(event.target).closest('#searchForm').length) {
        $('#suggestionList').empty(); // Clear suggestions
        $('#suggestionList').removeClass('active'); // Hide suggestions
    }
});


// BUSCADOR Y SUGESTION

// AJAX PARA BUSCADOR

function searchCountry(action, method, data) {
    
    $.ajax({
        url: action,
        type: method,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        beforeSend: function() {
            // Desactivar el botón de búsqueda mientras se procesa la solicitud
            $('#searchButton').prop('disabled', true);
        },
        success: function(resp) {
            if (resp.success) {
                // Redirigir directamente a la página con los resultados
                window.location.href = url + '/search/' + resp.countryId + '#documents';
            } else {
                swal({
                    title: 'Ups...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('#searchButton').prop('disabled', false);
            }
        },
        error: function(xhr) {
            $.each(xhr.responseJSON.errors, function(key, value) {
                swal({
                    title: 'Ups...', 
                    text: value, 
                    type: 'warning'
                });
            });
            $('#searchButton').prop('disabled', false);
        }
    });
}

// Handling form submission for the search form
$(document).on('submit', '#searchForm', function(event) {
    event.preventDefault();

    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var data = new FormData(this);

    // Call the searchCountry function to make the AJAX request
    searchCountry(action, method, data);
});
