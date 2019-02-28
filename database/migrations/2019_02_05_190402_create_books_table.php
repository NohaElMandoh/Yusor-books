<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksTable extends Migration {

	public function up()
	{
		Schema::create('books', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 191);
			$table->string('desc', 200);
			$table->year('publish_year');
			$table->integer('author_id')->unsigned();
			$table->integer('department_id')->unsigned();
			$table->string('photo', 191);
            $table->string('ISBN_num', 191);


		});
	}

	public function down()
	{
		Schema::drop('books');
	}
}