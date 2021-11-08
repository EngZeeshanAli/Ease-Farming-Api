<?php

use App\Utils\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::TABLE_DISEASE, function (Blueprint $table) {
            $table->id();
            $table->string(Constants::NAME);
            $table->string(Constants::ENG_NAME);
            $table->string(Constants::IMAGE)->nullable();
            $table->longText(Constants::CAUSE);
            $table->longText(Constants::SOLUTION);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diseases');
    }
}
