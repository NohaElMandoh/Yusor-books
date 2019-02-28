<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookStudentTable extends Migration {

	public function up()
	{
		Schema::create('book_student', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('student_id');
			$table->integer('book_id');
			$table->float('price');
			$table->boolean('book_status');
			$table->boolean('availability');
			$table->integer('transaction_id');
		});
	}

	public function down()
	{
		Schema::drop('book_student');
	}
}