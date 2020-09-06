<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            $table->string('first_name', 120);
            $table->string('last_name', 120);
            $table->string('dni', 8);
            $table->string('address');
            $table->string('phone', 9);
            $table->string('email')->unique();

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('staff');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
