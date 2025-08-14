@include('backend.layouts.head')
@include('backend.layouts.menu')
<div class="main-content property-abm">
    <div class="container-fluid">
        <div class="row">
            <div class="page-title col-md-6">
                <h4 class="section-title">Articles <span class="total-section-title-content">Edit article: {{ $articleData->title }}</span></h4>
            </div>
            <div class="text-right col-md-6">
                <a href="{{ route('articles-list') }}" class="btn btn-default btn-rounded">Back</a>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card maxed-content">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12 ml-auto mr-auto">
                                <form method="post" action="{{ route('edit-article', ['articleId' => $articleData->article_id]) }}" id="article-edition-form">
                                    @csrf
                                    <div class="content">
                                        <div class="row row-title-div mb-0">
                                            <div class="col-md-12">
                                                <h3 class="heading-title mb-0"><i>* Required information</i></h3>
                                            </div>
                                        </div>
                                        <div class="row row-title-div">
                                            <div class="col-md-12">
                                                <h3 class="heading-title">Article information</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Title *</label>
                                                <input type="text" name="title" id="article-title" class="form-control" placeholder="Enter article title" value="{{ $articleData->title ?? '' }}">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Category *</label>
                                                <select name="category_id" id="category_id" class="form-control">
                                                    <option value="">Select a category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->category_id }}" {{ ($articleData->category_id == $category->category_id) ? 'selected' : '' }}>
                                                            {{ $category->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label>Tag *</label>
                                                <select name="tag_id" id="tag-select" class="form-control">
                                                    <option value="">Select a tag</option>
                                                    @foreach($tags as $tag)
                                                        <option value="{{ $tag->tag_id }}" {{ in_array($tag->tag_id, $articleTags->pluck('tag_id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $tag->tag_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="selected-tags" class="mt-2">
                                                    @foreach($tags as $tag)
                                                        @if(in_array($tag->tag_id, $articleTags->pluck('tag_id')->toArray()))
                                                            <span class="tag-item" data-tag-id="{{ $tag->tag_id }}">
                                                                {{ $tag->tag_name }}
                                                                <button type="button" class="remove-tag" onclick="removeSelectedTag(this, '{{ $tag->tag_id }}')">×</button>
                                                            </span> 
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <input type="hidden" name="selected_tags[]" id="selected-tags-input">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Excerpt</label>
                                                <textarea name="excerpt" id="excerpt" class="form-control" rows="4" placeholder="Enter article excerpt">{{ $articleData->excerpt ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Body *</label>
                                                <textarea name="body" id="article-body" class="form-control">{{ $articleData->body ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row row-title-div">
                                            <div class="col-md-12">
                                                <h3 class="heading-title">Multimedia Gallery</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="mb-2">* The first image will be the main one</small>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="myDropzone" class="dropzone"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 p-3">
                                                <table id="multimedia_gallery" class="table table-bordered mt-3" {{ count($existingImages) > 0 ? '' : 'style=display:none' }}>
                                                    <thead>
                                                        <th>File</th>
                                                        <th>File Type</th>
                                                        <th>Actions</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($existingImages as $index => $image)
                                                            <tr id="galleryrow_{{ $index }}">
                                                                <td>
                                                                    <img src="{{ asset('public/backend/images/articles/' . $image->source) }}.webp" class="property-images-edit" style="width: 60px">
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="multimedia_gallery[{{ $index }}][filename]" value="{{ $image->source }}" data-id="{{ $image->image_id }}">
                                                                    Image 
                                                                    <div class="form-group">
                                                                        <label for="multimedia_gallery[{{ $index }}][file_alternative_text]">Alternative text</label>
                                                                        <input type="text" name="multimedia_gallery[{{ $index }}][file_alternative_text]" class="form-control" value="{{ $image->alt_text ?? 'Article Image' }}">
                                                                    </div>
                                                                </td>
                                                                <td class="align-content: middle">
                                                                    <button type="button" class="btn btn-custom deleteRow" data-type="image" data-row="{{ $index }}">
                                                                        <i class="fa fa-trash-o"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Meta Title</label>
                                                <input type="text" name="meta_title" id="meta-title" class="form-control" readonly value="{{ $articleData->meta_title ?? '' }}">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>URL Slug</label>
                                                <input type="text" name="url_slug" id="url-slug" class="form-control" readonly value="{{ $articleData->url_slug ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Meta Description</label>
                                                <textarea name="meta_description" id="meta-description" class="form-control" rows="3" readonly>{{ $articleData->meta_description ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="publish-checkbox" name="publish_checkbox" {{ $articleData->publish_date ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="publish-checkbox">
                                                        Program this article to be published on a specific date (if unspecified, it will be published immediately)
                                                    </label>
                                                </div>
                                                <label>Publish Date</label>
                                                <input type="date" name="publish_date" id="publish-date" class="form-control col-md-4" value="{{ $articleData->publish_date ? date('Y-m-d', strtotime($articleData->publish_date)) : '' }}" {{ $articleData->publish_date ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="text-right mrg-top-5">
                                                <a class="btn btn-custom btn-rounded mb-30" href="{{ route('articles-list') }}">Cancel</a>
                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 article-edition-button" data-saving-type="1">Save and keep editing</button>
                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 article-edition-button" data-saving-type="2">Save</button>
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

<script>
    // Set article ID for JavaScript
    var articleId = {{ $articleData->article_id }};
</script>

@include('backend.layouts.footer')
@include('backend.layouts.scripts')