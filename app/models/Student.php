<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model 
{

    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = array('perso_name', 'Email',  'api_token', 'UserName', 'password', 'Gender','department_id','firbase_id','pin_code');

    public function books()
    {
        return $this->belongsToMany('App\Models\Book')->
        withPivot('price', 'book_status', 'availability','transaction_types_id')->withTimestamps();
    }

    public function department()
    {
        return $this->belongsTo('App\models\Department');
    }

    public function bill()
    {
        return $this->hasMany('App\Models\Bill');
    }
    public function report(){
        $this->hasMany('App\Models\Report');
    }
    protected $hidden = [
        'password', 'api_token'
    ];
}