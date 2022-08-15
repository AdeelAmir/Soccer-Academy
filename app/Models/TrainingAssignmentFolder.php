<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAssignmentFolder extends Model
{
    protected $fillable = [
        'user_id',
        'folder_id',
        'completion_rate',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
