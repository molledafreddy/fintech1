@extends('layouts.app')
@section('title', ' Error 404')

@section('content')
<div class="container margen-components">
    <div class="row">
        <div class="col-md-12 ">
            <div class="text-center robot" style="padding-bottom: 30px;padding-top: 40px;">
                <img  class="img-admin" src="img/404.png" alt="server break"> 
            </div> 
            <div class="panel-body">
                <div class="text-center">
                    <div class="error_type hidden-xs">404</div>
                    <p class="error hidden-xs">error</p>
                    <div class="error_msg"><p>Disculpe, la pagina que busca no se encuentra disponible</p></div>
                    <hr class="seperator">
                    <a href="{{ url('/') }}" class="btn btn-primary">Ir al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
