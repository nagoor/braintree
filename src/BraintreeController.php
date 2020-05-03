<?php

namespace Nextl\btree;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Braintree;
use Redirect;
use Session;
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
    	$package = $request->get('package');
    	$price = $request->get('price');
    	$trailPeriod = $request->get('trailPeriod'); // in number
		$response = array();	

    	try 
    	{
    		if($userId==''||$package==''||$price==''||$trailPeriod=='') {
		    	$response['status'] = 0;
		    	$response['message'] = 'User Id, Package, Price and Trail period is required';
		    	return json_encode($response); 	    
		    }

		    # Braintree billing 
		    # create customer
		    $result = $this->gateway->customer()->create([
			    'id' => 'USR_'.$userId,
			    'paymentMethodNonce' => 'fake-valid-nonce',
			]);
			$paymentMethodToken = '';
			$customerId = '';
			$package = $package.'_USR_'.$userId;
			if ($result->success) {
				$customerId = $result->customer->id;
			    $paymentMethodToken = $result->customer->paymentMethods[0]->token;
			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        $response['status'] = 0;
		    		$response['message'] = $error->message;
		    		return json_encode($response); 	    
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
				  'trialPeriod' => false,
  				  'trialDuration' => 0
				]);
				$this->gateway->subscription()->update($result->subscription->id, [
				    'id' => $package,
				    'paymentMethodToken' => $paymentMethodToken
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
				$this->gateway->subscription()->update($result->subscription->id, [
				    'id' => $package,
				    'paymentMethodToken' => $paymentMethodToken
				]);
			}


			if ($result->success) {
			    # store transaction log
			    $res = $this->gateway->transaction()->sale([
				  'amount' => $price,
				  'paymentMethodNonce' => 'fake-valid-nonce',
				  'customerId' => $customerId,
				  'options' => [
				    'submitForSettlement' => true
				   ]
				]);
			    $transactionLog = array(
			    	'userId'=>$userId,
		            'package'=>$package,
		            'transactionId'=>$res->transaction->id,
		            'status'=>$res->transaction->status,
		            'type'=>$res->transaction->type,
		            'currencyIsoCode'=>$res->transaction->currencyIsoCode,
		            'amount'=>$res->transaction->amount,
		            'merchantAccountId'=>$res->transaction->merchantAccountId,
		            'orderId'=>$res->transaction->orderId,
		            'createdAt'=>$res->transaction->createdAt->format('Y-m-d H:i:s'),
		            'customerId'=>$customerId,
		            'balance'=>$result->subscription->balance,
		            'billingPeriodEndDate'=>(isset($result->subscription->billingPeriodEndDate) && $result->subscription->billingPeriodEndDate!='')?$result->subscription->billingPeriodEndDate->format('Y-m-d H:i:s'):'',
		            'billingPeriodStartDate'=>(isset($result->subscription->billingPeriodStartDate) && $result->subscription->billingPeriodStartDate!='')?$result->subscription->billingPeriodStartDate->format('Y-m-d H:i:s'):'',
		            'firstBillingDate'=>$result->subscription->firstBillingDate->format('Y-m-d H:i:s'),
		            'subscriptionId'=>$result->subscription->id,
		            'nextBillAmount'=>$result->subscription->nextBillAmount,
		            'nextBillingDate'=>$result->subscription->nextBillingDate->format('Y-m-d H:i:s'),
		            'paymentMethodToken'=>$result->subscription->paymentMethodToken,
		            'planId'=>$result->subscription->planId
			    ); 
			    DB::collection('transaction_log')->insert($transactionLog);

			    $response['status'] = 1;
		    	$response['message'] = 'Success';
		    	return json_encode($response); 	    

			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        $response['status'] = 0;
		    		$response['message'] = $error->message;
		    		return json_encode($response); 	    
			    }
			}
		}
		catch (\Exception $e) {
		    $response['status'] = 0;
		    $response['message'] = $e->getMessage();
		    return json_encode($response); 	    
		}
    }

    public function packupgrade(Request $request) 
    {
    	$userId = $request->get('userId');
    	$package = $request->get('package');
    	$billingDate = $request->get('billingDate');
		$response = array();	

		try 
    	{
    		if($userId==''||$package==''||$billingDate=='') {
		    	$response['status'] = 0;
		    	$response['message'] = 'User Id, Package & Billing date is required';
		    	return json_encode($response); 	    
		    }

		    # package upgradation
		    # Effective Immediately
		    # get old package price to upgrade
		    $subscriptionId = $package.'_USR_'.$userId;
			$result = $this->gateway->subscription()->find($subscriptionId);
			//echo '<pre>'; print_r($result); die;

			if ($result->id!='') {

				$monthlyCost = $result->price;
				$nextBillingDate = $result->nextBillingDate->format('Y-m-d H:i:s');
				$paymentMethodToken = $result->paymentMethodToken;

				if($billingDate==0) {						
					$date1 = date('Y-m-d'); 
					$date2 = $nextBillingDate; 
					$diff = strtotime($date2) - strtotime($date1); 
					$remainingDays = abs(round($diff / 86400)); 
					$newPrice = ($monthlyCost/30)*$remainingDays;
					$newPrice = number_format($newPrice,2);					

					$result1 = $this->gateway->subscription()->update($subscriptionId, [
					    'id' => $subscriptionId,
					    'paymentMethodToken' => $paymentMethodToken,
					    'price' => $newPrice,
					    'planId' => 'm73b'
					]);
					$result2 = $this->gateway->transaction()->sale([
					  'amount' => $newPrice,
					  'paymentMethodNonce' => 'fake-valid-nonce',
					  'options' => [
					    'submitForSettlement' => false
					  ]
					]);
					echo '<pre>'; print_r($result1); echo '<hr/>'; print_r($result2); die;
				} else {
					$newPrice = $result->price;
					$result1 = $this->gateway->subscription()->update($subscriptionId, [
					    'id' => $subscriptionId,
					    'paymentMethodToken' => $paymentMethodToken,
					    'price' => $newPrice,
					    'planId' => 'm73b',
					    'firstBillingDate' => $billingDate
					]);
					$result2 = $this->gateway->transaction()->sale([
					  'amount' => $newPrice,
					  'paymentMethodNonce' => 'fake-valid-nonce',
					  'options' => [
					    'submitForSettlement' => true
					  ]
					]);
					echo '<pre>'; print_r($result1); echo '<hr/>'; print_r($result2); die;
				}

			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        $response['status'] = 0;
		    		$response['message'] = $error->message;
		    		return json_encode($response); 	    
			    }
			}			
		}
		catch (\Exception $e) {
		    $response['status'] = 0;
		    $response['message'] = $e->getMessage();
		    return json_encode($response); 	    
		}
    }

    public function packcancel(Request $request) 
    {
    	$userId = $request->get('userId');
    	$package = $request->get('package');
		$response = array();	

    	try 
    	{
    		if($userId==''||$package=='') {
		    	$response['status'] = 0;
		    	$response['message'] = 'User Id & Package is required';
		    	return json_encode($response); 	    
		    }

		    # package cancel
		    # get subscription id
			$subscriptionId = '';
			$subscriptionId = $package.'_USR_'.$userId;

			$result = $this->gateway->subscription()->find($subscriptionId);
			//echo '<pre>'; print_r($result); die;

			if ($result->id!='') 
			{
				# subscription cancel
				$result = $this->gateway->subscription()->cancel($subscriptionId);
				if ($result->success) {				
					$transactionLog = array(
				    	'userId'=>$userId,
			            'package'=>$package,
			            'balance'=>$result->subscription->balance,
			            'billingPeriodEndDate'=>(isset($result->subscription->billingPeriodEndDate) && $result->subscription->billingPeriodEndDate!='')?$result->subscription->billingPeriodEndDate->format('Y-m-d H:i:s'):'',
			            'billingPeriodStartDate'=>(isset($result->subscription->billingPeriodStartDate) && $result->subscription->billingPeriodStartDate!='')?$result->subscription->billingPeriodStartDate->format('Y-m-d H:i:s'):'',
			            'createdAt'=>$result->subscription->createdAt->format('Y-m-d H:i:s'),
			            'firstBillingDate'=>$result->subscription->firstBillingDate,
			            'id'=>$result->subscription->id,
			            'merchantAccountId'=>$result->subscription->merchantAccountId,
			            'nextBillAmount'=>$result->subscription->nextBillAmount,
			            'nextBillingDate'=>$result->subscription->nextBillingDate,
			            'paymentMethodToken'=>$result->subscription->paymentMethodToken,
			            'planId'=>$result->subscription->planId,
			            'price'=>$result->subscription->price,
			            'status'=>$result->subscription->status,
			            'cancelledDate'=>date('Y-m-d')
				    ); 
				    DB::collection('cancel_log')->insert($transactionLog);		

				    $response['status'] = 1;
			    	$response['message'] = 'Success';
			    	return json_encode($response); 	
			   	} else {
				    foreach($result->errors->deepAll() AS $error) {
				        $response['status'] = 0;
			    		$response['message'] = $error->message;
			    		return json_encode($response); 	    
				    }
				}
			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        $response['status'] = 0;
		    		$response['message'] = $error->message;
		    		return json_encode($response); 	    
			    }
			}		
		}
		catch (\Exception $e) {
		    $response['status'] = 0;
		    $response['message'] = $e->getMessage();
		    return json_encode($response); 	    
		}
    }
}
