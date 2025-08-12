<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

//models
use App\Models\ArticlesModel;
use App\Models\CategoriesModel;
use App\Models\TagsModel;
use App\Models\ArticleImagesModel;
use App\Models\ArticleTagsModel;

//libraries
use Auth;
use Image;
use Response;


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

        $articles = collect();
        $filterSource = false;

        if ($request->has('source') && $request->input('source') != '') {
            $filterSource = $request->input('source');
        }

        $articles = ArticlesModel::when($filterSource, function($query, $filterSource) {
            return $query->where('title', 'LIKE', '%'.$filterSource.'%');
        })->groupBy('article_id')->orderBy('article_id', 'desc');

        $articles = $articles->get()->all();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;
        $currentItems = array_slice($articles, $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, count($articles), $perPage, $currentPage);

        $paginator->setPath(url('/') . '/ssy-administration/articles-list');
        $articles = $paginator;


        $filtersParameters = array(
            'source' => $filterSource
        );

        $scripts = array('articles.js');

        return view('backend.articles.list', compact('title', 'totalArticles', 'articles', 'filtersParameters', 'scripts'));
    }

    public function register() {
        $tags = TagsModel::all();
        $categories = CategoriesModel::all();
    	$title = 'Articles | Register | SSY';

    	$scripts = array('articles.js');

        return view('backend.articles.register', compact('title', 'scripts', 'tags', 'categories'));
    }

    public function edit($articleId) {
        $title = 'Articles | Edition | SSY';
        $articleData = ArticlesModel::where('article_id', $articleId)->first();

        if (!$articleData) {
            return redirect('ssy-administration/articles-list');
        }

        // Load categories and tags for the form
        $categories = CategoriesModel::all();
        $tags = TagsModel::all();
        
        // Load existing article tags
        $articleTags = ArticleTagsModel::where('article_id', $articleId)->get();
        $selectedTagIds = $articleTags->pluck('tag_id')->toArray();
        
        // Load existing article images
        $existingImages = ArticleImagesModel::where('article_id', $articleId)
            ->orderBy('order_position', 'asc')
            ->get();

        $scripts = array('articles.js');

        return view('backend.articles.edit', compact(
            'title', 
            'articleData', 
            'scripts', 
            'categories', 
            'tags', 
            'selectedTagIds', 
            'existingImages'
        ));
    }

    public function editArticle($articleId, Request $request) {
        $messages = [
            'title.required' => 'Debes ingresar el título del artículo',
            'excerpt.required' => 'Debes ingresar el extracto del artículo',
            'body.required' => 'Debes ingresar el contenido del artículo',
            'meta_title.required' => 'Debes ingresar el meta title para la página del artículo',
            'meta_description.required' => 'Debes ingresar el meta description para la página del artículo',
            'url_slug.required' => 'La URL slug para la página del artículo es requerida',
            'category_id.required' => 'Debes seleccionar una categoría',
            'category_id.exists' => 'La categoría seleccionada no existe',
            'multimedia_gallery.required' => 'Debes subir al menos una imagen',
            'url_slug.unique' => 'Ya existe artículo con dicha URL slug registrada'
        ];

        $validations = $request->validate([
            'title' => 'required|max:50|min:3',
            'excerpt' => 'nullable|max:200',
            'body' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'url_slug' => 'required|unique:ssy_articles,url_slug,'.$articleId.',article_id',
            'category_id' => 'required|exists:ssy_categories,category_id',
            'publish_date' => 'nullable|date',
            'multimedia_gallery' => 'required|json',
            'tags' => 'nullable|json'
        ], $messages);

        $title = $request->input('title');
        $excerpt = $request->input('excerpt');
        $body = $request->input('body');
        $metaTitle = $request->input('meta_title');
        $metaDescription = $request->input('meta_description');
        $urlSlug = $request->input('url_slug');
        $categoryId = $request->input('category_id');
        $publishDate = $request->input('publish_date');
        $publishCheckbox = $request->input('publish_checkbox');
        $multimediaGallery = json_decode($request->input('multimedia_gallery'), true);
        $tags = json_decode($request->input('tags'), true);
        $deletedMultimedia = ($request->input('deleted_multimedia') != '') ? explode(',', $request->input('deleted_multimedia')) : array();

        // Handle publish date logic
        if ($publishCheckbox == 'on') {
            $publishDate = $publishDate ?: date('Y-m-d H:i:s');
        } else {
            $publishDate = date('Y-m-d H:i:s');
        }

        // Update article
        ArticlesModel::where('article_id', $articleId)->update([
            'title' => $title,
            'excerpt' => $excerpt,
            'body' => $body,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'url_slug' => $urlSlug,
            'category_id' => $categoryId,
            'publish_date' => $publishDate,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Handle deleted multimedia
        if (!empty($deletedMultimedia)) {
            ArticleImagesModel::whereIn('image_id', $deletedMultimedia)->delete();
        }

        // Handle tags - delete existing and add new
        ArticleTagsModel::where('article_id', $articleId)->delete();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                ArticleTagsModel::create([
                    'article_id' => $articleId,
                    'tag_id' => $tag['id']
                ]);
            }
        }

        // Handle multimedia gallery
        $multimediaArray = [];

        foreach ($multimediaGallery as $key => $multimedia) {
            if (array_key_exists('id', $multimedia)) {
                // Update existing image
                $imageId = $multimedia['id'];
                ArticleImagesModel::where([['article_id', $articleId], ['image_id', $imageId]])->update([
                    'order_position' => $multimedia['position'],
                    'alt_text' => $multimedia['alt_text']
                ]);
            } else {
                // New image
                if (isset($multimedia['thumbnail'])) {
                    $base64Image = $multimedia['thumbnail'];
                    $resizeImage = Image::make($base64Image);
                    $mime = $resizeImage->mime();
                    $z = explode('/', $mime);
                    $fileExtension = $z[1];

                    $destinationPath = public_path('backend/images/articles/');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $fileName = time() . '-' . $key . '.' . $fileExtension;
                    $clearFileName = pathinfo($fileName, PATHINFO_FILENAME);
                    $resizeImage->fit(700, 467)->save($destinationPath . $clearFileName . '-700x467.' . $fileExtension);
                    $resizeImage->fit(285, 307)->save($destinationPath . $clearFileName . '-285x307.' . $fileExtension);

                    $multimediaArray[] = array(
                        'source' => $fileName,
                        'order_position' => $multimedia['position'],
                        'article_id' => $articleId,
                        'alt_text' => $multimedia['alt_text']
                    );
                }
            }
        }

        // Save new images
        if (!empty($multimediaArray)) {
            foreach ($multimediaArray as $multimedia) {
                if ($multimedia['alt_text'] == '') {
                    $multimedia['alt_text'] = null;
                }

                ArticleImagesModel::create([
                    'source' => $multimedia['source'],
                    'order_position' => $multimedia['order_position'],
                    'article_id' => $multimedia['article_id'],
                    'alt_text' => $multimedia['alt_text']
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Artículo editado con éxito',
            'article_id' => $articleId
        ]);
    }

    public function registerArticle(Request $request) {
        Log::info('Article registration request:', $request->all());
        
        // Validation rules and messages
        $messages = [
            'title.required' => 'Article title is required',
            'title.max' => 'Article title must not exceed 50 characters',
            'title.min' => 'Article title must be at least 3 characters',
            'excerpt.max' => 'Article excerpt must not exceed 200 characters',
            'body.required' => 'Article body is required',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category does not exist',
        ];

        $validations = $request->validate([
            'title' => 'required|string|max:50|min:3',
            'excerpt' => 'nullable|string|max:200',
            'body' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'url_slug' => 'nullable|string',
            'category_id' => 'required|exists:ssy_categories,category_id',
            'publish_date' => 'nullable|date',
        ], $messages);

        // Validate multimedia gallery
        $multimediaGallery = json_decode($request->input('multimedia_gallery'), true);
        if (!is_array($multimediaGallery) || empty($multimediaGallery)) {
            return response()->json([
                'success' => false,
                'message' => 'At least one image is required'
            ], 422);
        }

        // Validate file types in multimedia gallery
        foreach ($multimediaGallery as $item) {
            if (!isset($item['type']) || $item['type'] !== 'image') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only image files are allowed'
                ], 422);
            }
            
            if (!isset($item['thumbnail']) || empty($item['thumbnail'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image data'
                ], 422);
            }
        }

        // Create and save article
        $articleData = new ArticlesModel();
        $articleData->title = $request->input('title');
        $articleData->excerpt = $request->input('excerpt');
        $articleData->body = $request->input('body');
        $articleData->meta_title = $request->input('meta_title');
        $articleData->meta_description = $request->input('meta_description');
        $articleData->url_slug = $request->input('url_slug');
        $articleData->category_id = $request->input('category_id');
        
        // Handle publish date based on checkbox
        if ($request->has('publish_checkbox') && $request->input('publish_checkbox') == 'on') {
            // Checkbox is checked, use the selected date
            $articleData->publish_date = $request->input('publish_date');
        } else {
            // Checkbox is not checked, publish immediately (today)
            $articleData->publish_date = date('Y-m-d H:i:s');
        }
        
        $articleData->save();

        $articleId = $articleData->article_id;

        // Create article tags relationships
        if ($request->has('tags') && !empty($request->input('tags'))) {
            $tags = json_decode($request->input('tags'), true);
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

        // Process multimedia gallery and create article images
        $totalFiles = 0;

        foreach ($multimediaGallery as $key => $multimedia) {
            $base64Image = $multimedia['thumbnail'];
            
            // Validate base64 image
            if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $base64Image)) {
                continue; // Skip invalid images
            }

            // Extract image data and determine extension
            $imageData = base64_decode(preg_replace('/^data:image\/(jpeg|jpg|png);base64,/', '', $base64Image));
            
            // Get file extension from the data URL
            preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $base64Image, $matches);
            $fileExtension = str_replace('jpeg', 'jpg', $matches[1] ?? 'jpg');

            // Create destination directory
            $destinationPath = public_path('backend/images/articles/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Generate filename
            $fileName = time() . '-' . $key . '.' . $fileExtension;

            // Save original image
            file_put_contents($destinationPath . $fileName, $imageData);

            // Create article image record
            $articleImage = new ArticleImagesModel();
            $articleImage->source = $fileName;
            $articleImage->order_position = $multimedia['position'] ?? $key;
            $articleImage->article_id = $articleId;
            $articleImage->alt_text = $multimedia['file_alternative_text'] ?? 'Article Image';
            $articleImage->save();

            $totalFiles++;
        }

        if ($totalFiles === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No valid images were processed'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Article registered successfully with ' . $totalFiles . ' images',
            'article_id' => $articleId
        ]);
    }

    public function deleteArticle($articleId) {
        if (!isset($articleId)) {
            return response()->json([
                'success' => false,
                'message' => 'El ID del artículo no existe'
            ]);
        }

        $articleData = ArticlesModel::where('article_id', $articleId)->first();

        if (!$articleData) {
            return response()->json([
                'success' => false,
                'message' => 'El artículo es inválido o inexistente'
            ]);
        }

        ArticlesModel::where('article_id', $articleId)->delete();
        ArticleImagesModel::where('article_id', $articleId)->delete();
        ArticleTagsModel::where('article_id', $articleId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artículo eliminado con éxito'
        ]);
    }

    public function getArticleUrlSlug($articleId, Request $request) {
        $title = $request->input('title');
        $slug = $this->slugifyArticle($articleId, $title);

        return Response()->json([
            'success' => true,
            'slug' => $slug
        ]);
    }

    public function slugifyArticle($articleId, $title) {
        $slug = Str::slug($title);
        $slug = Str::limit($slug, 200);

        $existingSlugs = ArticlesModel::where(function($query) use ($slug) {
            $query->whereRaw("url_slug = '$slug' or url_slug LIKE '$slug%'");
        })->when($articleId, function ($query, $articleId) {
            return $query->where('article_id', '<>', $articleId);
        })->get();

        if (!$existingSlugs->contains('url_slug', $slug)) {
            return $slug;
        }

        $limit = 1;
        for ($i = 1; $i <= $limit; $i++) {
            $newSlug = Str::limit( $slug, 200 - ( strlen( $i ) + 1 ) ) . '-' . $i;
            if (!$existingSlugs->contains('url_slug', $newSlug)) {
                return $newSlug;
            }
            $limit ++;
        }
    }

    public function logout() {
        auth()->logout();
        Session::flush();
        return Redirect('ssy-administration');
    }
}