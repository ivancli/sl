<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media only screen and (max-width: 650px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 600px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0">
                    {{ $header or '' }}

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="650" cellpadding="0" cellspacing="0">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell" colspan="2">
                                        <div class="info-box">
                                            <p class="header-img-container">
                                                <img src="{{asset('images/header.png')}}" alt="" width="100%">
                                            </p>

                                            {{ Illuminate\Mail\Markdown::parse($slot) }}

                                            {{ $subcopy or '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-cell" width="50%">
                                        {{ $lower_body_1 or '' }}
                                    </td>
                                    <td class="content-cell" width="50%">
                                        {{ $lower_body_2 or '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{ $footer or '' }}
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
