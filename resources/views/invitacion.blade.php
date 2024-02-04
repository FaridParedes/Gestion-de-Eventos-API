<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style type="text/css">
        body {
            font-family: 'Verdana', sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007BFF;
            padding: 20px 0;
        }
        .header h1 {
            color: #ffffff;
            font-size: 28px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .button {
            text-align: center;
            margin-top: 20px;
        }
        .button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Saludos!</h1>
        </div>
        <div class="content">
            <p>Te invitamos a participar en el siguiente evento:</p>
            <p><a href="{{$enlace}}" target="_blank">{{$enlace}}</a></p>
        </div>
        <div class="button">
            <a href="{{$enlace}}" target="_blank">Inscríbete Ahora</a>
        </div>
    </div>
</body>
</html>