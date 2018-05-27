@extends('layouts.app')

@section('content')
	<div class="row">
		<transferfile :id="{{ $id }}"></transferfile>
	</div>
@endsection