@include('backend.layouts.head')
@include('backend.layouts.menu')
<div class="main-content property-abm">
    <div class="container-fluid">
        <div class="row">
            <div class="page-title col-md-6">
                <h4 class="section-title">Tags <span class="total-section-title-content">Edit tag</span></h4>
            </div>
            <div class="text-right col-md-6">
                <a href="{{ route('tags-list') }}" class="btn btn-default btn-rounded">Back</a>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card maxed-content">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12 ml-auto mr-auto">
                                <form method="post" action="{{ route('edit-tag', $tagData->tag_id) }}" id="tag-edit-form">
                                    @csrf
                                    @method('POST')
                                    <div class="spanish-content">
                                        <div class="row row-title-div mb-0">
                                            <div class="col-md-12">
                                                <h3 class="heading-title mb-0"><i>* Required information</i></h3>
                                            </div>
                                        </div>
                                        <div class="row row-title-div">
                                            <div class="col-md-12">
                                                <h3 class="heading-title">Tag information</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Tag Name *</label>
                                                <input type="text" name="tag_name" id="tag-name" class="form-control" placeholder="Enter tag name" value="{{ $tagData->tag_name ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Meta Title</label>
                                                <input type="text" name="meta_title" id="meta-title" class="form-control" readonly value="{{ $tagData->meta_title ?? '' }}">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>URL Slug</label>
                                                <input type="text" name="url_slug" id="url-slug" class="form-control" readonly value="{{ $tagData->url_slug ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Meta Description</label>
                                                <textarea name="meta_description" id="meta-description" class="form-control" rows="3" readonly>{{ $tagData->meta_description ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="text-right mrg-top-5">
                                                <a class="btn btn-custom btn-rounded mb-30" href="{{ route('tags-list') }}">Cancel</a>
                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 tag-edit-button" data-saving-type="1">Update and keep editing</button>
                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 tag-edit-button" data-saving-type="2">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.layouts.footer')
@include('backend.layouts.scripts')

<script>
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
</script>