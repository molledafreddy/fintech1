@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Bienvenido, Fintech
                    <ul>
                        <li class="col-md-4">Registro de Empresas<i class="fa fa-building  fa-5x" aria-hidden="true"></i></li>
                        <li class="col-md-4">Registro de empleado<i class="fa fa-user  fa-5x" aria-hidden="true"></i></li>
                    </ul>

                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
