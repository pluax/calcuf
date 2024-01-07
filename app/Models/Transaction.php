<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'id',
        'cost',
        'category_id',
        'date',
        'type',
        'comment',
        'user_id',
    ];
    public $timestamps = false;
}
