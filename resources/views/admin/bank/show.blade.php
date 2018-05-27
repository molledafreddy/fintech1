@extends('layouts.app')

@section('content')
	<div class="row">
		<bankshow :id="{{ $id }}"></bankshow>
	</div>
@endsection