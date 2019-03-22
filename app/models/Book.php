<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model 
{

    protected $table = 'books';
    public $timestamps = true;
    protected $fillable = array('title', 'desc', 'publish_year', 'author_id', 'department_id', 'photo', 'ISBN_num');

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function students()
    {
        return $this->belongsToMany('App\Models\Student');
    }

    public function bill()
    {
        return $this->hasMany('App\Models\Bill');
    }

}