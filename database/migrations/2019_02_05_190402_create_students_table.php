<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration {

	public function up()
	{
		Schema::create('students', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('perso_name', 191)->nullable();
			$table->string('G_email', 191)->nullable();
			$table->string('photo', 191);
			$table->string('access_token', 191);
			$table->integer('user_id');
			$table->string('password', 191);
			$table->string('major', 191);
		});
	}

	public function down()
	{
		Schema::drop('students');
	}
}