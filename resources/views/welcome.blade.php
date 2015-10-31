@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div>Laravel 5</div>
				<div>{{ Inspiring::quote() }}</div>
			</div>
		</div>
	</div>
</div>
@endsection