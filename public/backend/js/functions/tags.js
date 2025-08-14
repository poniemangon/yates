$(document).ready(function() {
    $('#tag-name').on('input', function() {
        var tagName = $(this).val();
        
        if (tagName.trim() !== '') {
            // Generate Meta Title
            var metaTitle = tagName + ' | Tag | Smooth Sailing Yachts';
            $('#meta-title').val(metaTitle);
            
            // Generate URL Slug (replace spaces with hyphens and convert to lowercase)
            var urlSlug = tagName.toLowerCase().replace(/\s+/g, '-');
            $('#url-slug').val('/' + urlSlug);
            
            // Generate Meta Description
            var metaDescription = tagName + ' tag of Smooth Sailing Yachts';
            $('#meta-description').val(metaDescription);
        } else {
            // Clear fields if tag name is empty
            $('#meta-title').val('');
            $('#url-slug').val('');
            $('#meta-description').val('');
        }
    });
});

//tag registration
function registerTag(action, method, data, savingType) {

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
            $('#tag-registration-button').prop('disabled', true);
            $('#tag-registration-button').text('Saving...');
        },
        success: function(resp) {
            if (resp.success) {
                swal({
                title: 'Tag saved successfully', 
                    text: resp.message, 
                    type: 'success'
                }).then(function() {
                    if (savingType == 1) {
                        window.location.href = url + '/edit-tag/' + resp.tag_id;
                    }
                    if (savingType == 2) {
                        window.location.href = url + '/tags-list';
                    }
                });
            } else {
                swal({
                    title: 'Oops...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('#tag-registration-button').prop('disabled', false);
                $('#tag-registration-button').text('Save');
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
            $('#tag-registration-button').prop('disabled', false);
            $('#tag-registration-button').text('Save');

        }

    });

}

$(document).on('submit', '#tag-registration-form', function(event) {

    event.preventDefault();

    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var data = new FormData(this);

    // add saving type on button
    var savingType = $(this).find('.tag-registration-button:focus').attr('data-saving-type');
    data.append('meta_title', $('#meta-title').val());
    data.append('url_slug', $('#url-slug').val());
    data.append('meta_description', $('#meta-description').val());    
    registerTag(action, method, data, savingType);
});

//tag edit
function editTag(action, method, data, savingType) {

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
            $('#tag-edit-button').prop('disabled', true);
            $('#tag-edit-button').text('Updating...');
        },
        success: function(resp) {
            if (resp.success) {
                swal({
                    title: 'Message', 
                    text: resp.message, 
                    type: 'success'
                }).then(function() {
                    if (savingType == 1) {
                        window.location.href = url + '/edit-tag/' + resp.tag_id;
                    }
                    if (savingType == 2) {
                        window.location.href = url + '/tags-list';
                    }
                });
            } else {
                swal({
                    title: 'Oops...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('#tag-edit-button').prop('disabled', false);
                $('#tag-edit-button').text('Update');
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
            $('#tag-edit-button').prop('disabled', false);
            $('#tag-edit-button').text('Update');

        }

    });

}

$(document).on('submit', '#tag-edit-form', function(event) {

    event.preventDefault();
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var data = new FormData(this);
    // add saving type on button
    var savingType = $(this).find('.tag-edit-button:focus').attr('data-saving-type');

    data.append('meta_title', $('#meta-title').val());
    data.append('url_slug', $('#url-slug').val());
    data.append('meta_description', $('#meta-description').val());
    editTag(action, method, data, savingType);
});

//tag delete
function deleteTag(action, method) {

    $.ajax({

        url: action,
        type: method,
        data: null,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        beforeSend: function() {
            $('.delete-tag-button').prop('disabled', true);
            $('.delete-tag-button').text('Deleting...');
        },
        success: function(resp) {
            if (resp.success) {
                // Close the modal first
                $('.modal').modal('hide');
                swal({
                    title: 'Message', 
                    text: resp.message, 
                    type: 'success'
                }).then(function() {
                    location.reload();
                }); 
            } else {
                swal({
                    title: 'Oops...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('.delete-tag-button').prop('disabled', false);
                $('.delete-tag-button').text('Delete');
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
            $('.delete-tag-button').prop('disabled', false);
            $('.delete-tag-button').text('Delete');
        }

    });

}

$(document).on('click', '.delete-tag-button', function() {
    // Check if this is the button inside the modal (has data-tag-id)
    if ($(this).attr('data-tag-id')) {
        var tagId = $(this).attr('data-tag-id');
        var action = url + '/delete-tag/' + tagId;
        var method = 'delete';
        deleteTag(action, method);
    }
    // If no data-tag-id, it's the icon button that opens the modal - do nothing
});

// Event handler for the icon that opens the delete modal
$(document).on('click', '.open-delete-modal', function() {
    // This just opens the modal, no AJAX needed
    // Bootstrap handles the modal opening via data-toggle="modal"
});