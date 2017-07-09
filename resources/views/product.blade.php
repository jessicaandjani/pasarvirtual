<!DOCTYPE html>
<html>
<head>
	<title>Laravel 5.3 Image Upload with Validation example</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>

<div class="container">
<div class="panel panel-primary">
  <div class="panel-heading"><h2>Laravel 5.3 Image Upload with Validation example</h2></div>
  <div class="panel-body">

	  	@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@if ($message = Session::get('success'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">×</button>
		        <strong>{{ $message }}</strong>
		</div>
		<img src="/images/{{ Session::get('path') }}">
		@endif

		<form action="{{ url('product/add') }}" enctype="multipart/form-data" method="POST">
			{{ csrf_field() }}
			<input type="hidden" name="id" value="{{isset($product->id)? $product->id:''}}">
			<div class="row">
				<div class="col-md-12">
					<input type="text" name="product_name" />
				</div>
				<div class="col-md-12">
					<input type="text" name="category_id" />
				</div>
				<div class="col-md-12">
					<input type="number" name="product_quantity" />
				</div>
				<div class="col-md-12">
					<input type="text" name="unit_id" />
				</div>
				<div class="col-md-12">
					<input type="number" name="product_price" />
				</div>
				<div class="col-md-12">
					<input type="file" name="product_image" />
				</div>
				<div class="col-md-12">
					<button type="submit" class="btn btn-success">Upload</button>
				</div>
			</div>
		</form>

  </div>
</div>

</div>

</body>
</html>