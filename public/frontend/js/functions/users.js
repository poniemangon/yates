
// countries array select

$(document).ready(function() {
    var selectedCountries = []; 

  
    $('#countrySelect').on('change', function() {
        console.log($(this));
        var selectedCountryId = $(this).val(); 
        var selectedCountryText = $(this).find('option:selected').text(); 
        // var selectedCountryPrice = $(this).

        var countryExists = selectedCountries.some(country => country.id === selectedCountryId);

        if (!countryExists) {
     
            selectedCountries.push({ id: selectedCountryId, name: selectedCountryText});

           
            var newCountryItem = `
                <li class="selected-country" data-id="${selectedCountryId}">
                    <div>
                        <p>${selectedCountryText}</p>
                        <i class="fa-solid fa-x"></i> <!-- Remove icon -->
                    </div>
                </li>
            `;

          
            $('.selected-countries ul').append(newCountryItem);

     
            $('#countrySelect').val(''); 

           
            
        }


    });


    $(document).on('click', '.selected-country', function() {
        var countryIdToRemove = $(this).data('id'); 
   
        selectedCountries = selectedCountries.filter(country => country.id != countryIdToRemove);

        $(this).remove();

      
        $('#countrySelect').val(''); 

    
    });

    $(document).on('submit', '#user-registration-form', function(event) {

        event.preventDefault();
    
        var action = $(this).attr('action');
    
        var method = $(this).attr('method');
    
        var data = new FormData(this);

        data.append('selected_countries', JSON.stringify(selectedCountries));
    
        var savingType = $(this).find('.user-registration-button:focus').attr('data-saving-type');
    
        registerUser(action, method, savingType, data);
    
    });

   
    $('#countrySelect').val(''); 
});

// User registration
function registerUser(action, method, savingType, data) {

  



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
            $('.user-registration-button').prop('disabled', true);
        },
        success: function(resp) {
            if (resp.success) {
                swal({
                    title: 'Mensaje',
                    text: resp.message,
                    type: 'success'
                }).then(function() {
                    window.location.href = url + '/login';
                });
            } else {
                swal({
                    title: 'Ups...',
                    text: resp.message,
                    type: 'warning'
                });
                $('.user-registration-button').prop('disabled', false);
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
            $('.user-registration-button').prop('disabled', false);
        }
    });
}




//user login

function loginUser(action, method, data) {

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

            $('#user-login-button').prop('disabled', true);

            $('#user-login-button').text('Accessing...');

        },

        success: function(resp) {

            if (resp.success) {
                console.log(url);
                
                window.location.href = url;

            } else {

                swal({

                    title: 'Ups...', 

                    text: resp.message, 

                    type: 'warning'

                });



                $('#user-login-button').prop('disabled', false);

                $('#user-login-button').text('Access');

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

            $('#user-login-button').prop('disabled', false);

            $('#user-login-button').text('Access');

        }

    });

}

$(document).on('submit', '#user-login-form', function(event) {

    event.preventDefault();

    var action = $(this).attr('action');

    var method = $(this).attr('method');

    var data = new FormData(this);

    loginUser(action, method, data);

});










//user edition
function editUser(action, method, data, savingType) {

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

            $('.user-edition-button').prop('disabled', true);

        },

        success: function(resp) {

            if (resp.success) {

                swal({

                    title: 'Mensaje', 

                    text: resp.message, 

                    type: 'success'

                }).then(function() {

                    if (savingType == 1) {
                        window.location.href = url + '/edit-user/' + resp.userId;
                    }

                    if (savingType == 2) {
                        window.location.href = url + '/users-list';
                    }

                }); 

            } else {

                swal({

                    title: 'Ups...', 

                    text: resp.message, 

                    type: 'warning'

                });



                $('.user-edition-button').prop('disabled', false);
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



            $('.user-edition-button').prop('disabled', false);
        }

    });

}

$(document).on('submit', '#user-edition-form', function(event) {

    event.preventDefault();

    var action = $(this).attr('action');

    var method = $(this).attr('method');

    var data = new FormData(this);

    var savingType = $(this).find('.user-edition-button:focus').attr('data-saving-type');

    editUser(action, method, data, savingType);

});



//delete user
function deleteUser(action, method) {

    $.ajax({

        url: action,

        type: method,

        data: null,

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        dataType: 'json',

        beforeSend: function() {

            $('.delete-user-button').prop('disabled', true);

            $('.delete-user-button').text('Deleting...');

        },

        success: function(resp) {

            if (resp.success) {

                swal({

                    title: 'Mensaje', 

                    text: resp.message, 

                    type: 'success'

                }).then(function() {

                    location.reload();

                }); 

            } else {

                swal({

                    title: 'Ups...', 

                    text: resp.message, 

                    type: 'warning'

                });

                $('.delete-user-button').prop('disabled', false);

                $('.delete-user-button').text('Delete');

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

            $('.delete-user-button').prop('disabled', false);

            $('.delete-user-button').text('Delete');

        }

    });

}

$(document).on('click', '.delete-user-button', function() {

    var userId = $(this).attr('data-user-id');

    var action = url + '/delete-user/' + userId;

    var method = 'delete';

    deleteUser(action, method);

});

// user role cities conditional 





$(document).ready(function() {
    // Cache the country dropdown element
    const $countryDropdown = $('select[name="country"]').closest('.full-input-div');

    // Function to toggle the visibility of the country dropdown
    function toggleCountryDropdown() {
        const selectedRoleId = $('select[name="role"]').val();
        
        // Check if the selected role is "onetime" (id 2)
        if (selectedRoleId == 2) { // Change the ID based on your requirements
            $countryDropdown.show(); // Show the country dropdown
        } else {
            $countryDropdown.hide(); // Hide the country dropdown
        }
    }

    // Initial state - hide country dropdown by default
    $countryDropdown.hide();

    // Attach the change event to the role dropdown
    $('select[name="role"]').change(toggleCountryDropdown);

    // Check the role on page load in case it has a pre-selected value
    toggleCountryDropdown();
});

// user role cities conditional 

