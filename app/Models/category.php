<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_name',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examForms()
    {
        return $this->hasMany(examform::class, 'category_id');
    }
}
