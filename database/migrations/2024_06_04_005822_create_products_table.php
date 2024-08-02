<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->index(); // corrected column name
            $table->integer('deal_id')->unsigned()->nullable()->index();
            $table->string('photo');
            $table->string('brand');
            $table->string('name');
            $table->string('description');
            $table->string('details');
            $table->double('price');
            $table->timestamps();

            // // Define foreign key constraint for category_id
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
        if (config('app.debug') == true) {
            DB::table('products')->insert([
                [
                    'user_id' => 1,
                    'category_id' => 1,
                    'deal_id' => null,
                    'photo' => '["product01.png", "product03.png", "product06.png", "product08.png"]',
                    'brand' => 'HP',
                    'name' => 'HP Probook 4540s', // corrected capitalization
                    'description' => 'This is the product description!',
                    'details' => 'These are the product details', // corrected spelling
                    'price' => 700,
                    'created_at' => now(), // corrected syntax
                ],
                [
                    'user_id' => 1,
                    'category_id' => 1,
                    'deal_id' => null,
                    'photo' => '["product01.png", "product03.png", "product06.png", "product08.png"]',
                    'brand' => 'Dell',
                    'name' => 'Dell XPS', // corrected capitalization
                    'description' => 'This is the product description!',
                    'details' => 'These are the product details', // corrected spelling
                    'price' => 1700,
                    'created_at' => now(), // corrected syntax
                ]
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
        Schema::dropIfExists('products');
    }
}
