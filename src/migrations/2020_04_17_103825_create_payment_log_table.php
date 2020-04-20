<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userId');
            $table->integer('packageId');
            $table->integer('timePeriod');
            $table->decimal('balance', 8, 2);
            $table->integer('billingDayOfMonth');
            $table->string('billingPeriodEndDate');
            $table->string('billingPeriodStartDate');
            $table->string('paymentDate');
            $table->integer('currentBillingCycle');
            $table->string('daysPastDue');
            $table->string('firstBillingDate');
            $table->string('subscriptionId');
            $table->string('merchantAccountId');
            $table->string('nextBillAmount');
            $table->string('nextBillingDate');
            $table->string('numberOfBillingCycles');
            $table->string('paymentMethodToken');
            $table->string('planId');
            $table->decimal('price', 8, 2);
            $table->string('status');
            $table->string('trialDuration');
            $table->string('trialPeriod');
            $table->string('description');
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
        Schema::dropIfExists('payment_log');
    }
}
