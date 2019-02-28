<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionTypesTable extends Migration {

	public function up()
	{
		Schema::create('transaction_types', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 191);
		});
	}

	public function down()
	{
		Schema::drop('transaction_types');
	}
}