<?php
Route::get('braintree', function(){
	echo 'Hello from the nextl braintree package!';
});

Route::get('braintree/payment', 'nextl\braintree\BraintreeController@index');
Route::post('braintree/payment/transaction', 'nextl\braintree\BraintreeController@transaction');