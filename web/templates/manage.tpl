<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang=""><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang=""><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{appname}} - Manage Servers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />
    <link rel="icon" type="image/png" href="favicon.ico" />
    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.slim.js"></script>
</head>
<body>
    <div id="socket_error" class="socket_error" style="visibility:hidden;padding:0px;"></div>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    {% include 'menu_top.tpl' %}
    {% include 'menu_sub.tpl' %}
    {% if gid == true and vid == true %}
    <div class="alert alert-info alert-info-vngreen" style="border-radius:0px;margin-top:0px;">
        <div class="container">
            <form role="form" class="form-inline">
                <div class="form-group">
                    <label>Server Quick Select: </label>
                    <select class="form-control" id="manquick">
                        <option value="{{base}}/manage">Select...</option>
                        {% for lxc in vmtable.lxc %}
                          {% if lxc.noLogin == false %}
                            <option value="{{base}}/manage?id={{lxc.hbid}}&virt=lxc">{{lxc.name}} - {{lxc.ip}}</option>
                          {% else %}
                            <option value="{{base}}/manage?id={{lxc.hbid}}&virt=lxc">server down - {{lxc.ip}}</option>
                          {% endif %}
                        {% endfor %}
                        {% for kvm in vmtable.kvm %}
                          {% if kvm.noLogin == false %}
                            <option value="{{base}}/manage?id={{kvm.hbid}}&virt=kvm">{{kvm.name}} - {{kvm.ip}}</option>
                          {% else %}
                            <option value="{{base}}/manage?id={{kvm.hbid}}&virt=kvm">server down - {{kvm.ip}}</option>
                          {% endif %}
                        {% endfor %}
                    </select>
                </div>
                <a href="manage" class="btn btn-info btn-sm" id="manquickgo">Go</a>
            </form>
        </div>
    </div>
    {% endif %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
