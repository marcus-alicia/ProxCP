<nav class="navbar navbar-default" id="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"><img src="img/logo.png" class="img-responsive" /></a>
        </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right nav-elem" id="bottom-nav">
                    <li><a href="index"><i class="fa fa-dashboard fa-fw"></i> {{ L.navbar.dashboard }}</a></li>
                    {% if enable_firewall == 'true' %}
                      <li><a href="firewall"><i class="fa fa-key fa-fw"></i> {{ L.navbar.firewall }}</a></li>
                    {% endif %}
                    {% if enable_forward_dns == 'true' or enable_reverse_dns == 'true' %}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-arrows-h fa-fw"></i> DNS <span class="caret"></span></a>
                        <ul class="dropdown-menu subNav" role="menu">
                            {% if enable_forward_dns == 'true' %}
                              <li><a href="forward_dns"><i class="fa fa-arrow-right fa-fw"></i> Forward DNS</a></li>
                            {% endif %}
                            {% if enable_reverse_dns == 'true' %}
                              <li><a href="reverse_dns"><i class="fa fa-arrow-left fa-fw"></i> Reverse DNS</a></li>
                            {% endif %}
                        </ul>
                    </li>
                    {% endif %}
                    {% if enable_notepad == 'true' %}
                      <li><a href="notes"><i class="fa fa-pencil fa-fw"></i> {{ L.navbar.notepad }}</a></li>
                    {% endif %}
                    {% if enable_status == 'true' %}
                      <li><a href="nodes"><i class="fa fa-tasks fa-fw"></i> {{ L.navbar.status }}</a></li>
                    {% endif %}
                    {% if isAdmin %}
                      <li><a href="{{adminBase}}">{{ L.navbar.ADMIN }}</a></li>
                    {% endif %}
                </ul>
            </div>
    </div>
</nav>
