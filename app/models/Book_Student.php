<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book_Student extends Model 
{

    protected $table = 'book_student';
    public $timestamps = true;
    protected $fillable = array('student_id', 'book_id', 'price', 'book_status', 'availability', 'transaction_id','bill_status');

    public function transactionType()
    {
        return $this->belongsTo('App\Models\TransactionType');
    }

}