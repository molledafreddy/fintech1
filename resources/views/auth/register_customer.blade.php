@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register Customer</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register-customer.store') }}">
                        {{ csrf_field() }}
                        
                       <input type="hidden" name="register" value="true">
                       <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="admin_id" value="1">
                        <div class="form-group{{ $errors->has('web_site') ? ' has-error' : '' }}">
                            <label for="web_site" class="col-md-4 control-label">Sitio Web</label>

                            <div class="col-md-6">
                                <input id="web_site" type="text" class="form-control" name="web_site" value="{{ old('web_site') }}" autofocus>

                                @if ($errors->has('web_site'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('web_site') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        

                        <div class="form-group{{ $errors->has('rfc') ? ' has-error' : '' }}">
                            <label for="rfc" class="col-md-4 control-label">RFC</label>

                            <div class="col-md-6">
                                <input id="rfc" type="text" class="form-control" name="rfc" value="{{ old('dni') }}" autofocus>

                                @if ($errors->has('rfc'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rfc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Teléfono</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Dirección de Facturación</label>

                            <div class="col-md-6">
                                <textarea id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" autofocus>
                                </textarea>

                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">Ciudad</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>

                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cp_first_name') ? ' has-error' : '' }}">
                            <label for="cp_first_name" class="col-md-4 control-label">Nombre de persona de contacto</label>

                            <div class="col-md-6">
                                <input id="cp_first_name" type="text" class="form-control" name="cp_first_name" value="{{ old('cp_first_name') }}" required>

                                @if ($errors->has('cp_first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cp_first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cp_last_name') ? ' has-error' : '' }}">
                            <label for="cp_last_name" class="col-md-4 control-label">Apellido de persona de contacto</label>

                            <div class="col-md-6">
                                <input id="cp_last_name" type="text" class="form-control" name="cp_last_name" value="{{ old('cp_last_name') }}" required>

                                @if ($errors->has('cp_last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cp_last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cp_email') ? ' has-error' : '' }}">
                            <label for="cp_email" class="col-md-4 control-label">Correo de persona de contacto</label>

                            <div class="col-md-6">
                                <input id="cp_email" type="text" class="form-control" name="cp_email" value="{{ old('cp_email') }}" required>

                                @if ($errors->has('cp_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cp_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cp_phone') ? ' has-error' : '' }}">
                            <label for="cp_phone" class="col-md-4 control-label">Teléfono de persona de contacto</label>

                            <div class="col-md-6">
                                <input id="cp_phone" type="text" class="form-control" name="cp_phone" value="{{ old('cp_phone') }}" required>

                                @if ($errors->has('cp_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cp_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
