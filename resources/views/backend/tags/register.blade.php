@include('backend.layouts.head')
@include('backend.layouts.menu')
<div class="main-content property-abm">
    <div class="container-fluid">
        <div class="row">
            <div class="page-title col-md-6">
                <h4 class="section-title">Tags <span class="total-section-title-content">Create a new tag</span></h4>
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
                                <form method="post" action="{{ route('register-tag') }}" id="tag-registration-form">
                                    @csrf
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
                                                <input type="text" name="tag_name" id="tag-name" class="form-control" placeholder="Enter tag name">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Meta Title</label>
                                                <input type="text" name="meta_title" id="meta-title" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>URL Slug</label>
                                                <input type="text" name="url_slug" id="url-slug" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Meta Description</label>
                                                <textarea name="meta_description" id="meta-description" class="form-control" rows="3" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="text-right mrg-top-5">
                                                <a class="btn btn-custom btn-rounded mb-30" href="{{ route('tags-list') }}">Cancel</a>
                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 tag-registration-button" data-saving-type="1">Save and keep editing</button>
                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 tag-registration-button" data-saving-type="2">Save</button>
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
