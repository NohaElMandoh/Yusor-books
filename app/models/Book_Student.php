<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Book_Student extends Model 
{

    protected $table = 'book_student';
    public $timestamps = true;
    protected $fillable = array('student_id', 'book_id', 'price', 'book_status', 'availability', 'transaction_types_id');

    public function transactionType()
    {
        return $this->belongsTo('Models\TransactionType');
    }

}