var accepted_multimedia = 30;

var deletedMultimedia = [];


Dropzone.autoDiscover = false;

var Dropzone = new Dropzone(

    '#myDropzone',

    {

        url: url + '/edit-article/' + articleId,

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },

        acceptedFiles: 'image/jpg, image/png, image/jpeg',

        autoProcessQueue: false,

        dictDefaultMessage: 'Seleccionar archivos',

        uploadMultiple: true,

        maxFiles: 100,

        parallelUploads: 100,

        enctype: 'multipart/form-data',

        init: function() {

        dzClosure = this;


        $(document).on('click', '.article-edition-button', function(e) {

          
            e.preventDefault();

            e.stopPropagation();

            $('.article-edition-button').prop('disabled', true);

            var savingType = $(this).attr('data-saving-type');


            var formData = new FormData();

            formData.append('title', $('#article-title').val());

            formData.append('excerpt', $('#excerpt').val());

            formData.append('body', CKEDITOR.instances['article-body'].getData());

            formData.append('category_id', $('#category_id').val());
            formData.append('meta_title', $('#meta-title').val());
            formData.append('meta_description', $('#meta-description').val());
            formData.append('url_slug', $('#url-slug').val());
            
            // Handle publish date based on checkbox
            if ($('#publish-checkbox').is(':checked')) {
                formData.append('publish_date', $('#publish-date').val());
            } else {
                formData.append('publish_date', ''); // Will be set to now on backend
            }

            var multimedia_gallery = new Array();
            
       

            var pos = 0;

            $.each($('#multimedia_gallery tbody tr'), function() {
                var row = $(this);
                
                multimedia_gallery.push({
                    "filename": row.find('input[name*="[filename]"]').val(),
                    "file_alternative_text": row.find('input[name*="[file_alternative_text]"]').val(),
                    "position": pos,
                    "id": row.find('input[name*="[filename]"]').attr('data-id'),
                    "type": row.find('button.deleteRow').attr("data-type"),
                    "thumbnail": row.find('img.property-images-edit').attr("src")
                });

                pos = pos + 1;

            });

            multimedia_gallery = JSON.stringify(multimedia_gallery)

            formData.append('multimedia_gallery', multimedia_gallery);
            formData.append('deleted_multimedia', deletedMultimedia);
            
            console.log('Sending deletedMultimedia:', deletedMultimedia);
            console.log('Sending multimedia_gallery:', multimedia_gallery);

            // Get selected tags and append to form data
            const selectedTags = getSelectedTags();
            formData.append('selected_tags', JSON.stringify(selectedTags));

            $.ajax({
                url: url + '/edit-article/' + articleId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (!response.success) {
                        swal({
                          title: 'Ups...', 
                          text: response.message, 
                          type: 'warning'
                        });
                        $('.article-edition-button').prop('disabled', false);
                    } else {
                        swal({
                            title: 'Mensaje', 
                            text: response.message, 
                            type: 'success'
                        }).then(function() {
                            if (savingType == 1) {
                                window.location.href = url + '/edit-article/' + response.article_id;
                            }
                            else {
                                window.location.href = url + '/articles-list';
                            }
                        });
                    }
                },
                error: function(response) {
                    $.each(response.responseJSON.errors, function(key, value) {
                        swal({
                            title: 'Ups...', 
                            text: value, 
                            type: 'warning'
                        });
                    });
                    $('.article-edition-button').prop('disabled', false);
                }
            });

        });



    },

    success: function(file, response) {

        if (!response.success) {

            swal({

              title: 'Ups...', 

              text: response.message, 

              type: 'warning'

            });


            $('.article-edition-button').prop('disabled', false);

        } else {

            swal({

              title: 'Mensaje', 

              text: response.message, 

              type: 'success'

             })
            .then(function() {
                if (savingType == 1) {
                    window.location.href = url + '/edit-article/' + response.article_id;
                }

                if (savingType == 2) {
                    window.location.href = url + '/articles-list';
                }

            });

        }

    },

    error: function(file, xhr) {

        $.each(myDropzone.files, function(i, file) {

            file.status = Dropzone.QUEUED

        });

        $.each(xhr.errors, function(key, value) {

            swal({

              title: 'Ups...', 

              text: value, 

              type: 'warning'

            });

        });

        $('.article-edition-button').prop('disabled', false);
    }

});



Dropzone.on('thumbnail', function (file) {

    var cachedFilename = file.name;

    var blob = file.dataURL;

    var newFile = dataURItoBlob(blob);

    newFile.name = cachedFilename;

    updateMultimediaTable(newFile, blob, 'image');

});



Dropzone.on('addedfile', function(file) {

    var rowQ = $('#multimedia_gallery').children('tbody').find('tr').length;

    var rowN = (accepted_multimedia - (accepted_multimedia - rowQ));

    if(rowN == accepted_multimedia){

        Dropzone.removeFile(file);

        swal({

            title: 'Alerta', 

            text: 'Ha alcanzado el límite de items en la galería multimedia', 

            type: 'warning'

        });

    }

});



function dataURItoBlob(dataURI) {

    var byteString = atob(dataURI.split(',')[1]);

    var ab = new ArrayBuffer(byteString.length);

    var ia = new Uint8Array(ab);

    for (var i = 0; i < byteString.length; i++) {

        ia[i] = byteString.charCodeAt(i);

    }

    return new Blob([ab], { type: 'image/jpeg' });

}



function updateMultimediaTable(file, fileSrc, type, existingImageId = null, existingAltText = null) {

    var table = $('#multimedia_gallery');

    var rowQ = table.children('tbody').find('tr').length;

    var rowN = (accepted_multimedia - (accepted_multimedia - rowQ));

    var html = '';

    if (rowN < accepted_multimedia) {

        $('#multimedia_gallery').show(); 

        if (type == 'image') {

            if(fileSrc) {

                html = '<tr id="galleryrow_'+rowN+'">';

                html += '<td><img src="'+fileSrc+'" class="property-images-edit" style="width: 60px"></td>';

                // If this is an existing image, add data-id and use existing alt_text
                if (existingImageId) {
                    html += '<td><input type="hidden" name="multimedia_gallery['+rowN+'][filename]" value="'+file.name+'" data-id="'+existingImageId+'"/>Imagen <div class="form-group"><label for="multimedia_gallery['+rowN+'][file_alternative_text]">Texto alternativo</label><input type="text" name="multimedia_gallery['+rowN+'][file_alternative_text]"  class="form-control" value="'+(existingAltText || 'El Hondo')+'"></div></td>';
                } else {
                    html += '<td><input type="hidden" name="multimedia_gallery['+rowN+'][filename]" value="'+file.name+'"/>Imagen <div class="form-group"><label for="multimedia_gallery['+rowN+'][file_alternative_text]">Texto alternativo</label><input type="text" name="multimedia_gallery['+rowN+'][file_alternative_text]"  class="form-control" value="El Hondo"></div></td>';
                }

                html += '<td class="align-content: middle"><button type="button" class="btn btn-custom deleteRow" data-type="image" data-row="'+rowN+'"><i class="fa fa-trash-o"></i></button></td>';
                
                html += '</tr>';

                table.append(html);

                setTimeout(function() { 

                    $('#myDropzone .dz-preview.dz-image-preview').hide(); 

                }, 500);

                $('#myDropzone .dz-message').show();

                $('.deleteRow').on("click", function() {

                   
                    var btn = $(this);

                    deleteRow(btn);

                });

            }   

        } 

    } else {

        swal({

            title: 'Alerta', 

            text: 'Ha alcanzado el límite de items en la galería multimedia', 

            type: 'warning'

        });

    }

}



function deleteRow(btn){
    
    if (btn.attr('data-type') == 'image') {
        var img = btn.parent().parent().find("input[name*='[filename]']").val();
        var myFiles = Dropzone.getQueuedFiles();
        
        $.each(myFiles, function() {
            if (this.name == img) {
                Dropzone.removeFile(this);
            }
        });
        
        // Check if this is an existing image (has data-id) or new image
        var imageId = btn.parent().parent().find("input[name*='[filename]']").attr('data-id');
        
        console.log('Deleting image with ID:', imageId);
        if (imageId && imageId !== 'undefined') {
            // This is an existing image - add to deletedMultimedia
            deletedMultimedia.push(imageId);
            console.log('Added to deletedMultimedia:', imageId, 'Array now:', deletedMultimedia);
        } else {
            console.log('This is a new image, not adding to deletedMultimedia');
        }
        
        btn.parent().parent().remove();
        
    } 
    
    if ($('#multimedia_gallery').children('tbody').find('tr').length <= 0) {
        $('#multimedia_gallery').hide(); 
    }
}

var sortedMG = $('#multimedia_gallery').sortable({

    items:'tbody tr',

    cursor: 'move',

    containment: '#multimedia_gallery',

    distance: 20,

    tolerance: 'pointer',

    helper: function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width());
        });
        
        return $helper;
    },

});







$(document).ready(function() {
    // Initialize CKEditor 4 for body textarea
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('body', {
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                { name: 'insert', items: ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'] },
                '/',
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
            ],
            height: 300,
            removeButtons: 'Image,Flash,Iframe',
            removePlugins: 'image,iframe',
            allowedContent: {
                $1: {
                    elements: {
                        img: false,
                        iframe: false,
                        embed: false,
                        object: false
                    }
                }
            }
        });
        console.log('CKEditor initialized for body');
    } else {
        console.log('CKEditor not available');
    }

    // Initialize tag selection functionality
    $("#tag-select").on("change", function() {
        const selectedTagId = $(this).val();
        const selectedTagText = $(this).find("option:selected").text();
        
        if (selectedTagId && selectedTagId !== '') {
            // Add tag to selected tags list
            addSelectedTag(selectedTagId, selectedTagText);
            
            // Disable the selected option
            $(this).find("option:selected").prop('disabled', true);
            
            // Reset select to first option
            $(this).val('');
            
            // Update hidden input with all selected tag IDs
            updateSelectedTagsInput();
        }
    });

    // Auto-generate meta fields from article title
    $('#article-title').on('input', function() {
        var articleTitle = $(this).val();
        
        if (articleTitle.trim() !== '') {
            // Generate Meta Title
            var metaTitle = articleTitle + ' | Article | Smooth Sailing Yachts';
            $('#meta-title').val(metaTitle);
            
            // Generate URL Slug (replace spaces with hyphens and convert to lowercase)
            var urlSlug = articleTitle.toLowerCase().replace(/\s+/g, '-');
            $('#url-slug').val('/' + urlSlug);
            
            // Generate Meta Description
            var metaDescription = articleTitle + ' article of Smooth Sailing Yachts';
            $('#meta-description').val(metaDescription);
        } else {
            // Clear fields if article title is empty
            $('#meta-title').val('');
            $('#url-slug').val('');
            $('#meta-description').val('');
        }
    });

    // Handle publish date checkbox
    $('#publish-checkbox').on('change', function() {
        var publishDate = $('#publish-date');
        
        if (this.checked) {
            publishDate.prop('disabled', false);
        } else {
            publishDate.prop('disabled', true);
            publishDate.val('');
        }
    });

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
    
    // Load existing images if editing (this should be called from the view with existingImages data)
    if (typeof existingImages !== 'undefined' && existingImages.length > 0) {
        loadExistingImages(existingImages);
    }
});

// Function to get all selected tags
function getSelectedTags() {
    const tags = [];
    $("#selected-tags .tag-item").each(function() {
        tags.push({
            id: $(this).data('tag-id'),
            name: $(this).text().replace('×', '').trim() // Remove the × button text
        });
    });
    return tags;
}

// Function to add a selected tag to the list
function addSelectedTag(tagId, tagText) {
    const selectedTagsContainer = $("#selected-tags");
    
    const tagHtml = `
        <span class="tag-item" data-tag-id="${tagId}">
            ${tagText}
            <button type="button" class="remove-tag" onclick="removeSelectedTag(this, '${tagId}')">×</button>
        </span>
    `;
    
    selectedTagsContainer.append(tagHtml);
}

// Function to remove a selected tag
function removeSelectedTag(button, tagId) {
    // Remove the tag from the list
    $(button).closest('.tag-item').remove();
    
    // Re-enable the option in the dropdown
    $("#tag-select").find(`option[value="${tagId}"]`).prop('disabled', false);
    
    // Update hidden input
    updateSelectedTagsInput();
}

// Function to update the hidden input with all selected tag IDs
function updateSelectedTagsInput() {
    const selectedTagIds = [];
    $("#selected-tags .tag-item").each(function() {
        selectedTagIds.push($(this).data('tag-id'));
    });
    
    // Update hidden input (create if it doesn't exist)
    let hiddenInput = $("#selected-tags-input");
    if (hiddenInput.length === 0) {
        hiddenInput = $('<input type="hidden" name="selected_tags" id="selected-tags-input">');
        $("#article-registration-form").append(hiddenInput);
    }
    
    hiddenInput.val(selectedTagIds.join(','));
}

// Function to load existing images for editing
function loadExistingImages(existingImages) {
    if (!existingImages || existingImages.length === 0) return;
    
    existingImages.forEach(function(image) {
        // Create a mock file object for updateMultimediaTable
        var mockFile = {
            name: image.source
        };
        
        // Call updateMultimediaTable with existing image data
        updateMultimediaTable(mockFile, image.thumbnail_url, 'image', image.image_id, image.alt_text);
    });
    
    $('#multimedia_gallery').show();
}

// Function to update row numbers after deletion
function updateRowNumbers() {
    $('#multimedia_gallery tbody tr').each(function(index) {
        var newRowId = 'galleryrow_' + index;
        $(this).attr('id', newRowId);
        
        // Update all input names with new index
        $(this).find('input[name*="multimedia_gallery["]').each(function() {
            var oldName = $(this).attr('name');
            var newName = oldName.replace(/multimedia_gallery\[\d+\]/, 'multimedia_gallery[' + index + ']');
            $(this).attr('name', newName);
        });
        
        // Update delete button data-row
        $(this).find('.deleteRow').attr('data-row', index);
    });
}


$(document).on("click", ".deleteRow", function() {
    var btn = $(this);
    deleteRow(btn);
});