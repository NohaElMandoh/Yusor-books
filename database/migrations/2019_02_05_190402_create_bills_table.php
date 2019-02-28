<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillsTable extends Migration {

	public function up()
	{
		Schema::create('bills', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->float('TotalAmount');
			$table->integer('book_id')->unsigned();
			$table->integer('student_id')->unsigned();
			$table->boolean('owner_status');
			$table->boolean('buyer_status');
		});
	}

	public function down()
	{
		Schema::drop('bills');
	}
}