<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
