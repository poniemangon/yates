

//category delete
function deleteArticle(action, method) {

    $.ajax({

        url: action,
        type: method,
        data: null,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        beforeSend: function() {
            $('.delete-article-button').prop('disabled', true);
            $('.delete-article-button').text('Deleting...');
        },
        success: function(resp) {
            if (resp.success) {
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
                $('.delete-article-button').prop('disabled', false);
                $('.delete-article-button').text('Delete');
            }
        },
        error: function(xhr) {
            $.each(xhr.responseJSON.errors, function(key, value) {
                swal({
                    title: 'Oops...', 
                    text: value, 
                    type: 'warning'
                });
            });
            $('.delete-article-button').prop('disabled', false);
            $('.delete-article-button').text('Delete');
        }

    });

}

$(document).on('click', '.delete-article-button', function() {
    var articleId = $(this).attr('data-article-id');
    var action = url + '/delete-article/' + articleId;
    var method = 'delete';
    deleteArticle(action, method);
});







