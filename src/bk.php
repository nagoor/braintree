# store payment log
$paymentLog = array(
	'userId'=>$userId,
    'package'=>$package,
    'timePeriod'=>$trailPeriod,
    'balance'=>$result->subscription->balance,
    'billingDayOfMonth'=>$result->subscription->billingDayOfMonth,
    'billingPeriodEndDate'=>(isset($result->subscription->billingPeriodEndDate) && $result->subscription->billingPeriodEndDate!='')?$result->subscription->billingPeriodEndDate->format('Y-m-d H:i:s'):'',
    'billingPeriodStartDate'=>(isset($result->subscription->billingPeriodStartDate) && $result->subscription->billingPeriodStartDate!='')?$result->subscription->billingPeriodStartDate->format('Y-m-d H:i:s'):'',
    'paymentDate'=>$result->subscription->createdAt->format('Y-m-d H:i:s'),
    'currentBillingCycle'=>$result->subscription->currentBillingCycle,
    'daysPastDue'=>$result->subscription->daysPastDue,
    'firstBillingDate'=>$result->subscription->firstBillingDate->format('Y-m-d H:i:s'),
    'subscriptionId'=>$result->subscription->id,
    'merchantAccountId'=>$result->subscription->merchantAccountId,
    'nextBillAmount'=>$result->subscription->nextBillAmount,
    'nextBillingDate'=>$result->subscription->nextBillingDate->format('Y-m-d H:i:s'),
    'numberOfBillingCycles'=>$result->subscription->numberOfBillingCycles,
    'paymentMethodToken'=>$result->subscription->paymentMethodToken,
    'planId'=>$result->subscription->planId,
    'price'=>$result->subscription->price,
    'status'=>$result->subscription->status
); 
DB::collection('payment_log')->insert($paymentLog);