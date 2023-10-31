{% include 'header.tpl' %}
    <div class="container">
    	<div class="row"><div class="col-md-12">
            <h1>Access Control - Allowed IP Addresses</h1>
            {% if aclsetting == 'true' %}
            <p>Use this whitelist to decide which IP addresses are able to access your {{appname}} account. An empty whitelist will allow access from any IP address.</p>
            {% if not errors is empty %}
              <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
            {% endif %}
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add IP Address - Your current IP is: <strong>{{currentIP}}</strong>
                </div>
                <div class="panel-body">
                    <form role="form" action="" method="POST">
                        <div class="form-group">
                            <label>IP Address</label>
                            <input class="form-control" type="text" name="ipaddress" />
                            <p class="help-block">Enter an IPv4 address in the form of xx.xx.xx.xx</p>
                        </div>
                        <input type="hidden" name="formid" value="{{formID}}" />
                        <input type="submit" value="Submit" class="btn btn-md btn-info" />
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="useracltable">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                    {% for acip in gotacl %}
                      <tr><td>{{acip.ipaddress}}</td></tr>
                      <td><form role="form" method="POST" action=""><input type="hidden" value="{{acip.ipaddress}}" name="ip_remove" /><input type="hidden" name="formid" value="{{formID2}}" /><input type="submit" value="Remove" class="btn btn-sm btn-danger" /></form></td></tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
            {% else %}
              <p>User ACL is not enabled.</p>
            {% endif %}
