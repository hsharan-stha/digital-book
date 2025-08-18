<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReaderSession extends Model
{
    use HasFactory;

    protected $table = "reader-sessions";

    protected $fillable = ['session_key', 'session_data'];
}
