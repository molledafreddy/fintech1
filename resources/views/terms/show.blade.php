@extends('layouts.app')

@section('content')
	<div class="row">
		<terms :id="{{ $id }}"></terms>
	</div>
@endsection