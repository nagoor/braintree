<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="E=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Braintree-Transaction</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

@include('braintree::transact_menu')

<div class="container" style="width:50%;">	
	<h4>{{Session::get('title')}}</h4>
	<div class="form-group">
	@if(Session::get('transaction_id'))
    <div style="color:black;"><b>Transaction Id:</b> {{Session::get('transaction_id')}}</div>
    @endif
    @if(Session::get('customer_id'))
    <div style="color:black;"><b>Customer Id:</b> {{Session::get('customer_id')}}</div>
    @endif
 	<div style="color:black;"><b>Message: </b>{{Session::get('success')}}</div>
 	<div style="margin-top:20px;"><a href="/{{Session::get('url')}}">Back</a></div>
</div>
  
</body>
</html>

