<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('staff_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->string('voucher_type', 45)->nullable();
            $table->string('voucher_serie', 10)->nullable();
            $table->string('voucher_number', 20)->nullable();
            $table->string('observation')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outputs');
    }
}
