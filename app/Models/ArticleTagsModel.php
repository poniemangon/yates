<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleTagsModel extends Model {
    use HasFactory;

    public $table = 'ssy_article_tags';
    public $primaryKey = 'article_tag_id';
    public $timestamps = false;
}
