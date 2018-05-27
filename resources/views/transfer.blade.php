@extends('layouts.app')

@section('content')
<div class="container">
    <div class="margen-components">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div align="center" class="panel-heading">Generar archivos de transferencia</div>

                <div class="panel-body">
                    <div class=" text-center">               
                        <a href="{{ route('santander-file.generate') }}"  type="button" class="btn btn-primary">Banco santander</a>                 
                        <a href="{{ route('transfer') }}"  type="button" class="btn btn-primary">Otros bancos</a>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
