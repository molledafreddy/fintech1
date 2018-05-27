@extends('layouts.app')
@section('content')
   <div class="container">
        <form id="form-validation" action="{{ route('file-validate-account') }}" method="POST" >
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default" style="margin-top:40px">
                        <div class="panel-heading" style="padding-top: 5px; ">
                            <div align="center">
                                <h3>Generar archivos</h3> 
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="form-group col-md-6 col-md-offset-2" style="padding-left:2px; padding-top: 17px; padding-right:2px; !important;">
                                     <select id="type" name="type" class="form-control" data-bv-field="type" required="required">
                                        <option disabled value="">Seleccione tipo de archivo</option>
                                        <option :value="1">Banco Santander</option>
                                        <option :value="2">Otros Bancos</option>
                                    </select>
                                </div>
                                <div class="col-md-2 " style="padding-left:2px; padding-top: 19px;  !important;">
                                    <button type="submit" class="btn btn-success">Generar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </form>
    </div>
@endsection

