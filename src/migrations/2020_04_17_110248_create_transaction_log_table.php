<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userId');
            $table->integer('packageId');
            $table->string('transactionId');
            $table->string('status');
            $table->string('type');
            $table->string('currencyIsoCode');
            $table->decimal('amount', 8, 2);
            $table->string('merchantAccountId');
            $table->string('orderId');
            $table->string('createdAt');
            $table->string('customerId');
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
        Schema::dropIfExists('transaction_log');
    }
}
