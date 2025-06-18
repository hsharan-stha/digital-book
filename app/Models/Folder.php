<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folders';
    protected $fillable = ['id', 'name', 'user_id'];

    public function purchase_details()
    {
        return $this->belongsTo(PurchaseDetail::class);
    }
}
