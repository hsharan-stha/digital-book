<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;


    protected $table = 'purchase_details'; // optional, agar nomi standart bo‘lsa (categories) bu kerak emas
    protected $fillable = ['purchase_id', 'book_id', 'user_id', 'quantity', 'per_price', 'price', "folder_id"];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
