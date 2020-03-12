<?php
Route::get('braintree', function(){
	return view('braintree::braintree');
});

Route::get('braintree/payment', 'nextl\braintree\BraintreeController@index');
Route::post('braintree/payment/transaction', 'nextl\braintree\BraintreeController@transaction');

Route::get('braintree/transaction', 'nextl\braintree\BraintreeController@transaction');
Route::get('braintree/transaction/{link}', 'nextl\braintree\BraintreeController@transaction_link');
Route::post('braintree/transaction/refund', 'nextl\braintree\BraintreeController@transaction_refund');
Route::post('braintree/transaction/sale', 'nextl\braintree\BraintreeController@transaction_sale');
Route::post('braintree/transaction/search', 'nextl\braintree\BraintreeController@transaction_search');
Route::get('braintree/transact/all', 'nextl\braintree\BraintreeController@transaction_all');
Route::get('braintree/customer', 'nextl\braintree\BraintreeController@customer');
Route::post('braintree/customer/create', 'nextl\braintree\BraintreeController@create_customer');
