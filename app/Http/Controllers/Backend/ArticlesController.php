<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

//models
use App\Models\ArticlesModel;
use App\Models\CategoriesModel;
use App\Models\TagsModel;
use App\Models\ArticleImagesModel;
use App\Models\ArticleTagsModel;


//libraries
use Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use ImageOptimizer;
use Embed\Embed;
use Illuminate\Support\Facades\Session;

class ArticlesController extends Controller {

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (!Session::has('loggedUser')) {
                return Redirect('ssy-administration');
            } else {
                return $next($request);
            }
        });
    }
    
    
    public function list(Request $request) {

        $title = 'Articles | List | SSY';

        $totalArticles = ArticlesModel::count();

   
        $articles = ArticlesModel::orderBy('article_id', 'desc')->paginate(20);

       
        foreach ($articles as $article) {
            $tagIds = ArticleTagsModel::where('article_id', $article->article_id)->get(); 

            $article->article_tags = TagsModel::whereIn('tag_id', $tagIds->pluck('tag_id'))->get();

            $articleCategory = CategoriesModel::where('category_id', $article->category_id)->first();
            if ($articleCategory) {
                $article->category_name = $articleCategory->category_name;
                $article->category_slug = $articleCategory->url_slug;
            } else {
                $article->category_name = 'No Category';
                $article->category_slug = '';
            }
        }

       
        $filtersParameters = [
            'search' => $request->query('search', ''),
            'category' => $request->query('category', ''),
            'date_from' => $request->query('date_from', ''),
            'date_to' => $request->query('date_to', ''),
            'tags' => $request->query('tags', ''), 
        ];

      
        $query = ArticlesModel::query();

   
        if (!empty($filtersParameters['search'])) {
            $search = $filtersParameters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%");
                $q->orWhere('excerpt', 'like', "%$search%");
                $q->orWhere('body', 'like', "%$search%");
            });
        }

   
        if (!empty($filtersParameters['category'])) {
            $query->where('category_id', $filtersParameters['category']);
        }

        if(!empty($filtersParameters['date_from']) && !empty($filtersParameters['date_to']) && $filtersParameters['date_from'] > $filtersParameters['date_to']) {
            $filtersParameters['date_to'] = '';
        } 
        
        if (!empty($filtersParameters['date_from'])) {
            $query->where('publish_date', '>=', $filtersParameters['date_from']);
        }
        if (!empty($filtersParameters['date_to'])) {
            $query->where('publish_date', '<=', $filtersParameters['date_to']);
        }



        $articles = $query->paginate(20);

     
        foreach ($articles as $article) {
            $tagIds = ArticleTagsModel::where('article_id', $article->article_id)->get(); 

            $article->article_tags = TagsModel::whereIn('tag_id', $tagIds->pluck('tag_id'))->get();

            $articleCategory = CategoriesModel::where('category_id', $article->category_id)->first();
            if ($articleCategory) {
                $article->category_name = $articleCategory->category_name;
                $article->category_slug = $articleCategory->url_slug;
            } else {
                $article->category_name = 'No Category';
                $article->category_slug = '';
            }
        }

        $categories = CategoriesModel::orderBy('category_name', 'asc')->get();

        $tags = TagsModel::orderBy('tag_name', 'asc')->get();

        $scripts = array('articles.js');

    	return view('backend.articles.list', compact('title', 'totalArticles', 'articles', 'filtersParameters', 'categories', 'scripts', 'tags'));
    }

    public function register() {

    	$title = 'Articles | Register | SSY';

        $categories = CategoriesModel::orderBy('category_name', 'asc')->get();

        $tags = TagsModel::orderBy('tag_name', 'asc')->get();

    	$usesCKeditor = true;


    	$scripts = array('articles-registration.js');

    	return view('backend.articles.register', compact('title', 'categories', 'usesCKeditor', 'scripts', 'tags'));
    }

    public function edit($articleId) {

        $title = 'Articles | Register | SSY';

        $articleData = ArticlesModel::where('article_id', $articleId)->first();


        if (!$articleData) {
    		redirect('ssy-administration/articles-list');
    	}

    	$existingImages = ArticleImagesModel::where('article_id', $articleId)->orderBy('order_position', 'asc')->get();

        $categories = CategoriesModel::orderBy('category_id', 'asc')->get();

        $tags = TagsModel::orderBy('tag_name', 'asc')->get();

        $articleTags = ArticleTagsModel::where('article_id', $articleId)->get();

    	$usesCKeditor = true;

    	$scripts = array('articles-edition.js');
      

    	return view('backend.articles.edit', compact('title', 'articleData', 'existingImages', 'categories', 'articleId', 'usesCKeditor', 'scripts', 'tags', 'articleTags'));
    }

    public function registerArticle(Request $request) {
        Log::info($request->all());
        $messages = [
            'title.required' => 'You must enter the title of the article',
            'category_id.required' => 'You must select a category',
            'body.required' => 'You must select the body of the article',
            'excerpt.required' => 'You must enter the description of the article',
            'meta_title.required' => 'You must enter the meta title for the article page',
            'meta_description.required' => 'You must enter the meta description for the article page',
            'url_slug.required' => 'The URL slug for the article page is required',
            'url_slug.unique' => 'There is already a page of article with that URL slug registered'
        ];


        $validations = $request->validate([
            'title' => 'required',    
            'category_id' => 'required',
            'body' => 'required',
            'excerpt' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'url_slug' => 'required|unique:ssy_articles'
        ], $messages);

        $title = $request->input('title');
        $excerpt = $request->input('excerpt');
        $body = $request->input('body');
        $categoryId = $request->input('category_id');
        $metaTitle = $request->input('meta_title');
        $metaDescription = $request->input('meta_description');
        $urlSlug = $request->input('url_slug');
        $publishDate = $request->input('publish_date');
        $multimediaGallery = json_decode($request->input('multimedia_gallery'), true);
            
        $articleModel = new ArticlesModel;

        $articleModel->title = $title;
        $articleModel->excerpt = $excerpt;
        $articleModel->body = $body;
        $articleModel->category_id = $categoryId;
        $articleModel->meta_title = $metaTitle;
        $articleModel->meta_description = $metaDescription;
        $articleModel->url_slug = $urlSlug;
   
        if (empty($publishDate)) {
            $articleModel->publish_date = date('Y-m-d H:i:s');
        } else {
            $articleModel->publish_date = $publishDate;
        }
        $articleModel->save();
        $articleId = $articleModel->article_id;

      
        
        if ($request->has('selected_tags') && !empty($request->input('selected_tags'))) {
            $tags = json_decode($request->input('selected_tags'), true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (isset($tag['id']) && !empty($tag['id'])) {
                        $articleTag = new ArticleTagsModel();
                        $articleTag->article_id = $articleId;
                        $articleTag->tag_id = $tag['id'];
                        $articleTag->save();
                    }
                }
            }
        }

     

     
        
       
        $manager = new ImageManager(new Driver());

        foreach ($multimediaGallery as $key => $multimedia) {
                    $base64Image = $multimedia['thumbnail'];
        
            // Remover encabezado base64 si existe
            if (str_starts_with($base64Image, 'data:image')) {
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
            }
        
            $resizeImage = $manager->read(base64_decode($base64Image));
        
            $fileExtension = '.webp'; 
                    $destinationPath = public_path('backend/images/articles/');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

            $fileName = time() . '-' . $key;
                    $clearFileName = pathinfo($fileName, PATHINFO_FILENAME);

            file_put_contents(
                $destinationPath . $clearFileName . $fileExtension,
                $resizeImage->toWebp(90) 
            );
        
        
   
            $articleImage = new ArticleImagesModel();
            $articleImage->source = $fileName;
            $articleImage->order_position = $multimedia['position'];
            $articleImage->article_id = $articleId;
            $articleImage->alt_text = $multimedia['file_alternative_text'] ?: 'Article Image';
            $articleImage->save();
        }

  

        return response()->json([
            'success' => true,
            'message' => 'Article registered successfully',
            'article_id' => $articleId
        ]);
    }

    public function editArticle($articleId, Request $request) {
        Log::info($request->input('publish_date'));
        
    
        $messages = [
            'title.required' => 'You must enter the title of the article',
            'excerpt.required' => 'You must enter the excerpt of the article',
            'body.required' => 'You must enter the content of the article',
            'meta_title.required' => 'You must enter the meta title for the article page',
            'meta_description.required' => 'You must enter the meta description for the article page',
            'url_slug.required' => 'The URL slug for the article page is required',
            'category_id.required' => 'You must select a category',
            'url_slug.unique' => 'An article with that URL slug already exists',
            'publish_date.date_format' => 'The publication date must be in DD-MM-YYYY format'
        ];

        $validations = $request->validate([
            'title' => 'required',
            'excerpt' => 'required',
            'body' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'url_slug' => 'required|unique:ssy_articles,url_slug,'.$articleId.',article_id',
            'category_id' => 'required|exists:ssy_categories,category_id',
            'publish_date' => 'nullable|date_format:Y-m-d',
        ], $messages);

       
        $title = $request->input('title');
        $excerpt = $request->input('excerpt');
        $body = $request->input('body');
        $categoryId = $request->input('category_id');
        $metaTitle = $request->input('meta_title');
        $metaDescription = $request->input('meta_description');
        $urlSlug = $request->input('url_slug');
        $publishDate = $request->input('publish_date');

      
        $article = ArticlesModel::find($articleId);
        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        $article->title = $title;
        $article->excerpt = $excerpt;
        $article->body = $body;
        $article->category_id = $categoryId;
        $article->meta_title = $metaTitle;
        $article->meta_description = $metaDescription;
        $article->url_slug = $urlSlug;
        $article->publish_date = $publishDate ?: date('Y-m-d');
        $article->save();

      
        if ($request->has('selected_tags') && !empty($request->input('selected_tags'))) {
            
            ArticleTagsModel::where('article_id', $articleId)->delete();
            
            
            $tags = json_decode($request->input('selected_tags'), true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (isset($tag['id']) && !empty($tag['id'])) {
                        $articleTag = new ArticleTagsModel();
                        $articleTag->article_id = $articleId;
                        $articleTag->tag_id = $tag['id'];
                        $articleTag->save();
                    }
                }
            }
        }

        
        $multimediaGallery = $request->input('multimedia_gallery');
        if (is_string($multimediaGallery)) {
            $multimediaGallery = json_decode($multimediaGallery, true);
        }
        
     
        $deletedMultimedia = ($request->input('deleted_multimedia') != '') ? explode(',', $request->input('deleted_multimedia')) : array();
        if (!empty($deletedMultimedia)) {  
           
            $deletedImages = ArticleImagesModel::whereIn('image_id', $deletedMultimedia)->get();
            
            foreach ($deletedImages as $deletedImage) {
                $baseFileName = $deletedImage->source;
                $filePath = public_path('backend/images/articles/' . $baseFileName . '.webp');
                
               
                if (file_exists($filePath)) unlink($filePath);

            }
            
           
            ArticleImagesModel::whereIn('image_id', $deletedMultimedia)->delete();
        }

        if (is_array($multimediaGallery) && !empty($multimediaGallery)) {
          
            $existingImages = ArticleImagesModel::where('article_id', $articleId)->get();
            $existingImageIds = $existingImages->pluck('image_id')->toArray();
            
         
            $currentImageIds = [];
            
      
            $manager = new ImageManager(new Driver());
            
            foreach ($multimediaGallery as $key => $multimedia) {
                if (isset($multimedia['id']) && !empty($multimedia['id'])) {
                 
                    $imageId = $multimedia['id'];
                    $currentImageIds[] = $imageId;
                    
                    ArticleImagesModel::where('image_id', $imageId)->update([
                        'order_position' => $multimedia['position'],
                        'alt_text' => $multimedia['file_alternative_text'] ?: 'Article Image'
                    ]);
                    
                } elseif (isset($multimedia['thumbnail']) && !empty($multimedia['thumbnail'])) {
                   
                    $base64Image = $multimedia['thumbnail'];
                    
                    
                    if (str_starts_with($base64Image, 'data:image')) {
                        $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
                    }
                    
                    $resizeImage = $manager->read(base64_decode($base64Image));
                    $fileExtension = '.webp'; 
            $destinationPath = public_path('backend/images/articles/');
                    
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

                    $fileName = time() . '-' . $key;
                    $clearFileName = pathinfo($fileName, PATHINFO_FILENAME);
                    
                    file_put_contents(
                        $destinationPath . $clearFileName . $fileExtension,
                        $resizeImage->toWebp(90)
                    );
                    
                    
                    
                
            $articleImage = new ArticleImagesModel();
            $articleImage->source = $fileName;
                    $articleImage->order_position = $multimedia['position'];
            $articleImage->article_id = $articleId;
                    $articleImage->alt_text = $multimedia['file_alternative_text'] ?: 'Article Image';
            $articleImage->save();
                }
               
            }
            
            
            $imagesToDelete = array_diff($existingImageIds, $currentImageIds);
            if (!empty($imagesToDelete)) {
            
                $deletedImages = ArticleImagesModel::whereIn('image_id', $imagesToDelete)->get();
                
                foreach ($deletedImages as $deletedImage) {
                    $baseFileName = $deletedImage->source;
                    $filePath = public_path('backend/images/articles/' . $baseFileName . '.webp');
                    
                   
                    if (file_exists($filePath)) unlink($filePath);
                }
                
             
                ArticleImagesModel::whereIn('image_id', $imagesToDelete)->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'article_id' => $articleId
        ]);
    }

    public function deleteArticle($articleId) { 

        $article = ArticlesModel::find($articleId);
        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }
        $articleImages = ArticleImagesModel::where('article_id', $articleId)->get();
        foreach ($articleImages as $articleImage) {
            $imagePath = public_path('backend/images/articles/' . $articleImage->source . '.webp');
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $article->delete();

        ArticleTagsModel::where('article_id', $articleId)->delete();


        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }

}