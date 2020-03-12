<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="E=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Braintree-Customer</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

@include('braintree::customer_menu')

<div class="container" style="width:40%">

  <h4>Create Customer</h4>
  <form class="form-horizontal" method="post" action="/braintree/customer/create">
  <div class="form-group">
    <div class="col-sm-12">
     <input type="text" class="form-control" id="firstName" placeholder="Enter firstname" name="firstName" required>
    </div><br/><br/>
    <div class="col-sm-12">
     <input type="text" class="form-control" id="lastName" placeholder="Enter lastname" name="lastName" required>
    </div><br/><br/>
    <div class="col-sm-12">
     <input type="text" class="form-control" id="company" placeholder="Enter company" name="company" required>
    </div><br/><br/>
    <div class="col-sm-12">
     <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div><br/><br/>
    <div class="col-sm-12">
     <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone" required>
    </div><br/><br/>
    <div class="col-sm-12">
     <input type="text" class="form-control" id="fax" placeholder="Enter fax" name="fax" required>
    </div><br/><br/>
    <div class="col-sm-12">
     <input type="text" class="form-control" id="website" placeholder="Enter website" name="website" required>
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

