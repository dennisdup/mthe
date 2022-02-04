<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->string('external_id')->nullable();
            $table->string('colour_code')->nullable();
            $table->string('colour_text')->nullable();
            $table->string('size_no')->nullable();
            $table->string('size')->nullable();
            $table->string('price_level')->nullable();
            $table->string('activ_colour')->nullable();
            $table->string('ean')->nullable();
            $table->string('item_style')->nullable();
            $table->string('pic_color')->nullable();
            $table->string('item_text')->nullable();
            $table->timestamps();

            $table->foreign('item_id')
                ->references('id')->on('items')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('variants');
    }
}
