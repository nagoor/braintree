<?php

namespace nextl\braintree;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Braintree;
use Redirect;
use Session;


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

    public function index(Request $request)
	{
		return view('braintree::index');
	}

	public function transaction(Request $request)
	{
		return view('braintree::transaction');
	}

	public function transaction_link(Request $request) 
	{
		$link = $request->segment(3);
		return view('braintree::'.$link);
	}

	public function transaction_refund(Request $request) 
	{
		$transaction_id = $request->get('transaction_id');
		try {
			$result = $this->gateway->transaction()->refund($transaction_id);
			$msg = '';
			if(isset($result->errors)) {
				foreach ($result->errors->deepAll() as $key => $value) {
					$msg = $value->message;
				}
			}
			if(isset($result->success) && $result->success==1) {
				$msg = 'Refund Success!!';
			}
			Session::put('title', 'Refund Transaction');
			Session::put('transaction_id', $transaction_id);
			Session::put('success', $msg);
			Session::put('url', 'braintree/transaction/transact_refund');
			return view('braintree::success');			
		} catch(Braintree\Exception $e) {
			Session::put('title', 'Refund Transaction');
			Session::put('transaction_id', $transaction_id);
			Session::put('error', 'Invalid Transaction Id');
			Session::put('url', 'braintree/transaction/transact_refund');
			return view('braintree::errors');
		}
	}

	public function transaction_sale(Request $request)
	{
	    //dd($request->all());
	    $inputs = $request->all();
	    
	    try {
			$result = $this->gateway->transaction()->sale([
			  'amount' => $inputs['amount'],
			  'orderId' => rand(1,500000),
			  'merchantAccountId' => 'testnm',
			  'paymentMethodNonce' => 'fake-valid-nonce',
			  'options' => [
			    'submitForSettlement' => true
			  ]
			]); //echo '<pre>'; print_r($result); die;
			$msg = '';
			if(isset($result->errors)) {
				foreach ($result->errors->deepAll() as $key => $value) {
					$msg = $value->message;
				}
			}
			if(isset($result->success) && $result->success==1) {
				$msg = 'Transaction Success!!';
			}
			Session::put('title', 'Transaction Sales');
			Session::put('transaction_id', @$result->transaction->id);
			Session::put('success', $msg);
			Session::put('url', 'braintree/transaction/transact_sale');
			return view('braintree::success');			
		} catch(Braintree\Exception $e) {
			Session::put('title', 'Transaction Sales');
			Session::put('error', $msg);
			Session::put('url', 'braintree/transaction/transact_sale');
			return view('braintree::errors');
		}
	}

	public function transaction_search(Request $request)
	{
	    //dd($request->all());
		$transaction_id = $request->get('transaction_id');

		try {
	    	$transaction = $this->gateway->transaction()->find($transaction_id);
	    	//echo '<pre>'; print_r($transaction); die;
	    	return view('braintree::viewtransaction',compact('transaction'));
	    } catch(Braintree\Exception $e) {
			Session::put('title', 'Transaction Search');
			Session::put('transaction_id', $transaction_id);
			Session::put('error', "Transaction id isn't found");
			Session::put('url', 'braintree/transaction/transact_search');
			return view('braintree::errors');
		}
	}

	public function transaction_all(Request $request)
	{
		return redirect()->back();	
	}

	public function customer()
	{
		return view('braintree::customer');
	}

	public function create_customer(Request $request)
	{
	    //dd($request->all());
		$inputs = $request->all();

		try {
		    $result = $this->gateway->customer()->create([
			    'firstName' => $inputs['firstName'],
			    'lastName' => $inputs['lastName'],
			    'company' => $inputs['company'],
			    'email' => $inputs['email'],
			    'phone' => $inputs['phone'],
			    'fax' => $inputs['fax'],
			    'website' => $inputs['website']
			]);
		    $msg = '';
			if(isset($result->errors)) {
				foreach ($result->errors->deepAll() as $key => $value) {
					$msg = $value->message;
				}
			}
			if(isset($result->success) && $result->success==1) {
				$msg = 'Customer Success!!';
			}
			Session::put('title', 'Create Customer');
			Session::put('customer_id', @$result->customer->id);
			Session::put('success', $msg);
			Session::put('url', 'braintree/customer');
			return view('braintree::customersuccess');			
		} catch(Braintree\Exception $e) {
			Session::put('title', 'Create Customer');
			Session::put('error', $msg);
			Session::put('url', 'braintree/customer');
			return view('braintree::customererrors');
		}
	}
}
