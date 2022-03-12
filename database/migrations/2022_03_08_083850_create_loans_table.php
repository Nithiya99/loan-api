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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->uuid('loan_id')->unique();
            $table->decimal('loan_amt', $precision = 10, $scale = 2);
            $table->integer('loan_terms');
            $table->string('loan_status');
            $table->uuid('cust_id');
            $table->foreign('cust_id')->references('user_id')->on('users')->onUpdate('cascade');
            $table->uuid('loan_approve_id')->nullable();
            $table->foreign('loan_approve_id')->references('user_id')->on('users')->onUpdate('cascade');
            $table->timestamp('loan_approve_date')->nullable();
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
        Schema::dropIfExists('loans');
    }
};
