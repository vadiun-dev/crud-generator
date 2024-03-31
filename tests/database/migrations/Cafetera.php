<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CafeteraMigration extends Migration
{
	public function up(): void
	{
		Schema::create('cafeteras', function (Blueprint $table) {
		                $table->id();
                        $table->integer('category_id');
                        $table->string('name')->nullable();
                        $table->string('description');
                        $table->float('price');
                        $table->dateTime('fecha');
                        $table->boolean('weight');
                        $table->timestamps();
        });
	}
}
