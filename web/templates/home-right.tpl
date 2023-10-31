</div>
<div class="col-md-3">
        {% if enable_panel_news == 'true' %}
          <div class="panel panel-default"><div class="panel-body"><strong>{{appname}} {{ L.side_bar.news }}</strong><br /><br />
            {{news}}
          </div></div>
        {% endif %}
  <div class="panel panel-default">
    <div class="panel-body">
      <strong>{{ L.side_bar.last_login }}: </strong>{{data.ip}} <br />{{ L.side_bar.at }} {{data.date}}
                <br /><br />
                <a href="profile">{{ L.side_bar.view_full_log }}</a>
    </div>
  </div>
  {% if user_iso_upload == 'true' and hasKVM_ISO == true %}
  <div class="panel panel-default">
    <div class="panel-body">
      <strong>KVM Custom ISOs</strong>
                <br /><br />
                <p><a href="#" data-toggle="modal" data-target="#userisoupload">Click here</a> to upload a custom ISO for your KVM services.</p>
    </div>
  </div>
  <div class="modal fade" id="userisoupload" tabindex="-1" role="dialog" aria-labelledby="userisouploadlabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="userisouploadlabel">User ISO Upload - KVM</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" id="error" style="display:none;">
            Oops! Something went wrong. Please refresh the page and try again.
            Don't worry, the upload will start where you left off!
          </div>
          <strong>Maximum File Size: {{ max_upload_size }}</strong><br /><br />
          <div class="form-group">
              <label>Friendly Name</label>
              <input class="form-control" type="text" name="useriso_fname" id="useriso_fname" placeholder="CentOS Custom" />
          </div>
          <div class="form-group">
              <label>ISO File Type</label>
              <select class="form-control" name="useriso_type" id="useriso_type">
                <option value="default">Select...</option>
                <option value="linux">Linux or BSD</option>
                <option value="windows">Windows</option>
              </select>
          </div>
          <div class="form-group">
              <label>ISO File</label>
              <input type="file" name="tus_file" id="tus-file" class="form-control file-input" accept=".iso" />
          </div>
          <button type="button" class="btn btn-labeled btn-primary" id="upload" disabled>Upload</button>
          <br /><br />
          <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"><span>0%</span></div>
          </div>
          <hr />
          <h3>Uploads</h3>
          <div class="completed-uploads">
            {% if user_isos|length > 0 %}
              <div class="table-responsive">
                <table class="table table-hover">
                  <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                  {% for iso in user_isos %}
                    <tr>
                      <td>{{ iso.fname }}</td>
                      <td>{{ iso.type|capitalize }}</td>
                      <td>{{ iso.status|capitalize }}</td>
                      <td><button id="useriso_delete{{ iso.id }}" class="btn btn-sm btn-danger" role="{{ iso.id }}">Delete</button></td>
                    </tr>
                  {% endfor %}
                </table>
              </div>
            {% else %}
              <p class="info">Successful uploads will be listed here.</p>
            {% endif %}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  {% endif %}
  {% if enable_whmcs == 'false' %}
  <div class="panel panel-danger vnalf">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div style="font-size:16px;">{{ L.side_bar.support_panel }}</div>
                    </div>
                </div>
            </div>
            <a href="{{support_ticket_url}}" target="_blank">
                <div class="panel-footer">
                    <span class="pull-left">{{ L.side_bar.new_ticket }}</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    {% endif %}
