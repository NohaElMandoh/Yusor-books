<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model 
{

    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = array('perso_name', 'G_email', 'photo', 'api_token', 'user_id', 'password', 'major');

    public function books()
    {
        return $this->belongsToMany('Models\Book');
    }

    public function bill()
    {
        return $this->belongsTo('Models\Bill');
    }
    protected $hidden = [
        'password', 'api_token'
    ];
}