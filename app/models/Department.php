<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model 
{

    protected $table = 'departments';
    public $timestamps = true;
    protected $fillable = array('name');

    public function book()
    {
        return $this->hasMany('Models\Book');
    }

}