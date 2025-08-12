<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model {
    use HasFactory;

    public $table = 'ssy_categories';
    public $primaryKey = 'category_id';
    public $timestamps = false;
}
