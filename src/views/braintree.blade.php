<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="E=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Braintree-Demo</title>
<!-- Latest minified bootstrap css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<!-- Latest minified bootstrap js -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12" style="margin-top: 20px; text-align:center;">
<div id="dropin-container"></div>
<h3>Braintee Payment Gateway</h3><br/>
<div class="row">
    <div class="col-sm-12 text-center">
        <button id="btnTransaction" class="btn btn-primary btn-md center-block" style="width: 100px;" onclick="window.location='{{url('braintree/transaction')}}'">Transaction</button>
         <button id="btnCustomer" class="btn btn-danger btn-md center-block" style="width: 100px;" onclick="window.location='{{url('braintree/customer')}}'">Customer</button>
     </div>
</div>
</div>
</div>
</div>

<style type="text/css">
#btnTransaction, #btnCustomer{
    display: inline-block;
    vertical-align: top;
}
</style>

</body>
</html>