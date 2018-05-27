@extends('layouts.app')

@section('content')
	<div class="row">
		<usershow :id="{{ $id }}"></usershow>
	</div>
@endsection