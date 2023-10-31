{% include 'header-noauth.tpl' %}
    <div class="container-full" id="blocks">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3 login-box">
                    <form role="form" action="" method="POST">
                        <fieldset>
                            <h2>{{appname}} Password Reset</h2>
                            {% if not errors is empty %}
                              <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
                            {% endif %}
                            <hr class="pulse">
                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Username">
                            </div>
                            <hr class="pulse">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input type="submit" class="btn btn-lg btn-success btn-block" value="Reset password">
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <a class="btn btn-lg btn-default btn-block" href="login">Back</a>
                                </div>
                            </div>
                        </fieldset>
                        <input type="hidden" name="token" value="{{formToken}}" />
                    </form>
