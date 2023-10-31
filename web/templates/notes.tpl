{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                <h1>{{ L.notepad.notepad }}</h1>
                {% if notessetting == 'true' %}
                <div class="panel panel-default">
                    <div class="panel-heading" id="n_counter">
                        {{count}} characters remaining
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                {% if not errors is empty %}
                                  <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
                                {% endif %}
                                <form role="form" action="" method="POST">
                                    <div class="form-group">
                                        <label>{{ L.notepad.notes }}</label>
                                        <textarea class="form-control" rows="15" name="notes" id="notes">{{notes}}</textarea>
                                    </div>
                                    <input type="hidden" name="token" value="{{formToken}}" />
                                    <input type="submit" value="{{ L.notepad.save }}" class="btn btn-success" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {% else %}
                  <p>{{ L.notepad.notepad }} is not enabled.</p>
                {% endif %}
            </div>
    	</div>
    </div>
