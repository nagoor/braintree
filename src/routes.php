<?php
Route::get('braintree', function(){
	echo 'Braintee package';
});

Route::get('braintree/signup', 'nextl\braintree\BraintreeController@signup');
Route::get('braintree/packupgrade', 'nextl\braintree\BraintreeController@packupgrade');
Route::get('braintree/packcancel', 'nextl\braintree\BraintreeController@packcancel');