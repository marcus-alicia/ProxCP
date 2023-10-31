{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                <h1 align="center">{{ L.status.node_status }}</h1>
                {% if statussetting == 'true' %}
                <div class="table-responsive">
                    <table class="table" id="userstatustable">
                        <thead>
                            <tr>
                                <th>{{ L.status.status }}</th>
                                <th>{{ L.status.name }}</th>
                                <th>{{ L.status.cpu_model }}</th>
                                <th>{{ L.status.cpu_usage }}</th>
                                <th>{{ L.status.uptime }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for n in nodes %}
                          {% if n.noLogin == false %}
                            {% if n.status == 'online' %}
                              <tr class="success">
                                <td><img src="img/online.png" /></td>
                                <td>{{n.name}}</td>
                                <td>{{n.cpu}}</td>
                                <td>{{n.percent}}</td>
                                <td>{{n.uptime}}</td>
                              </tr>
                            {% else %}
                            <tr class="danger">
                              <td><img src="img/offline.png" /></td>
                              <td>{{n.name}}</td>
                              <td>{{n.cpu}}</td>
                              <td><em>null</em></td>
                              <td><em>null</em></td>
                            </tr>
                            {% endif %}
                          {% else %}
                            <tr class="danger">
                              <td><img src="img/offline.png" /></td>
                              <td>{{n.name}}</td>
                              <td>{{n.cpu}}</td>
                              <td><em>null</em></td>
                              <td><em>null</em></td>
                            </tr>
                          {% endif %}
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
                {% else %}
                  <p>{{ L.status.node_status }} is not enabled.</p>
                {% endif %}
            </div>
    	</div>
    </div>
