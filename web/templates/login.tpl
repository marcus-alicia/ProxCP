{% include 'header-noauth.tpl' %}
    <div class="container-full" id="blocks">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3 login-box">
                  {% if not modalForm is empty %}
                    {{modalForm|raw}}
                  {% else %}
                    <form role="form" action="" method="POST">
                        <fieldset>
                            <h2>{{appname}} Login</h2>
                            {% if not errors is empty %}
                              <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
                            {% endif %}
                            <hr class="pulse" />
                            <div class="form-group">
              								{% if ssoemail is empty %}
              									<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Username">
              								{% else %}
              									<input type="text" name="username" id="username" class="form-control input-lg" value="{{ssoemail}}">
              								{% endif %}
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password">
                            </div>
                            <span class="button-checkbox">
                                <button type="button" class="btn" data-color="info">Remember Me</button>
                                <input type="checkbox" name="remember_me" id="remember_me" class="hidden">
                                <a href="forgotpassword" class="btn btn-link pull-right">Forgot Password?</a>
                            </span>
                            <hr class="pulse">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <input type="submit" class="btn btn-lg btn-success btn-block" value="Login">
                                </div>
                            </div>
                        </fieldset>
                        <input type="hidden" name="token" value="{{formToken}}" />
                        <input type="hidden" name="form_name" value="login_form" />
                    </form>
                  {% endif %}
