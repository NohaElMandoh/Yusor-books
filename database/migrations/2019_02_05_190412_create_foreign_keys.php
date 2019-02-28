<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('books', function(Blueprint $table) {
			$table->foreign('auther_id')->references('id')->on('authors')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('books', function(Blueprint $table) {
			$table->foreign('dept_id')->references('id')->on('departments')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('bills', function(Blueprint $table) {
			$table->foreign('book_id')->references('id')->on('books')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('bills', function(Blueprint $table) {
			$table->foreign('student_id')->references('id')->on('students')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('books', function(Blueprint $table) {
			$table->dropForeign('books_auther_id_foreign');
		});
		Schema::table('books', function(Blueprint $table) {
			$table->dropForeign('books_dept_id_foreign');
		});
		Schema::table('bills', function(Blueprint $table) {
			$table->dropForeign('bills_book_id_foreign');
		});
		Schema::table('bills', function(Blueprint $table) {
			$table->dropForeign('bills_student_id_foreign');
		});
	}
}