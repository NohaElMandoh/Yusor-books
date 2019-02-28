<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model 
{

    protected $table = 'bills';
    public $timestamps = true;
    protected $fillable = array('TotalAmount', 'book_id', 'student_id', 'owner_status', 'buyer_status');

    public function book()
    {
        return $this->hasMany('Models\Book');
    }

    public function student_buy()
    {
        return $this->hasMany('Models\Student');
    }

}