<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImagesModel extends Model {
    use HasFactory;

    public $table = 'ssy_article_images';
    public $primaryKey = 'image_id';
    public $timestamps = false;
}
