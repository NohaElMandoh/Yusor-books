<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model 
{

    protected $table = 'transaction_types';
    public $timestamps = true;
    protected $fillable = array('name');

    public function BookStudent()
    {
        return $this->hasMany('Models\Book_Student');
    }

}