<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'assignment_type',
        'training_assignment_folder_id',
        'assignment_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
