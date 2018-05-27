@extends('layouts.app')

@section('content')
	<div class="row">
		<accountshow :id="{{ $id }}"></accountshow>
	</div>
@endsection