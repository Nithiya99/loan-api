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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('loan_pmt_id');
            $table->uuid('loan_id'); 
            $table->foreign('loan_id')->references('loan_id')->on('loans')->onUpdate('cascade');
            $table->integer('loan_pmt_sequence');
            $table->decimal('loan_pmt_amt', $precision = 10, $scale = 2);
            $table->string('loan_pmt_status');
            $table->dateTime('loan_pmt_date');
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
        Schema::dropIfExists('loan_payments');
    }
};
