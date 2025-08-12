// Global variables

var accepted_multimedia = 30;
var deletedMultimedia = new Array();

$(document).ready(function() {
    console.log('Articles.js loaded and ready');
    
    // Initialize CKEditor 4
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('article-body', {
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                '/',
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
            ],
            height: 300,
            removeButtons: 'Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe'
        });
        console.log('CKEditor initialized');
    } else {
        console.log('CKEditor not available');
    }

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
    console.log('Setting up publish checkbox handler');
    $('#publish-checkbox').on('change', function() {
        console.log('Checkbox changed, checked:', this.checked);
        var publishDate = $('#publish-date');
        console.log('Publish date field found:', publishDate.length > 0);
        
        if (this.checked) {
            publishDate.prop('disabled', false);
            console.log('Date field enabled');
            // Set today's date as default if no date is set
            if (!publishDate.val()) {
                var today = new Date();
                var yyyy = today.getFullYear();
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var dd = String(today.getDate()).padStart(2, '0');
                var todayString = yyyy + '-' + mm + '-' + dd;
                publishDate.val(todayString);
                console.log('Set default date to:', todayString);
            }
        } else {
            publishDate.prop('disabled', true);
            publishDate.val('');
            console.log('Date field disabled and cleared');
        }
    });

    // Enhanced tag selection functionality
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
});

// Initialize Dropzone for multimedia gallery
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone('#myDropzone', {
    url: url + '/edit-article/' + (typeof articleId !== 'undefined' ? articleId : ''),
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    acceptedFiles: 'image/jpg, image/png, image/jpeg',
    autoProcessQueue: false,
    dictDefaultMessage: 'Select files',
    uploadMultiple: true,
    maxFiles: 100,
    parallelUploads: 100,
    enctype: 'multipart/form-data',
    init: function() {
        dzClosure = this;
        
        // Article registration button handler
        $(document).on('click', '.article-registration-button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.article-registration-button').prop('disabled', true);
            var savingType = $(this).attr('data-saving-type');
            
            var formData = new FormData();
            formData.append('title', $('#article-title').val());
            formData.append('excerpt', $('#excerpt').val());
            formData.append('body', CKEDITOR.instances['article-body'].getData());
            formData.append('category_id', $('#category_id option:selected').val());
            formData.append('meta_title', $('#meta-title').val());
            formData.append('meta_description', $('#meta-description').val());
            formData.append('url_slug', $('#url-slug').val());
            formData.append('publish_date', $('#publish-date').val());
           
            var multimedia_gallery = new Array();
            var pos = 0;
            $.each($('#multimedia_gallery tbody tr'), function() {
                multimedia_gallery.push({
                    "filename": $(this).find('input[name="multimedia_gallery[][filename]"]').val(),
                    "file_alternative_text": $(this).find('input[name="multimedia_gallery[][file_alternative_text]"]').val(),
                    "position": pos,
                    "id": $(this).find('input[name="multimedia_gallery[][filename]"]').attr('data-id'),
                    "type": $(this).find('button.deleteRow').attr("data-type"),
                    "thumbnail": $(this).find('img.property-images-edit').attr("src"),
                    "tmp_name": $(this).find('input[name="multimedia_gallery[][tmp_name]"]').val(),
                    "code": $(this).find('input[name="multimedia_gallery[][code]"]').val(),
                    "video_type": $(this).find('input[name="multimedia_gallery[][code]"]').attr('data-video-type')
                });
                pos = pos + 1;
            });
            
            multimedia_gallery = JSON.stringify(multimedia_gallery);
            formData.append('multimedia_gallery', multimedia_gallery);
            formData.append('deleted_multimedia', deletedMultimedia);
            
            // Get selected tags
            const selectedTags = getSelectedTags();
            formData.append('tags', JSON.stringify(selectedTags));
            
            $.ajax({
                url: url + '/register-article',
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
                        $('.article-registration-button').prop('disabled', false);
                    } else {
                        swal({
                            title: 'Mensaje', 
                            text: response.message, 
                            type: 'success'
                        }).then(function() {
                            if (savingType == 1) {
                                window.location.href = url + '/edit-article/' + response.article_id;
                            }
                            if (savingType == 2) {
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
                    $('.article-registration-button').prop('disabled', false);
                }
            });
        });
        
        // Article edition button handler
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
            formData.append('publish_date', $('#publish-date').val());
            
            var multimedia_gallery = new Array();
            var pos = 0;
            $.each($('#multimedia_gallery tbody tr'), function() {
                multimedia_gallery.push({
                    "filename": $(this).find('input[name="multimedia_gallery[][filename]"]').val(),
                    "file_alternative_text": $(this).find('input[name="multimedia_gallery[][file_alternative_text]"]').val(),
                    "position": pos,
                    "id": $(this).find('input[name="multimedia_gallery[][filename]"]').attr('data-id'),
                    "type": $(this).find('button.deleteRow').attr("data-type"),
                    "thumbnail": $(this).find('img.property-images-edit').attr("src"),
                    "tmp_name": $(this).find('input[name="multimedia_gallery[][tmp_name]"]').val(),
                    "code": $(this).find('input[name="multimedia_gallery[][code]"]').val(),
                    "video_type": $(this).find('input[name="multimedia_gallery[][code]"]').attr('data-video-type')
                });
                pos = pos + 1;
            });
            
            multimedia_gallery = JSON.stringify(multimedia_gallery);
            formData.append('multimedia_gallery', multimedia_gallery);
            formData.append('deleted_multimedia', deletedMultimedia);
            
            // Get selected tags
            const selectedTags = getSelectedTags();
            formData.append('tags', JSON.stringify(selectedTags));
            
            $.ajax({
                url: url + '/edit-article/' + (typeof articleId !== 'undefined' ? articleId : ''),
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
                            if (savingType == 2) {
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
            }).then(function() {
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
            file.status = myDropzone.QUEUED
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

// Dropzone events
myDropzone.on('thumbnail', function (file) {
    var cachedFilename = file.name;
    var blob = file.dataURL;
    var newFile = dataURItoBlob(blob);
    newFile.name = cachedFilename;
    updateMultimediaTable(newFile, blob, 'image');
});

myDropzone.on('addedfile', function(file) {
    var rowQ = $('#multimedia_gallery').children('tbody').find('tr').length;
    var rowN = (accepted_multimedia - (accepted_multimedia - rowQ));
    if(rowN == accepted_multimedia){
        myDropzone.removeFile(file);
        swal({
            title: 'Alerta', 
            text: 'You have reached the limit of items in the multimedia gallery', 
            type: 'warning'
        });
    }
});

// Utility functions
function dataURItoBlob(dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: 'image/jpeg' });
}

function updateMultimediaTable(file, fileSrc, type) {
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
                html += '<td><input type="hidden" name="multimedia_gallery[][filename]" value="'+file.name+'"/>Image <div class="form-group"><label for="multimedia_gallery[][file_alternative_text]">Alternative text</label><input type="text" name="multimedia_gallery[][file_alternative_text]"  class="form-control" value="Article Image"></div></td>';
                html += '<td class="align-content: middle"><button type="button" class="btn btn-custom deleteRow" data-type="image"><i class="fa fa-trash-o"></i></button></td>';
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
            text: 'You have reached the limit of items in the multimedia gallery', 
            type: 'warning'
        });
    }
}

function deleteRow(btn){
    if (btn.attr('data-type') == 'image') {
        var img = btn.parent().parent().find("input[name='multimedia_gallery[][filename]']").val();
        var myFiles = myDropzone.getQueuedFiles();
        $.each(myFiles,function() {
            if(this.name == img) {
                myDropzone.removeFile(this);
            }
        });
        deletedMultimedia.push(btn.parent().parent().find("input[name='multimedia_gallery[][filename]']").attr("data-id"));
        btn.parent().parent().remove();
    }
    
    if ($('#multimedia_gallery').children('tbody').find('tr').length <= 0) {
        $('#multimedia_gallery').hide(); 
    }
}

// Initialize sortable for multimedia gallery
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

// Article registration function (for backward compatibility)
function registerArticle(action, method, data, savingType) {
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
            $('#article-registration-button').prop('disabled', true);
            $('#article-registration-button').text('Saving...');
        },
        success: function(resp) {
            if (resp.success) {
                swal({
                    title: 'Mensaje', 
                    text: resp.message, 
                    type: 'success'
                }).then(function() {
                    if (savingType == 1) {
                        window.location.href = url + '/edit-article/' + resp.article_id;
                    }
                    if (savingType == 2) {
                        window.location.href = url + '/articles-list';
                    }
                });
            } else {
                swal({
                    title: 'Ups...', 
                    text: resp.message, 
                    type: 'warning'
                });
                $('#article-registration-button').prop('disabled', false);
                $('#article-registration-button').text('Save');
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
            $('#article-registration-button').prop('disabled', false);
            $('#article-registration-button').text('Save');
        }
    });
}

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

