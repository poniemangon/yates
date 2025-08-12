@include('backend.layouts.head')
@include('backend.layouts.menu')
<div class="main-content property-abm">
    <div class="container-fluid">
        <div class="row">
            <div class="page-title col-md-6">
                <h4 class="section-title">Tags <span class="total-section-title-content">List of tags</span></h4>
            </div>
            <div class="text-right col-md-6">
                <a href="{{ route('register-tag') }}" class="btn btn-custom btn-rounded">Create new tag</a>
            </div> 
        </div>
        

        
        <div class="row">

            <div class="col-lg-9">

                <a class="mb-3 d-inline-block" data-toggle="collapse" href="#collapsable-tags-content" role="button" aria-expanded="false" aria-controls="collapsable-tags-content">Filter tags</a>

            </div>

            </div>

            <div class="collapse @if ($filtersParameters['source'] != '') show @endif" id="collapsable-tags-content">

            <div class="card card-body">

                <form id="tag-search-form" method="get">

                    <div class="row">
                        
                        <div class="col-lg-6">

                            <label>Search tags by name</label>
                            
                            <input type="text" class="form-control" name="source" autocomplete="off" value="{{ $filtersParameters['source'] }}">

                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-lg-3">

                            <button type="submit" class="btn btn-custom btn-sm">Filter</button>

                            <a href="{{ route('tags-list') }}" class="btn btn-default btn-sm text-white ml-1">Clean</a>

                        </div>

                    </div>

                </form>

            </div>

            </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card maxed-content">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tag Name</th>
                                                <th>Meta Title</th>
                                                <th>URL Slug</th>
                                                <th>Meta Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tags as $tag)
                                            <tr>
                                                
                                                <td>{{ $tag->tag_name }}</td>
                                                <td>{{ $tag->meta_title }}</td>
                                                <td>{{ $tag->url_slug }}</td>
                                                <td>{{ $tag->meta_description }}</td>
                                                <td class="text-right">
                                                    <a href="{{ route('edit-tag', $tag->tag_id) }}"><i class="fa fa-edit" title="Edit" data-toggle="tooltip" data-placement="top"></i></a>
                                                    |
                                                    <a href="javascript:void(0)" class="delete-tag-button" data-tag-id="{{ $tag->tag_id }}"><i class="ei-garbage-2" title="Delete" data-toggle="tooltip" data-placement="top"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($tags->hasPages())
                                    <div class="pagination-wrapper">
                                        {{ $tags->appends($filtersParameters)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.selected-tags-container {
    border: 2px solid #ddd;
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 15px;
    min-height: 60px;
    background-color: #e9ecef;
}

.tag-item {
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    margin: 2px;
    border-radius: 15px;
    font-size: 14px;
    position: relative;
}

.remove-tag {
    background: none;
    border: none;
    color: white;
    font-weight: bold;
    margin-left: 8px;
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
}

.remove-tag:hover {
    color: #ffc107;
}
</style>

@include('backend.layouts.footer')
@include('backend.layouts.scripts')