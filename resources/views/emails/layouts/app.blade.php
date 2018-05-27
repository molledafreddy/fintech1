<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }

        body, body *:not(html):not(style):not(br):not(tr):not(code) {
            font-family: Avenir, Helvetica, sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #d5e0e8;
            color: #74787E;
            height: 100%;
            hyphens: auto;
            line-height: 1.4;
            margin: 1110;
            -moz-hyphens: auto;
            -ms-word-break: break-all;
            width: 100% !important;
            -webkit-hyphens: auto;
            -webkit-text-size-adjust: none;
            word-break: break-all;
            word-break: break-word;
        }

        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }

        a {
            color: #3869D4;
        }
        
        .body {
            background-color: #FFFFFF;
            border-bottom: 1px solid #EDEFF2;
            border-top: 1px solid #EDEFF2;
            margin: 0;
            padding: 0;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .header {
            padding: 25px 0;
            text-align: center;
        }
        .wrapper {
            background-color: #373c42;
            margin: 0;
            padding: 0;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .content {
            margin: 0;
            padding: 0;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .inner-body {
            background-color: #FFFFFF;
            margin: 0 auto;
            padding: 0;
            width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 570px;
        }

        .content-cell {
            margin-top: 25px;
            padding: 25px;
        }

        .footer {
            margin: 0 auto;
            padding: 0;
            text-align: center;
            width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 570px;
        }

        .p {
            margin-left: 14px;
            
        }

        .capitalize {
            text-transform: capitalize;
        }

        .table table {
            margin: 30px auto;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .table th {
            border-bottom: 1px solid #EDEFF2;
            padding-bottom: 8px;
        }

        .table td {
            color: #74787E;
            font-size: 15px;
            line-height: 18px;
            padding: 10px 0;
        }

        .button {
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            color: #FFF;
            display: inline-block;
            text-decoration: none;
            -webkit-text-size-adjust: none;
        }

        .button-blue {
            background-color: #3097D1;
            border-top: 10px solid #3097D1;
            border-right: 18px solid #3097D1;
            border-bottom: 10px solid #3097D1;
            border-left: 18px solid #3097D1;
        }
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0">
                    @include('emails.layouts.nav')

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        @yield('content')
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>
        @include('emails.layouts.footer')
    </table>
</body>
</html>
