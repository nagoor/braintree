<?php

namespace Nextl\braintree;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Braintree;
use Redirect;
use Session;
use Nextl\braintree\PaymentLog;
use Nextl\braintree\TransactionLog;
use DB;

class BraintreeController extends Controller
{
	public function __construct() {
		$config = new \Braintree_Configuration([
		    'environment' => env('BRAINTREE_ENV'),
		    'merchantId' => env('BRAINTREE_MERCHANT_ID'),
		    'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
		    'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
		]);
		$this->gateway = new Braintree\Gateway($config);
    }

    public function signup(Request $request) 
    {
    	$userId = $request->get('userId');
    	$packageId = $request->get('packageId');
    	$price = $request->get('price');
    	$trailPeriod = $request->get('trailPeriod'); // in number

    	try {
    		if($userId==''&&$packageId==''&&$price==''&&$trailPeriod=='') {
		    	throw new \Exception("These fields are required. <br/> (User Id & Package Id, Price, Trail Priod is Missing!)");
		    }
		    else if($userId=='') {
		    	throw new \Exception("User Id is required");
		    }
		    else if($packageId=='') {
		    	throw new \Exception("Package Id is required");
		    }
		    else if($price=='') {
		    	throw new \Exception("Price is required");
		    }
		    else if($trailPeriod=='') {
		    	throw new \Exception("Trail Period is required");
		    }

		    // Braintree billing 

		    # create customer
		    $result = $this->gateway->customer()->create([
			    'id' => 'USR_'.$userId,
			    'paymentMethodNonce' => 'fake-valid-nonce',
			]);
			$paymentMethodToken = '';
			$customerId = '';
			if ($result->success) {
				$customerId = $result->customer->id;
			    $paymentMethodToken = $result->customer->paymentMethods[0]->token;
			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        throw new \Exception($error->code . ": " . $error->message . "\n");
			    }
			}

			# create subscription
			# with immediate billing
			# bill will be charged immediately
			if($trailPeriod==0) {				
				$result = $this->gateway->subscription()->create([
				  'paymentMethodToken' => $paymentMethodToken,
				  'planId' => 'm73b',	
				  'price' => $price,	
				  'options' => ['startImmediately' => true]
				]);
			} 

			# without immediate billing
			# bill will be charged on specific date
			if($trailPeriod!=0) {	
				$date = new \DateTime(date('Y-m-d'));				 
				$interval = new \DateInterval('P'.$trailPeriod.'D');				 
				$date->add($interval);				 
				$billDate = $date->format("Y-m-d");

				$result = $this->gateway->subscription()->create([
				  'paymentMethodToken' => $paymentMethodToken,
				  'planId' => 'm73b',
				  'price' => $price,
				  'firstBillingDate' => $billDate,
				]);
			}

			if ($result->success) {

				# store payment log
			    $paymentLog = array(
			    	'userId'=>$userId,
		            'packageId'=>$packageId,
		            'timePeriod'=>$trailPeriod,
		            'balance'=>$result->subscription->balance,
		            'billingDayOfMonth'=>$result->subscription->billingDayOfMonth,
		            'billingPeriodEndDate'=>(isset($result->subscription->billingPeriodEndDate) && $result->subscription->billingPeriodEndDate!='')?$result->subscription->billingPeriodEndDate->format('Y-m-d H:i:s'):'',
		            'billingPeriodStartDate'=>(isset($result->subscription->billingPeriodStartDate) && $result->subscription->billingPeriodStartDate!='')?$result->subscription->billingPeriodStartDate->format('Y-m-d H:i:s'):'',
		            'paymentDate'=>$result->subscription->createdAt->format('Y-m-d H:i:s'),
		            'currentBillingCycle'=>$result->subscription->currentBillingCycle,
		            'daysPastDue'=>$result->subscription->daysPastDue,
		            'firstBillingDate'=>@$result->subscription->firstBillingDate->format('Y-m-d H:i:s'),
		            'subscriptionId'=>$result->subscription->id,
		            'merchantAccountId'=>$result->subscription->merchantAccountId,
		            'nextBillAmount'=>$result->subscription->nextBillAmount,
		            'nextBillingDate'=>@$result->subscription->nextBillingDate->format('Y-m-d H:i:s'),
		            'numberOfBillingCycles'=>$result->subscription->numberOfBillingCycles,
		            'paymentMethodToken'=>$result->subscription->paymentMethodToken,
		            'planId'=>$result->subscription->planId,
		            'price'=>$result->subscription->price,
		            'status'=>$result->subscription->status
			    ); 
			    DB::collection('payment_log')->insert($paymentLog);


			    # store transaction log
			    if($trailPeriod!=0) {	
				    $result = $this->gateway->transaction()->sale([
					  'amount' => $price,
					  'paymentMethodNonce' => 'fake-valid-nonce',
					  'customerId' => $customerId,
					  'options' => [
					    'submitForSettlement' => true
					   ]
					]);
				    $transactionLog = array(
				    	'userId'=>$userId,
			            'packageId'=>$packageId,
			            'transactionId'=>$result->transaction->id,
			            'status'=>$result->transaction->status,
			            'type'=>$result->transaction->type,
			            'currencyIsoCode'=>@$result->transaction->currencyIsoCode,
			            'amount'=>@$result->transaction->amount,
			            'merchantAccountId'=>$result->transaction->merchantAccountId,
			            'orderId'=>$result->transaction->orderId,
			            'createdAt'=>$result->transaction->createdAt->format('Y-m-d H:i:s'),
			            'customerId'=>@$result->transaction->customer->id
				    ); 
				    DB::collection('transaction_log')->insert($transactionLog);
				}

			    echo 'Success';

			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        throw new \Exception($error->code . ": " . $error->message . "\n");
			    }
			}

		}
		catch (\Exception $e) {
		    echo "Message: " . $e->getMessage();	    
		}
    }
}
