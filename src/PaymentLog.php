<?php

namespace Nextl\braintree;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class PaymentLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'payment_log';

    protected $fillable = [
        'userId','packageId','timePeriod','balance','billingDayOfMonth','billingPeriodEndDate','billingPeriodStartDate','paymentDate','currentBillingCycle','daysPastDue','firstBillingDate','subscriptionId','merchantAccountId','nextBillAmount','nextBillingDate','numberOfBillingCycles','paymentMethodToken','planId','price','status'
    ];
}
