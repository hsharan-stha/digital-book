<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // optional, agar nomi standart bo‘lsa (categories) bu kerak emas
    protected $fillable = ['name'];


    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
