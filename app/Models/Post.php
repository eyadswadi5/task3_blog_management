<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";

    protected $fillable = [
        "title", "slug", "body", "is_published", "publish_date", "meta_description", "tags"
    ];

    protected $casts = [
        "tags" => "array",
        "keywords" => "array",
    ];
}
