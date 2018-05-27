@extends('layouts.app')

@section('content')
    <!-- services section -->
<section id="" class="margen-secciones ">
    <div class="row">
      <div class="col-md-12 col-sm-12 services text-center"> <span class="icon icon-gears"></span>
        <div class="services-content">
          <h3><strong>MODULOS ADMINISTRATIVOS</strong></h2>
            <br>

        </div>
      </div>
    </div>
    @if(Auth::user()->role=='administrador')
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12   services text-center">
            <div id="img-administracion">
                <img class="img-admin" src="img/empresa_azul.png" alt="">    
            </div>  
            
            <div class="services-content">
                <a href="{{route('customer/index')}}"><h5>Gestión de Empresas</h5></a>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 col-xs-12  services text-center">
            <div id="img-administracion">
                <img class="img-admin" src="img/usuarios_azul.png" alt="">
            </div>  
            
            <div class="services-content">
                <a href="{{route('user/index')}}"><h5>Gestión de usuarios</h5></a>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 col-xs-12  services text-center">
            <div id="img-administracion">
                <img class="img-admin" src="img/banco_azul.png" alt="">   
            </div>  
            
            <div class="services-content">
             <a href="{{route('bank/index')}}"><h5>Gestión de Bancos</h5></a>
            </div>
        </div>        
    </div>

    <div class="row">

        <div class="col-md-6 col-sm-12 col-xs-12  services text-center">
            <div id="img-administracion">
                <img class="img-admin" src="img/cuentas_azul.png" alt="">
            </div>  
            
            <div class="services-content">
                <a href="{{route('banxico/index')}}"><h5>Gestión de banxico</h5></a>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12  services text-center">
            <div id="img-administracion">
                <img  class="img-admin" src="img/banxico_azul.png" alt="">   
            </div>  
            
            <div class="services-content">
                <a href="{{route('account/index')}}"><h5>Gestión de cuentas</h5></a>
            </div>
        </div>        
    </div>    
@endif
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12   services text-center">
            
        </div>

        <div class="col-md-4 col-sm-12 col-xs-12  services text-center">
            <div id="img-administracion">
                <img class="img-admin" src="img/usuarios_azul.png" alt="">
            </div>  
            
            <div class="services-content">
                <a href="{{route('employee/index')}}"><h5>Gestión de empleados</h5></a>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 col-xs-12  services text-center">
            
        </div>        
    </div>
</section>
<!-- services section --> 
@endsection
