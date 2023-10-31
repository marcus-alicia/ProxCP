{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
    		<div class="col-md-9">
          {% if not errors is empty %}
            <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
          {% endif %}
    			<h2 align="center">{{ L.dashboard.vms }}</h2>
    			<div class="table-responsive">
        			<table class="table table-hover">
        				<tr>
        					<th>{{ L.dashboard.status }}</th>
        					<th>{{ L.dashboard.hostname }}</th>
        					<th>{{ L.dashboard.type }}</th>
        					<th>{{ L.dashboard.main_ip }}</th>
        					<th>{{ L.dashboard.os }}</th>
                  <th>CPU</th>
        					<th>{{ L.dashboard.ram }}</th>
        					<th>{{ L.dashboard.storage }}</th>
        					<th></th>
        				</tr>
                {% for lxc in vmtable.lxc %}
                  {% if lxc.noLogin == true %}
                    <tr>
                      <td>Uh oh! We can't reach your node.</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  {% elseif lxc.suspended == false and lxc.noLogin == false %}
                    <tr>
                      {% if lxc.status == 'running' %}
                        <td><img src="img/online.png" /></td>
                      {% else %}
                        <td><img src="img/offline.png" /></td>
                      {% endif %}
                      <td>{{lxc.name}}</td>
                      <td><img src="img/lxc.png" style="padding-right:5px;" />LXC</td>
                      <td>{{lxc.ip}}</td>
                      <td>{{lxc.os}}</td>
                      <td>{{lxc.cpus}}</td>
                      <td>{{lxc.maxmem}}</td>
                      <td>{{lxc.maxdisk}}</td>
                      <td><a href="manage?id={{lxc.hbid}}&virt=lxc" class="btn btn-sm btn-info">Manage</a></td>
                    </tr>
                  {% elseif lxc.suspended == true %}
                    <tr>
                      {% if lxc.status == 'running' %}
                        <td><img src="img/online.png" /></td>
                      {% else %}
                        <td><img src="img/offline.png" /></td>
                      {% endif %}
                      <td>{{lxc.name}}</td>
                      <td><img src="img/lxc.png" style="padding-right:5px;" />LXC</td>
                      <td>{{lxc.ip}}</td>
                      <td>{{lxc.os}}</td>
                      <td>{{lxc.cpus}}</td>
                      <td>{{lxc.maxmem}}</td>
                      <td>{{lxc.maxdisk}}</td>
                      <td><div class="tooltip-wrapper disabled" data-title="Suspended" data-placement="right"><button class="btn btn-sm btn-info" disabled>Manage</button></div></td>
                    </tr>
                  {% endif %}
                {% endfor %}
                {% for kvm in vmtable.kvm %}
                  {% if kvm.noLogin == true %}
                    <tr>
                      <td>Uh oh! We can't reach your node.</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  {% elseif kvm.suspended == false and kvm.noLogin == false %}
                    <tr>
                      {% if kvm.status == 'running' %}
                        <td><img src="img/online.png" /></td>
                      {% else %}
                        <td><img src="img/offline.png" /></td>
                      {% endif %}
                      <td>{{kvm.name}}</td>
                      <td><img src="img/kvm.png" style="padding-right:5px;" />KVM</td>
                      <td>{{kvm.ip}}</td>
                      <td>{{kvm.os}}</td>
                      <td>{{kvm.cpus}}</td>
                      <td>{{kvm.maxmem}}</td>
                      <td>{{kvm.maxdisk}}</td>
                      {% if kvm.from_template == 1 %}
                        <td><a href="manage?id={{kvm.hbid}}&virt=kvm" class="btn btn-sm btn-warning template_setup_btn">Setup</a></td>
                      {% else %}
                        <td><a href="manage?id={{kvm.hbid}}&virt=kvm" class="btn btn-sm btn-info">Manage</a></td>
                      {% endif %}
                    </tr>
                  {% elseif kvm.suspended == true %}
                    <tr>
                      {% if kvm.status == 'running' %}
                        <td><img src="img/online.png" /></td>
                      {% else %}
                        <td><img src="img/offline.png" /></td>
                      {% endif %}
                      <td>{{kvm.name}}</td>
                      <td><img src="img/kvm.png" style="padding-right:5px;" />KVM</td>
                      <td>{{kvm.ip}}</td>
                      <td>{{kvm.os}}</td>
                      <td>{{kvm.cpus}}</td>
                      <td>{{kvm.maxmem}}</td>
                      <td>{{kvm.maxdisk}}</td>
                      <td><div class="tooltip-wrapper disabled" data-title="Suspended" data-placement="right"><button class="btn btn-sm btn-info" disabled>Manage</button></div></td>
                    </tr>
                  {% endif %}
                {% endfor %}
        			</table>
        		</div>
            {% if cloud_accounts != 'false' %}
              {% if cl_data|length > 0 %}
                <h2 align="center">Public Cloud Resources</h2>
                <div id="clcreation"></div>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <tr>
                      <th>ID</th>
                      <th>{{ L.dashboard.ram }}</th>
                      <th>CPU Cores</th>
                      <th>Disk Space</th>
                      <th>VM Limit</th>
                      <th>View Details</th>
                    </tr>
                    {% for cl in cl_data %}
                      <tr>
                        <td>{{cl.hb_account_id}}</td>
                        <td>{{cl.memory}}MB</td>
                        <td>{{cl.cpu_cores}}</td>
                        <td>{{cl.disk_size}}GB</td>
                        <td>{{cl.ip_limit}}</td>
                        {% if cl.suspended == 1 %}
                          <td><button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#cldetails{{cl.hb_account_id}}">Details (suspended)</button></td>
                        {% else %}
                          <td><button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#cldetails{{cl.hb_account_id}}">Details</button></td>
                        {% endif %}
                      </tr>
                      {% set clramperc = (cl.avail_memory / cl.memory) * 100 %}
                      {% set clcpuperc = (cl.avail_cpu_cores / cl.cpu_cores) * 100 %}
                      {% set cldiskperc = (cl.avail_disk_size / cl.disk_size) * 100 %}
                      {% set clvmperc = (cl.avail_ip_limit / cl.ip_limit) * 100 %}
                      <div class="modal fade" id="cldetails{{cl.hb_account_id}}" tabindex="-1" role="dialog" aria-labelledby="cldetails{{cl.hb_account_id}}label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="cldetails{{cl.hb_account_id}}label">Pool {{cl.hb_account_id}} Resources Available</h4>
                            </div>
                            <div class="modal-body">
                              <h5>{{ L.dashboard.ram }}</h5>
                              <div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{clramperc}}" aria-valuemin="0" aria-valuemax="100" style="min-width:2em;width:{{clramperc}}%;"><div>{{cl.avail_memory}}MB of {{cl.memory}}MB</div></div></div>
                              <h5>CPU Cores</h5>
                              <div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{clcpuperc}}" aria-valuemin="0" aria-valuemax="100" style="min-width:2em;width:{{clcpuperc}}%;"><div>{{cl.avail_cpu_cores}} of {{cl.cpu_cores}}</div></div></div>
                              <h5>Disk Space</h5>
                              <div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{cldiskperc}}" aria-valuemin="0" aria-valuemax="100" style="min-width:2em;width:{{cldiskperc}}%;"><div>{{cl.avail_disk_size}}GB of {{cl.disk_size}}GB</div></div></div>
                              <h5>VM Count</h5>
                              <div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{clvmperc}}" aria-valuemin="0" aria-valuemax="100" style="min-width:2em;width:{{clvmperc}}%;"><div>{{cl.avail_ip_limit}} of {{cl.ip_limit}}</div></div></div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    {% endfor %}
                  </table>
                </div>
                <button type="button" class="btn btn-md btn-success" data-toggle="modal" data-target="#clcreatevm">Create new VM</button>
                <div class="modal fade" id="clcreatevm" tabindex="-1" role="dialog" aria-labelledby="clcreatevmlabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="clcreatevmlabel">Cloud - Create New VM</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="clcreate_error"></div>
                                    <form role="form">
                                        <div class="form-group">
                                            <label>Resource pool</label>
                                            <select class="form-control" id="clpoolid">
                                                <option value="default">Select...</option>
                                                {% for cl in cl_data %}
                                                  <option value="{{cl.hb_account_id}}">ID: {{cl.hb_account_id}}</option>
                                                {% endfor %}
                                              </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Hostname</label>
                                                <input type="text" class="form-control" id="clhostname" />
                                            </div>
                                            <div class="form-group">
                                                <label>Operating system</label>
                                                <select class="form-control" id="clos">
                                                    <option value="default">Select...</option>
                                                    {% for iso in kvmisos_custom %}
                                                      <option value="{{ kvmisos_custom_location }}:iso/{{ iso.upload_key }}.iso">{{ iso.fname }}</option>
                                                    {% endfor %}
                                                    {% for iso in kvmisos %}
                                                      <option value="{{iso.volid}}">{{iso.friendly_name}} (manual ISO)</option>
                                                    {% endfor %}
                                                    </select>
                                          </div>
                                          <div class="form-group">
                                              <label>{{ L.dashboard.ram }} (MB)</label><br />
                                              <input id="ramslider" type="text" data-slider-min="32" data-slider-max="32" data-slider-step="32" data-slider-value="32" data-slider-enabled="false" />
                                              <span id="ramsliderLabel">RAM: <span id="ramsliderVal">32</span>MB</span>
                                          </div>
                                          <div class="form-group">
                                              <label>CPU cores</label><br />
                                              <input id="cpuslider" type="text" data-slider-min="1" data-slider-max="1" data-slider-step="1" data-slider-value="1" data-slider-enabled="false" />
                                              <span id="cpusliderLabel">CPU Cores: <span id="cpusliderVal">1</span></span>
                                          </div>
                                          <div class="form-group">
                                              <label>Disk size (GB)</label><br />
                                              <input id="diskslider" type="text" data-slider-min="1" data-slider-max="1" data-slider-step="1" data-slider-value="1" data-slider-enabled="false" />
                                              <span id="disksliderLabel">Disk Size: <span id="disksliderVal">1</span>GB</span>
                                          </div>
                                          <div class="form-group">
                                              <label>Disk Device</label>
                                              <select class="form-control" id="clddevice">
                                                  <option value="default">Select...</option>
                                                  <option value="ide">IDE (Windows)</option>
                                                  <option value="virtio">VirtIO (Linux)</option>
                                              </select>
                                          </div>
                                          <div class="form-group">
                                              <label>Network Device</label>
                                              <select class="form-control" id="clnetdevice">
                                                  <option value="default">Select...</option>
                                                  <option value="e1000">Intel E1000 (Windows)</option>
                                                  <option value="virtio">VirtIO (Linux)</option>
                                              </select>
                                          </div>
                                          <input type="submit" value="Create" class="btn btn-md btn-success" id="clcreatevmsaved" disabled />
                                      </form>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  </div>
                              </div>
                          </div>
                      </div>
              {% endif %}
            {% endif %}
                {% if enable_whmcs == 'true' %}
								<br />
								<h2 align="center">Unpaid Invoices</h2>
								<div class="table-responsive">
									<table class="table table-hover">
										<tr>
											<th>Invoice #</th>
											<th>Invoice Date</th>
											<th>Invoice Due Date</th>
											<th>Total</th>
											<th>{{ L.dashboard.status }}</th>
										</tr>
                    {% if getInvoices.invoices|length > 0 %}
                      {% for bill in getInvoices.invoices.invoice %}
                        <tr>
                          <td>{{bill.id}}</td>
                          <td>{{bill.date}}</td>
                          <td>{{bill.duedate}}</td>
                          <td>{{bill.currencyprefix}}{{bill.total}}</td>
                          <td>{{bill.status}}</td>
                        </tr>
                      {% endfor %}
                    {% else %}
                      <tr><td>No unpaid invoices</td><td></td><td></td><td></td><td></td></tr>
                    {% endif %}
									</table>
								</div>
								<br />
								<h2 align="center">Support Tickets</h2>
								<center><button type="button" class="btn btn-success btn-sm" style="margin-bottom:10px;" data-toggle="modal" data-target="#ticketModal">Open New Ticket</button></center>
								<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="ticketModalLabel">Open New Ticket</h4>
											</div>
											<div class="modal-body">
												<form role="form" action="" method="POST">
			                    <div class="form-group">
		                        <label>Department</label>
		                        <select class="form-control" name="ticketdept">
															<option value="default">Select...</option>
                              {% for dept in getDepts.departments.department %}
                                <option value="{{dept.id}}">{{dept.name}}</option>
                              {% endfor %}
														</select>
			                    </div>
			                    <div class="form-group">
		                        <label>Subject</label>
		                        <input class="form-control" type="text" name="ticketsubject" />
			                    </div>
													<div class="form-group">
		                        <label>Message</label>
		                        <textarea class="form-control" name="ticketmsg" rows="7"></textarea>
			                    </div>
													<input type="hidden" name="token" value="{{token}}" />
			                    <input type="submit" value="Submit" class="btn btn-success" />
				                </form>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-hover">
										<tr>
											<th>Ticket #</th>
											<th>Department</th>
											<th>Subject</th>
											<th>{{ L.dashboard.status }}</th>
											<th></th>
										</tr>
                    {% if getTickets.tickets|length > 0 %}
                      {% for ticket in getTickets.tickets.ticket %}
                        <tr>
                          <td>{{ticket.tid}}</td>
                          {% for dept in getDepts.departments.department %}
                            {% if ticket.deptid == dept.id %}
                              <td>{{dept.name}}</td>
                            {% endif %}
                          {% endfor %}
                          <td>{{ticket.subject}}</td>
                          <td>{{ticket.status}}</td>
                          <td><a class="btn btn-info btn-sm" href="viewticket?id={{ticket.tid}}">View</a></td>
                        </tr>
                      {% endfor %}
                    {% else %}
                      <tr><td>No active tickets</td><td></td><td></td><td></td><td></td></tr>
                    {% endif %}
									</table>
								</div>
							{% endif %}
