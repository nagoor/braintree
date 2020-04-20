<?php

namespace Nextl\braintree;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TransactionLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'transaction_log';

    protected $fillable = [
        'userId','packageId','transactionId','status','type','currencyIsoCode','amount','merchantAccountId','orderId','createdAt','customerId'
    ];
}
