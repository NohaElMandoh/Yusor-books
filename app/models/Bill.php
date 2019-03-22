<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model 
{

    protected $table = 'bills';
    public $timestamps = true;
    protected $fillable = array('TotalAmount', 'book_id', 'buyer_id', 'owner_status', 'buyer_status','student_id');

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function student_buy()
    {
        return $this->belongsTo('App\Models\Student');
    }

}