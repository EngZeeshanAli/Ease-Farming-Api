<?php

use App\Utils\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create(Constants::TABLE_PRODUCTS, function (Blueprint $table) {
            $table->id();
            $table->string(Constants::NAME);
            $table->integer(Constants::PRICE);
            $table->string(Constants::QUANTITY);
            $table->foreignId(Constants::USER_ID)->references(Constants::ID)->on(Constants::TABLE_USERS);
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
        Schema::dropIfExists('products');
    }
}
