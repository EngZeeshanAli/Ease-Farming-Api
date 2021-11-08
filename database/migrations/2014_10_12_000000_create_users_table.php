<?php

use App\Utils\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::TABLE_USERS, function (Blueprint $table) {
            $table->id();
            $table->string(Constants::NAME);
            $table->string(Constants::EMAIL)->unique();
            $table->string(Constants::PASSWORD);
            $table->string(Constants::IMAGE)->nullable();
            $table->string(Constants::MOBILE);
            $table->integer(Constants::USER_TYPE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Constants::USER_TYPE);
    }
}
