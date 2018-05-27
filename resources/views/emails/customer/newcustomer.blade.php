<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Contacto Capacitaciones CIEC</title>
  <style type="text/css">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 40px 30px 20px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #153643; font-family: sans-serif; letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 30px;}
  .footercopy {font-family: sans-serif; font-size: 14px; color: #153643;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td bgcolor="#f5f5f5" class="header">
         <!-- <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img class="fix" src="{{url('assets/logo.png')}}" width="35" height="35" border="0" alt="" />
              </td>
            </tr>
          </table> -->

          <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 30%; max-width: 425px;">
            <tr>
              <td height="70">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      <img class="img img-resposinve" width="80px" src="{{ asset('assets/logo.png')}}">
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="h2">
                Nueva empresa Registrada
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="115" style="padding: 0 20px 20px 0;">
                <img class="fix" src="{{url('images/hands-on-keyboard.jpg')}}" width="115" height="115" border="0" alt="" />
              </td>
            </tr>
          </table>
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;">
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bodycopy">
                      Se ha registrado una nueva empresa.
                      <br/><br/>
                      {{-- Empresa: {{$data->name}} --}}
                      <br/>
                      {{-- RFC: {{$data->rfc}} --}}
                      <br/>
                      {{-- Dirección de Facturación: {{$data->address}} --}}
                      <br />
                      {{-- Nombre y Apellido de la persona de contacto: {{$data->cp_first_name ." ". $data->cp_last_name}} --}}
                      <br />
                        {{-- Email de la persona de contacto: {{$data->cp_email}} --}}
                      <br />
                        {{-- Telefono de la persona de contacto: {{$data->cp_phone}} --}}
                    </td>
                  </tr>
                  <tr>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="footer" bgcolor="#f5f5f5">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="footercopy">
                Copyright &reg; 2018 Fintech. Todos los derechos reservados. <br/>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>

<!--analytics-->
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://tutsplus.github.io/github-analytics/ga-tracking.min.js"></script>
</body>
</html>
