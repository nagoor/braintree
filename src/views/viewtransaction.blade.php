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
	<h4>Transaction Id: {{$transaction->id}}</h4><br/>
	<div class="list-group" style="width:80%;">
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-camera"></i> Merchant <span class="pull-right">{{$transaction->merchantAccountId}}</span>
    </a>
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-music"></i> Merchant Account <span class="pull-right">{{$transaction->merchantAccountId}}</span>
    </a>
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Transaction Type <span class="pull-right">{{$transaction->type}}</span>
    </a>
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Amount <span class="pull-right">{{$transaction->amount}}</span>
    </a>
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Transaction Date <span class="pull-right">{{$transaction->createdAt->format('Y-m-d H:i:s')}}</span>
    </a>
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Order Id <span class="pull-right">{{$transaction->orderId}}</span>
    </a>
    <a href="javascript:" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Status <span class="pull-right">{{$transaction->status}}</span>
    </a>
</div>
</div>

<div align="center"><a href="/braintree/transaction/transact_search" class="btn btn-sm btn-primary">Back</a></div>
  
</body>
</html>

