<?php

namespace Nextl\braintree;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TransactionLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'transaction_log';
}
