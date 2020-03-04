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
<!-- Button to trigger modal -->
<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalForm">
    Braintree Payment
</button>
</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Braintree Payment</h4>
            </div>

            <form role="form" name="frmpayment" id="frmpayment" method="post" action="{{url('braintree/payment/transaction')}}">
	            @csrf
	            <!-- Modal Body -->
	            <div class="modal-body">
	                <p class="statusMsg"></p>                
	                     <div class="form-group row">
						    <label for="staticEmail" class="col-sm-4 col-form-label">Payment Gateway</label>
						    <div class="col-sm-8">
						      Braintree
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="inputPassword" class="col-sm-4 col-form-label">Amount</label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount" required>
						    </div>
						  </div>                
	            </div>            
	            <!-- Modal Footer -->
	            <div class="modal-footer" style="text-align: center;">
	                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
	                <button type="submit" class="btn btn-primary submitBtn" onclick="submitContactForm()">SUBMIT</button>
	            </div>
        	</form>

        </div>
    </div>
</div>

</body>
</html>