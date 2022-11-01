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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->longText('details');
            $table->double('total_iva_collected');
            $table->double('total_amount_payable');
            $table->dateTime('date_issuance');
            $table->dateTime('date_payment');
            $table->string('type');
            $table->string('state');
            $table->string('status')->default(true);

            $table->foreignId('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('invoices');
    }
};
