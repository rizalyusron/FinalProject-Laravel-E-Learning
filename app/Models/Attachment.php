<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_name',
        'course_id',
        'title',
        'description',
        'pdf_path'
    ];
}
