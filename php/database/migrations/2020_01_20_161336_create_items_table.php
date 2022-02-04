<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_id');
            $table->string('bran_number')->nullable();
            $table->string('style')->nullable();
            $table->string('quality')->nullable();
            $table->string('text')->nullable();
            $table->string('brand')->nullable();
            $table->string('brand_text')->nullable();
            $table->string('group')->nullable();
            $table->string('group_text')->nullable();
            $table->string('comp_code')->nullable();
            $table->string('comp_text')->nullable();
            $table->string('image_2')->nullable();
            $table->string('item_color')->nullable();
            $table->string('pic_color')->nullable();
            $table->string('pic_style')->nullable();
            $table->string('item_color_2')->nullable();
            $table->string('item_color_3')->nullable();
            $table->string('item_color_4')->nullable();
            $table->string('item_color_5')->nullable();
            $table->string('item_color_6')->nullable();
            $table->string('item_color_7')->nullable();
            $table->string('item_color_8')->nullable();
            $table->string('item_color_9')->nullable();
            $table->string('item_color_10')->nullable();
            $table->string('item_color_text')->nullable();
            $table->string('collection')->nullable();
            $table->string('collection_name')->nullable();
            $table->string('new')->nullable();
            $table->string('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
