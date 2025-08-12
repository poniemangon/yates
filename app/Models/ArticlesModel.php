<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlesModel extends Model {
    use HasFactory;

    public $table = 'ssy_articles';
    public $primaryKey = 'article_id';
    public $timestamps = false;
}
