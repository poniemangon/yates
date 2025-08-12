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
use App\Models\CategoriesModel;
use App\Models\ArticlesModel;
use App\Models\TagsModel;
use App\Models\ArticleImagesModel;
use App\Models\ArticleTagsModel;


class CategoriesController extends Controller {

    public function login() {

 
    

        if (Session::has('loggedUser')) {
            return redirect('ssy-administration/categories-list');
        }
    
        $title = 'Categories | Login | SSY';
        return view('backend.categories.login', compact('title'));
    }
    
    
    public function list(Request $request) {

    	if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }
        
        $title = 'Categories | List | SSY';

        $totalCategories = CategoriesModel::count();

        $categories = collect();
        $filterSource = false;

        if ($request->has('source') && $request->input('source') != '') {
            $filterSource = $request->input('source');
        }

        $categories = CategoriesModel::when($filterSource, function($query, $filterSource) {
            return $query->where('category_name', 'LIKE', '%'.$filterSource.'%');
        })->groupBy('category_id')->orderBy('category_id', 'desc');

        $categories = $categories->get()->all();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;
        $currentItems = array_slice($categories, $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, count($categories), $perPage, $currentPage);

        $paginator->setPath(url('/') . '/ssy-administration/categories-list');
        $categories = $paginator;


        $filtersParameters = array(
            'source' => $filterSource
        );

        $scripts = array('categories.js');

        return view('backend.categories.list', compact('title', 'totalCategories', 'categories', 'filtersParameters', 'scripts'));
    }

    public function register() {

    	if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }

    	$title = 'Categories | Register | SSY';

    	$scripts = array('categories.js');

        return view('backend.categories.register', compact('title', 'scripts'));
    }

    public function edit($categoryId) {

        if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }

    	$title = 'Categories | Edition | SSY';

    	$categoryData = CategoriesModel::where('category_id', $categoryId)->first();

    	if (!$categoryData) {
    		return redirect('ssy-administration/categories-list');
    	}

    	$scripts = array('categories.js');

        return view('backend.categories.edit', compact('title', 'categoryData', 'scripts'));
    }


    public function registerCategory(Request $request) {
        Log::info($request->all());
        $messages = [
            'category_name.required' => 'Name of the category is required',
            'category_name.max' => 'Name of the category must contain less than 100 characters',
            'category_name.min' => 'Name of the category must contain at least 3 characters',
            'meta_title.required' => 'Meta title is required',
            'url_slug.required' => 'URL slug is required',
            'meta_description.required' => 'Meta description is required',
        ];

        $validations = $request->validate([
            'category_name' => 'required|max:100|min:3',
            'meta_title' => 'required',
            'url_slug' => 'required',
            'meta_description' => 'required'
        ], $messages);

        $categoryName = $request->input('category_name');
        
        $metaTitle = $request->input('meta_title');
        $urlSlug = $request->input('url_slug');
        $metaDescription = $request->input('meta_description');

        $categoriesModel = new CategoriesModel();
        $categoriesModel->category_name = $categoryName;
        $categoriesModel->meta_title = $metaTitle;
        $categoriesModel->url_slug = $urlSlug;
        $categoriesModel->meta_description = $metaDescription;
        $categoriesModel->save();
       

        return Response()->json([
            'success' => true,
            'message' => 'Category registered successfully',
            'category_name' => $categoryName,
            'url' => 'categories-list',
            'category_id' => $categoriesModel->category_id
        ]);
    }

    public function editCategory($categoryId, Request $request) {
         
        $messages = [
            'category_name.required' => 'Name of the category is required',
            'category_name.max' => 'Name of the category must contain less than 100 characters',
            'category_name.min' => 'Name of the category must contain at least 3 characters',
            'meta_title.required' => 'Meta title is required',
            'url_slug.required' => 'URL slug is required',
            'meta_description.required' => 'Meta description is required',
        ];

        $validations = $request->validate([
            'category_name' => 'required|max:100|min:3',
            'meta_title' => 'required',
            'url_slug' => 'required',
            'meta_description' => 'required'
        ], $messages);

        $categoryName = $request->input('category_name');
        $metaTitle = $request->input('meta_title');
        $urlSlug = $request->input('url_slug');
        $metaDescription = $request->input('meta_description');

        CategoriesModel::where('category_id', $categoryId)->update([
            'category_name' => $categoryName,
            'meta_title' => $metaTitle,
            'url_slug' => $urlSlug,
            'meta_description' => $metaDescription
	    ]);
                
        return Response()->json([
            'success' => true,
            'message' => 'Category edited successfully',
            'category_name' => $categoryName,
            'url' => 'categories-list',
            'category_id' => $categoryId
        ]);
    }

    public function deleteCategory($categoryId, Request $request) {

    	if (!isset($categoryId)) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is invalid or inexistent'
            ]);
        }

        $categoryData = CategoriesModel::where('category_id', $categoryId)->first();

        if (!$categoryData) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is invalid or inexistent'
            ]);
        }


        CategoriesModel::where('category_id', $categoryId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function logout() {
    	auth()->logout();
        Session::flush();
        return Redirect('ssy-administration');
    }
}