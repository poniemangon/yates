<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

//libraries
use Hash;
use Session;
use Log;

//models
use App\Models\TagsModel;
use App\Models\ArticlesModel;
use App\Models\ArticleImagesModel;
use App\Models\ArticleTagsModel;


class TagsController extends Controller {

    public function login() {

 

    

        if (Session::has('loggedUser')) {
            return redirect('ssy-administration/tags-list');
        }
    
        $title = 'Tags | Login | SSY';
        return view('backend.tags.login', compact('title'));
    }
    
    
    public function list(Request $request) {

    	if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }
        
        $title = 'Tags | List | SSY';

        $totalTags = TagsModel::count();

        $tags = collect();
        $filterSource = false;

        if ($request->has('source') && $request->input('source') != '') {
            $filterSource = $request->input('source');
        }

        $tags = TagsModel::when($filterSource, function($query, $filterSource) {
            return $query->where('tag_name', 'LIKE', '%'.$filterSource.'%');
        })->groupBy('tag_id')->orderBy('tag_id', 'desc');

        $tags = $tags->get()->all();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;
        $currentItems = array_slice($tags, $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, count($tags), $perPage, $currentPage);

        $paginator->setPath(url('/') . '/ssy-administration/tags-list');
        $tags = $paginator;


        $filtersParameters = array(
            'source' => $filterSource
        );

        $scripts = array('tags.js');

        return view('backend.tags.list', compact('title', 'totalTags', 'tags', 'filtersParameters', 'scripts'));
    }

    public function register() {

    	if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }

    	$title = 'Tags | Register | SSY';

    	$scripts = array('tags.js');

        return view('backend.tags.register', compact('title', 'scripts'));
    }

    public function edit($tagId) {

        if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }

    	$title = 'Tags | Edition | SSY';

    	$tagData = TagsModel::where('tag_id', $tagId)->first();

    	if (!$tagData) {
    		return redirect('ssy-administration/tags-list');
    	}

    	$scripts = array('tags.js');

        return view('backend.tags.edit', compact('title', 'tagData', 'scripts'));
    }


    public function registerTag(Request $request) {
        Log::info($request->all());
        $messages = [
            'tag_name.required' => 'Name of the tag is required',
            'tag_name.max' => 'Name of the tag must contain less than 100 characters',
            'tag_name.min' => 'Name of the tag must contain at least 3 characters',
            'meta_title.required' => 'Meta title is required',
            'url_slug.required' => 'URL slug is required',
            'meta_description.required' => 'Meta description is required',
        ];

        $validations = $request->validate([
            'tag_name' => 'required|max:100|min:3',
            'meta_title' => 'required',
            'url_slug' => 'required',
            'meta_description' => 'required'
        ], $messages);

        $tagName = $request->input('tag_name');
        
        $metaTitle = $request->input('meta_title');
        $urlSlug = $request->input('url_slug');
        $metaDescription = $request->input('meta_description');

        $tagsModel = new TagsModel();
        $tagsModel->tag_name = $tagName;
        $tagsModel->meta_title = $metaTitle;
        $tagsModel->url_slug = $urlSlug;
        $tagsModel->meta_description = $metaDescription;
        $tagsModel->save();
       

        return Response()->json([
            'success' => true,
            'message' => 'Tag registered successfully',
            'tag_name' => $tagName,
            'url' => 'tags-list',
            'tag_id' => $tagsModel->tag_id
        ]);
    }

    public function editTag($tagId, Request $request) {
         
        $messages = [
            'tag_name.required' => 'Name of the tag is required',
            'tag_name.max' => 'Name of the tag must contain less than 100 characters',
            'tag_name.min' => 'Name of the tag must contain at least 3 characters',
            'meta_title.required' => 'Meta title is required',
            'url_slug.required' => 'URL slug is required',
            'meta_description.required' => 'Meta description is required',
        ];

        $validations = $request->validate([
            'tag_name' => 'required|max:100|min:3',
            'meta_title' => 'required',
            'url_slug' => 'required',
            'meta_description' => 'required'
        ], $messages);

        $tagName = $request->input('tag_name');
        $metaTitle = $request->input('meta_title');
        $urlSlug = $request->input('url_slug');
        $metaDescription = $request->input('meta_description');

        TagsModel::where('tag_id', $tagId)->update([
            'tag_name' => $tagName,
            'meta_title' => $metaTitle,
            'url_slug' => $urlSlug,
            'meta_description' => $metaDescription
	    ]);
                
        return Response()->json([
            'success' => true,
            'message' => 'Tag edited successfully',
            'tag_name' => $tagName,
            'url' => 'tags-list',
            'tag_id' => $tagId
        ]);
    }

    public function deleteTag($tagId, Request $request) {

    	if (!isset($tagId)) {
            return response()->json([
                'success' => false,
                'message' => 'Tag ID is invalid or inexistent'
            ]);
        }

        $tagData = TagsModel::where('tag_id', $tagId)->first();

        if (!$tagData) {
            return response()->json([
                'success' => false,
                'message' => 'Tag ID is invalid or inexistent'
            ]);
        }


        TagsModel::where('tag_id', $tagId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully'
        ]);
    }

    public function logout() {
    	auth()->logout();
        Session::flush();
        return Redirect('ssy-administration');
    }
}