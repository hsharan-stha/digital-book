<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;


    protected $table = 'purchases'; // optional, agar nomi standart bo‘lsa (categories) bu kerak emas
    protected $fillable = ['id','purchase_date', 'total_amount','item_count', 'is_paid'];
    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
