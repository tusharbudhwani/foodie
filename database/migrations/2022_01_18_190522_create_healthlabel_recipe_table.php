<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthlabelRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healthlabel_recipe', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('healthlabel_id');

            $table->timestamps();

            $table->foreign('recipe_id')
                ->references('id')
                ->on('recipes')
                ->onDelete('cascade');

            $table->foreign('healthlabel_id')
                ->references('id')
                ->on('healthlabels')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('healthlabel_recipe');
    }
}
