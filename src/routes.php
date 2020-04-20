<?php
Route::get('braintree', function(){
	echo 'Braintee package';
});

Route::get('braintree/signup', 'nextl\braintree\BraintreeController@signup');
