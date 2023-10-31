{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
            <div class="col-md-5">
                <h3>Current Profile Information</h3>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                {% if not errors is empty %}
                                  <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
                                {% endif %}
                                {% if not qrCodeUrl is empty %}
                                  <div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Do not leave this page until you scan this QR code with the Google Authenticator app!</strong><img src="{{qrCodeUrl}}" /></div>
                                {% endif %}
                                <form role="form" action="" method="POST">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" disabled value="{{username}}" />
                                        <p class="help-block">Contact support to change this value.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Email address</label>
                                        <input class="form-control" disabled value="{{email}}" />
                                        <p class="help-block">Contact support to change this value.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control" type="password" name="new_password" />
                                        <p class="help-block">Enter a new password here to change it. This only changes your password for {{appname}}.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm password</label>
                                        <input class="form-control" type="password" name="new_password_confirm" />
                                    </div>
                                    <input type="hidden" name="token" value="{{formToken}}" />
                                    <input type="hidden" name="form_name" value="update_profile" />
                                    <input type="submit" value="Update" class="btn btn-success" />
                                    <p class="help-block"><strong>You will be automatically logged out after changing your password!</strong></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                              <form role="form" action="" method="POST">
                                <div class="form-group">
                                    <label>Change Language</label>
                                    <select class="form-control" name="user_lang">
                                      <option value="{{currentLang}}">{{currentLang}}</option>
                                      {% for option in langOptions %}
                                        <option value="{{option}}">{{option}}</option>
                                      {% endfor %}
                                    </select>
                                </div>
                                <input type="hidden" name="form_name" value="submit_lang" />
                                <input type="submit" value="Submit" class="btn btn-success" />
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                              <form role="form" action="" method="POST">
                                <div class="form-group">
                                    <label>Enable Two-Factor Authentication</label>
                                    <select class="form-control" name="tfa_account">
                                      {% if tfa_enabled == 0 %}
                                        <option value="false">False</option><option value="true">True</option>
                                      {% else %}
                                        <option value="true">True</option><option value="false">False</option>
                                      {% endif %}
                                    </select>
                                    <p class="help-block">Example 2FA/OTP app: Google Authenticator</p>
                                </div>
                                <input type="hidden" name="form_name" value="submit_tfa" />
                                <input type="submit" value="Submit" class="btn btn-success" />
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <h3>Previous User Sessions</h3>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="geoipmap"></div>
                                {% for d in data %}
                                  <input type="hidden" value="{{d.ip}}#{{d.date}}#{{d.geoip_loc}}#{{d.geoip_coords}}" class="geoipdata" />
                                {% endfor %}
                                <br />
                                <p style="font-size:11px;">This map is generated using GeoLite2 data created by <a href="http://www.maxmind.com" target="_blank">MaxMind</a> distributed under the <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">CC SA 4.0 License</a>.</p>
                                <br />
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#geoModal">View Previous Sessions As List</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
    <div class="modal fade" id="geoModal" tabindex="-1" role="dialog" aria-labelledby="geoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="geoModalLabel">Previous User Sessions</h4>
                </div>
                <div class="modal-body">
                    {% for d in data %}
                      {{d.ip}} @ {{d.date}} - <a class="geoip" data-toggle="tooltip" data-placement="right" title="{{d.geoip_loc}}">GeoIP</a><br />
                    {% endfor %}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
