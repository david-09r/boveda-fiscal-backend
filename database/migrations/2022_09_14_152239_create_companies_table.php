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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nit')->unique();
            $table->string('social_reason')->nullable()->unique();
            $table->string('site_direction')->nullable();
            $table->integer('code_number');
            $table->bigInteger('phone_number')->unique();
            $table->string('email')->unique();
            $table->string('website')->nullable()->unique();
            $table->boolean('status')->default(true);

            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('companies');
    }
};
