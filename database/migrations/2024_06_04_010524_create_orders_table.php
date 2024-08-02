<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('stock_id')->unsigned()->index();
            $table->integer('quantity');
            $table->string('note')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        if (config('app.debug') == true) {
            DB::table('orders')->insert([
                [
                    'user_id' => 1,
                    'stock_id' => 1, // corrected column name
                    'quantity' => 1,
                    'note' => null,
                    'status' => 'completed',
                    'created_at' => now(), // corrected syntax
                    'updated_at' => now(), // added for completeness
                ],
                [
                    'user_id' => 21,
                    'stock_id' => 1, // corrected column name
                    'quantity' => 2,
                    'note' => null,
                    'status' => 'completed',
                    'created_at' => now(), // corrected syntax
                    'updated_at' => now(), // added for completeness
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
        Schema::dropIfExists('orders');
    }
}
