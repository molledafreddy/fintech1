@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div align="center" class="panel-heading">{{ $error }}</div>

                <div class="panel-body">
                    <div class="text-center">               
                        <a href="{{ url($url) }}"  type="button" class="btn btn-warning">Atras</a>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
