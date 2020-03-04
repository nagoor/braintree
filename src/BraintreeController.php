<?php

namespace nextl\braintree;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Braintree;


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
	    //dd($request->all());
	    $inputs = $request->all();
	    
	    //$gateway = $this->gateway;
		// echo '<pre>';
		// print_r($paymentMethod);
		// die;

		// Then, create a transaction:
		$result = $this->gateway->transaction()->sale([
		  'amount' => $inputs['amount'],
		  'orderId' => rand(1,500000),
		  'merchantAccountId' => 'testnm',
		  'paymentMethodNonce' => 'fake-valid-nonce',
		  //'deviceData' => $deviceDataFromTheClient,
		  // 'customer' => [
		  //   'firstName' => 'Drew',
		  //   'lastName' => 'Smith',
		  //   'company' => 'Braintree',
		  //   'phone' => '312-555-1234',
		  //   'fax' => '312-555-1235',
		  //   'website' => 'http://www.example.com',
		  //   'email' => 'drew@example.com'echo '<a href="'.url('braintree/payment').'">Back to payment</a>';
		  // ],
		  // 'billing' => [
		  //   'firstName' => 'Paul',
		  //   'lastName' => 'Smith',
		  //   'company' => 'Braintree',
		  //   'streetAddress' => '1 E Main St',
		  //   'extendedAddress' => 'Suite 403',
		  //   'locality' => 'Chicago',
		  //   'region' => 'IL',
		  //   'postalCode' => '60622',
		  //   'countryCodeAlpha2' => 'US'
		  // ],
		  // 'shipping' => [
		  //   'firstName' => 'Jen',
		  //   'lastName' => 'Smith',
		  //   'company' => 'Braintree',
		  //   'streetAddress' => '1 E 1st St',
		  //   'extendedAddress' => 'Suite 403',
		  //   'locality' => 'Bartlett',
		  //   'region' => 'IL',
		  //   'postalCode' => '60103',
		  //   'countryCodeAlpha2' => 'US'
		  // ],
		  'options' => [
		    'submitForSettlement' => true
		  ]
		]);

		if ($result->success) {
			echo '<pre>';
		    print_r("success!: " . $result->transaction->id);
		    echo '<br/><br/><a href="'.url('braintree/payment').'" style="color: red;">Back to payment</a>';
		} else if ($result->transaction) {
			echo '<pre>';
		    print_r("Error processing transaction:");
		    print_r("\n  code: " . $result->transaction->processorResponseCode);
		    print_r("\n  text: " . $result->transaction->processorResponseText);
		    echo '<a href="'.url('braintree/payment').'" style="color: red;">Back to payment</a>';
		} else {
			echo '<pre>';
		    print_r("Validation errors: \n");
		    print_r($result->errors->deepAll());
		    echo '<a href="'.url('braintree/payment').'" style="color: red;">Back to payment</a>';
		}
	}
}
