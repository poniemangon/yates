<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsModel extends Model {
    use HasFactory;

    public $table = 'ssy_tags';
    public $primaryKey = 'tag_id';
    public $timestamps = false;
}
