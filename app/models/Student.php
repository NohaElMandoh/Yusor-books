<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model 
{

//            $table->string('Gender', 10);
//            $table->integer('department_id')->unsigned();;
    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = array('perso_name', 'Email',  'api_token', 'UserName', 'password', 'Gender','department_id');

    public function books()
    {
        return $this->belongsToMany('Models\Book');
    }

    public function department()
    {
        return $this->belongsTo('App\models\Department');
    }

    public function bill()
    {
        return $this->belongsTo('Models\Bill');
    }
    protected $hidden = [
        'password', 'api_token'
    ];
}