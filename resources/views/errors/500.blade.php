@extends('layouts.app')

@section('title', ' Error 404')
@section('styles')

    <!-- end of page level styles-->
@stop
@section('content')
        <div class="err-cont">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <div class="row">
                    <div class="text-center robot" style="padding-bottom: 30px;padding-top: 40px;">
                        <img  class="img-admin" src="img/404.png" alt="server break"> 
                    </div> 
                    <div class="panel-body">
                        <div class="text-center">
                            <div class="error_type hidden-xs">500</div>
                            <p class="error hidden-xs">error</p>
                            <div class="error_msg"><p>Oops! Ah ocurrido un error Interno en el Servidor</p></div>
                            <hr class="seperator">
                            <a href="{{ url('/') }}" class="btn btn-primary">Ir al inicio</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
@section('script')
@endsection