<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model {
    use HasFactory;

    public $table = 'ssy_users';
    public $primaryKey = 'user_id';
    public $timestamps = false;
}
