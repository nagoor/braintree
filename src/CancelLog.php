<?php

namespace Nextl\braintree;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CancelLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'cancel_log';
}
