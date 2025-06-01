<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Mail</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f6f6f6;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; padding: 40px; border-radius: 10px;">
                    <!-- <tr>
                        <td align="center" style="font-size: 28px; font-weight: bold; padding-bottom: 20px;">
                            Demande de congé
                        </td>
                    </tr> -->
                    <tr>
                        <td align="center" style="font-size: 28px; font-weight: bold; padding-bottom: 20px;">
                            {{ $name }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 16px; padding-bottom: 30px;">
                            {{ $text }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="{{'https://congefdsut.com/' }}" style="background-color: #233C6C; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                Se connecter
                            </a>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td align="center" style="padding-top: 30px;">
                            <img src="{{ asset('logo.png') }}" alt="Logo" width="100">
                        </td>
                    </tr> -->
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
