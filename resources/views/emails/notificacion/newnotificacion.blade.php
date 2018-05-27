@component('mail::message')
# Fintech

Su Estatus ha cambiado a **ERROR**.

@extends('emails.layouts.app')

@section('content')
<div class="row">
	<table class="subcopy" width="100%" cellpadding="0" cellspacing="0">
		<tr>
	    	<td>
	    		<table style="margin: 30px auto; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;"  align="center" width="100%" cellpadding="0" cellspacing="0">
				    <tr>
				        <td align="center">
				            <table width="100%" border="0" cellpadding="0" cellspacing="0">
				                <tr>
				                    <td align="center">
				                        <table border="0" cellpadding="0" cellspacing="0">
				                            <tr>
				                                <td>
				                                	<p class="p">
				                                		@foreach ($data as $element)

 														<p>Existe un ERROR, valide la información del siguiente usuario: {{$element->user->name}}</p>
 															<p>{{$element->user->email}}</p>
 															<p>{{$element->user->rfc}}</p>

														@endforeach

				                                	</p>
				                                </td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
				</table>
	    	</td>
	    </tr>
	</table>
</div>
@endsection

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
