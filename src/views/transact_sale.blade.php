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

<div class="container" style="width:50%">

  <h4>Transaction Sales</h4>
  <form class="form-horizontal" method="post" action="/braintree/transaction/sale">
  <div class="form-group">
    <div class="col-sm-12">
     <input type="text" class="form-control" id="amount" placeholder="Enter Amount" name="amount" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form> 
</div>
  
</body>
</html>


