<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use HasFactory;
    use Notifiable;


    protected $fillable = [
        'author',
        'title',
        'publish_date',
        'status',
        'content',
        'categories',
        'images',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
    ];
}
