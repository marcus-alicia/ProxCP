<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang=""><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang=""><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{appname}} - {{pagename}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />
    <link href='css/custom.css' rel='stylesheet' type='text/css' />
    <link rel="icon" type="image/png" href="favicon.ico" />
    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <style type="text/css">
        body {
            background: #fff url("img/circuit-pattern-white.png") repeat-y 50% 250%;
            background-size: 100%;
        }
    </style>
</head>
<body>
    {% if not gethttps %}
    <div id="socket_error" class="socket_error" style="visibility:visible;padding:10px;">Insecure connection (non-HTTPS)!</div>
    {% endif %}
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    {% include 'menu_top_noauth.tpl' %}
