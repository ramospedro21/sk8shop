<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Pendencia de documentos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

</head>

<body style="background:#f3f3f3; font-family: 'Roboto', sans-serif;">

    <table style=" margin: 0 auto; ">
        <thead style="width: 500px">
            <tr>
                <td style="text-align: center; ">
                    <img src="{{ url('images/logo_sk8shop.png') }}" style="width: 200px !important">
                </td>
            </tr>
            <tr>
                <td style="text-align: center; background-color: #FFF;">
                  <p style="font-weight: bold; font-size: 20px; color: #000; height: 10px !important; margin-top: 3px;">Obrigado !</p>
                </td>
            </tr>
        </thead>
        <tbody style="text-align:center; width: 500px">
            <tr>
                <td><h1 style="font-size:21px; font-weight: bold; color: #000; margin:30px 60px;">Olá, {{ $user->name }}!</h1></td>
            </tr>
            <tr>
                <td>
                    <p style="font-size:14px; margin:30px 60px;">
                        Obrigado por se registrar na nossa plataforma
                    </p>
                    <p style="font-size:14px; margin:30px 60px;">
                        aproveite as nossas promoções
                    </p>

                    <br>
                </td>
            </tr>
        </tbody>
        <tfoot style="background: #FFF; text-align:center; color:#ccc; font-size:12px; width: 500px">
            <tr>
                <td style="padding:20px 0;">
                    <img src="{{ url('imgs/instagram.png') }}" style="width: 20px !important">
                    <img src="{{ url('imgs/facebook.png') }}" style="width: 10px !important; margin-left: 15px !important;">
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
