<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracted_works', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid');
            $table->foreignId('work_id');
            $table->foreignId('contractor_id');
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('value_paid')->nullable();
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('done_at')->nullable();
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
        Schema::dropIfExists('contracted_works');
    }
};