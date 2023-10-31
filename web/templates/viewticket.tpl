{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                <h1>View Ticket - #{{tid}}</h1>
                {% if not errors is empty %}
                  <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
                {% endif %}
                {% if enable_whmcs == 'true' %}
                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <td><strong>Ticket #</strong></td>
                      <td>{{getTicket.tid}}</td>
                      <td><strong>Department</strong></td>
                      <td>{{getTicket.deptname}}</td>
                    </tr>
                    <tr>
                      <td><strong>Open Date</strong></td>
                      <td>{{getTicket.date}}</td>
                      <td><strong>Last Reply</strong></td>
                      <td>{{getTicket.lastreply}}</td>
                    </tr>
                    <tr>
                      <td><strong>Status</strong></td>
                      <td>{{getTicket.status}}</td>
                      <td><strong>Priority</strong></td>
                      <td>{{getTicket.priority}}</td>
                    </tr>
                  </table>
                </div>
                <h3>Subject: {{getTicket.subject}}</h3>
                <form role="form" action="" method="POST">
                  <div class="form-group">
                    <label>Reply</label>
                    <textarea class="form-control" name="replymsg" rows="7"></textarea>
                  </div>
                  <input type="hidden" name="token" value="{{token}}" />
                  <input type="hidden" name="tid" value="{{getTicket.ticketid}}" />
                  <input type="submit" value="Reply" class="btn btn-success" />
                </form>
                <br /><br />
                {% for reply in getTicket.replies.reply %}
                  {% if not reply.admin is empty %}
                    <div class="panel panel-primary">
                      <div class="panel-heading">{{reply.admin}} @ {{reply.date}}</div>
                      <div class="panel-body">{{reply.message|nl2br}}</div>
                    </div>
                  {% else %}
                    <div class="panel panel-default">
                      <div class="panel-heading">{{reply.name}} @ {{reply.date}}</div>
                      <div class="panel-body">{{reply.message|nl2br}}</div>
                    </div>
                  {% endif %}
                {% endfor %}
                <br /><br /><br />
              {% else %}
                <p>Tickets are not enabled.</p>
              {% endif %}
            </div>
    	</div>
    </div>
