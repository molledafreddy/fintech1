@component('mail::message')
# Fintech

Su cuenta relacionada a la empresa: {{$customer}}.
esta lista para usar, ingrese al portal dando click al boton
para activar su cuenta.


@component('mail::button', ['url' => $url])
	Activar cuenta
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
