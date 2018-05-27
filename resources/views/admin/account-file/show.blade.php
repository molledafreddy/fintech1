@extends('layouts.app')

@section('content')
	<div class="row">
		<accountfile :id="{{ $id }}"></accountfile>
	</div>
@endsection