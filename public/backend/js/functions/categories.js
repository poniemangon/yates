$(document).ready(function() {
    $('#category-name').on('input', function() {
        var categoryName = $(this).val();
        
        if (categoryName.trim() !== '') {
            // Generate Meta Title
            var metaTitle = categoryName + ' | Category | Smooth Sailing Yachts';
            $('#meta-title').val(metaTitle);
            
            // Generate URL Slug (replace spaces with hyphens and convert to lowercase)
            var urlSlug = categoryName.toLowerCase().replace(/\s+/g, '-');
            $('#url-slug').val('/' + urlSlug);
            
            // Generate Meta Description
            var metaDescription = categoryName + ' category of Smooth Sailing Yachts';
            $('#meta-description').val(metaDescription);
        } else {
            // Clear fields if category name is empty
            $('#meta-title').val('');
            $('#url-slug').val('');
            $('#meta-description').val('');
        }
    });
});

//category registration
function registerCategory(action, method, data, savingType) {

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
            $('#category-registration-button').prop('disabled', true);
            $('#category-registration-button').text('Saving...');
        },
        success: function(resp) {
            if (resp.success) {
                swal({
                    title: 'Mensaje', 
                    text: resp.message, 
                    type: 'success'
                }).then(function() {
                    if (savingType == 1) {
                        window.location.href = url + '/edit-category/' + resp.category_id;
                    }
                    if (savingType == 2) {
                        window.location.href = url + '/categories-list';
                    }
                });
            } else {
                swal({
                    title: 'Ups...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('#category-registration-button').prop('disabled', false);
                $('#category-registration-button').text('Save');
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
            $('#category-registration-button').prop('disabled', false);
            $('#category-registration-button').text('Save');

        }

    });

}

$(document).on('submit', '#category-registration-form', function(event) {

    event.preventDefault();

    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var data = new FormData(this);

    // add saving type on button
    var savingType = $(this).find('.category-registration-button:focus').attr('data-saving-type');
    data.append('meta_title', $('#meta-title').val());
    data.append('url_slug', $('#url-slug').val());
    data.append('meta_description', $('#meta-description').val());    
    registerCategory(action, method, data, savingType);
});

//category edit
function editCategory(action, method, data, savingType) {

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
            $('#category-edit-button').prop('disabled', true);
            $('#category-edit-button').text('Updating...');
        },
        success: function(resp) {
            if (resp.success) {
                swal({
                    title: 'Mensaje', 
                    text: resp.message, 
                    type: 'success'
                }).then(function() {
                    if (savingType == 1) {
                        window.location.href = url + '/edit-category/' + resp.category_id;
                    }
                    if (savingType == 2) {
                        window.location.href = url + '/categories-list';
                    }
                });
            } else {
                swal({
                    title: 'Ups...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('#category-edit-button').prop('disabled', false);
                $('#category-edit-button').text('Update');
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
            $('#category-edit-button').prop('disabled', false);
            $('#category-edit-button').text('Update');

        }

    });

}

$(document).on('submit', '#category-edit-form', function(event) {

    event.preventDefault();
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var data = new FormData(this);
    // add saving type on button
    var savingType = $(this).find('.category-edit-button:focus').attr('data-saving-type');

    data.append('meta_title', $('#meta-title').val());
    data.append('url_slug', $('#url-slug').val());
    data.append('meta_description', $('#meta-description').val());
    editCategory(action, method, data, savingType);
});

//category delete
function deleteCategory(action, method) {

    $.ajax({

        url: action,
        type: method,
        data: null,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        beforeSend: function() {
            $('.delete-category-button').prop('disabled', true);
            $('.delete-category-button').text('Deleting...');
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
                $('.delete-category-button').prop('disabled', false);
                $('.delete-category-button').text('Delete');
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
            $('.delete-category-button').prop('disabled', false);
            $('.delete-category-button').text('Delete');
        }

    });

}

$(document).on('click', '.delete-category-button', function() {
    var categoryId = $(this).attr('data-category-id');
    var action = url + '/delete-category/' + categoryId;
    var method = 'delete';
    deleteCategory(action, method);
});