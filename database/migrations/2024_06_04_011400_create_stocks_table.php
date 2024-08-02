<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->string('size');
            $table->string('color');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
        if (config('app.debug') == true) {
            DB::table('stocks')->insert([
                [
                    'product_id' => 1,
                    'size' => '15.6"',
                    'color' => 'Black',
                    'quantity' => 0,
                    'created_at' => now(),
                    'updated_at' => now(), // Include updated_at for completeness
                ],
                [
                    'product_id' => 2,
                    'size' => '', // Fill in appropriate size
                    'color' => 'Silver', // Corrected 'Silcer' to 'Silver'
                    'quantity' => 20,
                    'created_at' => now(),
                    'updated_at' => now(), // Include updated_at for completeness
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
