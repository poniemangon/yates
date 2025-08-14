@include ('backend.layouts.head')

@include('backend.layouts.menu')

<div class="main-content">

    <div class="container-fluid">

        <div class="row">

            <div class="page-title col-md-6">

                <h4 class="section-title">Articles <span class="total-section-title-content">(Total registered: {{ $totalArticles }})</span></h4>

            </div> 

            <div class="text-right col-md-6">

                <a href="{{ route('register-article') }}" class="btn btn-custom btn-rounded">New article</a> 

            </div> 

        </div>

        <div class="row">

            <div class="col-lg-9">

                <a class="mb-3 d-inline-block" data-toggle="collapse" href="#collapsable-articles-content" role="button" aria-expanded="false" aria-controls="collapsable-articles-content">Filter articles</a>

            </div>

        </div>

        <div class="collapse @if ($filtersParameters['search'] != '') show @endif" id="collapsable-articles-content">

            <div class="card card-body">

                <form id="article-search-form" method="get">

                    <div class="row">
                        
                        <div class="col-lg-4">
                            <label>Search by title, description or content</label>
                            <input type="text" class="form-control" name="search" autocomplete="off" value="{{ $filtersParameters['search'] ?? '' }}" placeholder="Enter search term...">
                        </div>

                        <div class="col-lg-3 property-abm">
                            <label>Category</label>
                            <select class="form-control" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->category_id }}" {{ ($filtersParameters['category'] ?? '') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Publication Date From</label>
                            <input type="date" class="form-control" name="date_from" value="{{ $filtersParameters['date_from'] ?? '' }}">
                        </div>

                        <div class="col-lg-2">
                            <label>Publication Date To</label>
                            <input type="date" class="form-control" name="date_to" value="{{ $filtersParameters['date_to'] ?? '' }}">
                        </div>

                        <!-- <div class="col-lg-3 property-abm">
                            <label>Tags</label>
                            <select class="form-control" name="tags">
                                <option value="">All Tags</option>
                                @foreach($tags ?? [] as $tag)
                                    <option value="{{ $tag->tag_id }}" {{ ($filtersParameters['tags'] ?? '') == $tag->tag_id ? 'selected' : '' }}>
                                        {{ $tag->tag_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> -->

                    </div>

                    <div class="row mt-3">

                        <div class="col-lg-6">

                            <button type="submit" class="btn btn-custom btn-sm">Filter</button>

                            <a href="{{ url('/ssy-administration/articles-list') }}" class="btn btn-default btn-sm text-white ml-1">Clean</a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        <div class="row">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-block">

                        <div class="table-overflow">

                            @if (count($articles) <= 0)

                                <h3 class="text-center not-found">Not registered articles found</h3>

                            @else

                                <table id="dt-opt" class="table table-lg table-hover">

                                    <thead>

                                        <tr>

                                            <!-- <th>Plan</th> -->

                                            <th>Title</th>

                                            <th>Excerpt</th>

                                            <th>Category</th>

                                            <th>Tags</th>

                                            <th>Publish Date</th>

                                            <th class="text-right">Actions</th>

                                        </tr>

                                    </thead>

                                    <tbody class="tbody">

                                        @foreach ($articles as $article)

                                            <tr>
                                              

                                                <td>{{ $article->title }}</td>

                                                <td>{{ $article->excerpt }}</td>

                                                <td>{{ $article->category_name }}</td>

                                                <td>
                                                    @foreach($article->article_tags as $tag)
                                                        <span class="badge badge-primary">{{ $tag->tag_name }}</span>
                                                    @endforeach
                                                </td>

                                                <td>{{ $article->publish_date }}</td>

                                                <td class="text-right">

                                                    <a href="{{ route('edit-article', ['articleId' => $article->article_id]) }}"><i class="fa fa-edit" title="Edit" data-toggle="tooltip" data-placement="top"></i></a>

                                                    |

                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#delete-article-{{ $article->article_id }}"><i class="ei-garbage-2" title="Delete" data-toggle="tooltip" data-placement="top"></i></a>

                                                    <div class="modal fade" id="delete-article-{{ $article->article_id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                                                        <div class="modal-dialog text-left">

                                                            <div class="modal-content">

                                                                <div class="modal-header">Are you sure you wish to delete this article?</div>

                                                                <div class="modal-body">

                                                                    This action cannot be undone.

                                                                </div>

                                                                <div class="modal-footer">

                                                                   <button data-article-id="{{ $article->article_id }}" class="btn btn-custom delete-article-button">Delete</button>

                                                                   <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            @endif

                        </div>

                    </div>

                </div>

                @if (count($articles) > 0) 

                    <div class="mt-3 d-flex flex-row justify-content-between">

                        <p class="text-muted">Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} articles</p>

                        <div class="custom-paginator">

                            {{ $articles->appends(request()->input())->links("pagination::bootstrap-4") }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@include('backend.layouts.footer')

@include('backend.layouts.scripts')