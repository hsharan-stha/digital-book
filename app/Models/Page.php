<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = ['book_id', 'name', 'title', 'pageno', 'page_image', 'page_html'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
