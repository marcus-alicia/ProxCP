{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                {% if fdnssetting == 'true' %}
                {% if not errors is empty %}
                  <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Errors: </strong>{{errors}}</div>
                {% endif %}
                {% if not success is empty %}
                  <div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Success: </strong>{{success}}</div>
                {% endif %}
                <!-- Feature with tabs -->
                <section class="feature-tabs">
                 <div class="first-sliding">
                 </div>
                 <div class="wrap">
                  <div class="tabs">
                   <div class="tabs_container">
                   <!-- TAB -->
                    <li class="btnLink smallBtnLink green_tab">
                        <a class="tab-action active" data-tab-cnt="tab1">
                            <span>List DNS Records</span>
                        </a></li>
                    <!-- TAB END -->
                    <!-- TAB -->
                    <li class="btnLink smallBtnLink green_tab">
                        <a class="tab-action" data-tab-cnt="tab2">
                            <span>Add DNS Record</span>
                        </a></li>
                    <!-- TAB END -->
                    <!-- TAB -->
                    <li class="btnLink smallBtnLink green_tab">
                        <a class="tab-action" data-tab-cnt="tab3">
                            <span>Add Domain</span>
                        </a></li>
                    <!-- TAB END -->
                    <!-- TAB -->
                    <li class="btnLink smallBtnLink green_tab">
                        <a class="tab-action" data-tab-cnt="tab4">
                            <span>List Domains</span>
                        </a></li>
                    <!-- TAB END -->
                   </div>
                   <br>
                   <div class="clr"></div>
                   <!-- TAB CONTENT -->
                   <div id="tab1" class="tab-single tab-cnt active">
                        <div class="datacenters">
                            <div class="col-md-12">
                                <h3 align="center">Existing DNS Records</h3>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Domain</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>CNAME</th>
                                            <th>Pref.</th>
                                            <th>Exchange</th>
                                            <th>Priority</th>
                                            <th>Weight</th>
                                            <th>Port</th>
                                            <th>Target</th>
                                            <th>Data</th>
                                            <th>Remove</th>
                                        </tr>
                                        {% for r in forward_dns_records %}
                                          <form action="" method="POST" role="form">
                                            <tr>
                                              <td>{{r.domain}}</td>
                                              <td>{{r.name}}</td>
                                              <td>{{r.type}}</td>
                                              {% if not r.address is empty %}
                                                <td>{{r.address}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.cname is empty %}
                                                <td>{{r.cname}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.preference is empty %}
                                                <td>{{r.preference}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.exchange is empty %}
                                                <td>{{r.exchange}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.priority is empty %}
                                                <td>{{r.priority}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.weight is empty %}
                                                <td>{{r.weight}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.port is empty %}
                                                <td>{{r.port}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.target is empty %}
                                                <td>{{r.target}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                              {% if not r.txtdata is empty %}
                                                <td>{{r.txtdata}}</td>
                                              {% else %}
                                                <td><em>n/a</em></td>
                                              {% endif %}
                                            </tr>
                                            <input type="hidden" name="formid" value="{{r.formID}}" />
                                            <input type="hidden" name="domain" value="{{r.domain}}" />
                                            <input type="hidden" name="name" value="{{r.name}}" />
                                            <input type="hidden" name="type" value="{{r.type}}" />
                                            <input type="hidden" name="address" value="{{r.address}}" />
                                            <input type="hidden" name="cname" value="{{r.cname}}" />
                                            <input type="hidden" name="pref" value="{{r.preference}}" />
                                            <input type="hidden" name="exchange" value="{{r.exchange}}" />
                                            <input type="hidden" name="priority" value="{{r.priority}}" />
                                            <input type="hidden" name="weight" value="{{r.weight}}" />
                                            <input type="hidden" name="port" value="{{r.port}}" />
                                            <input type="hidden" name="target" value="{{r.target}}" />
                                            <input type="hidden" name="txtdata" value="{{r.newdata}}" />
                                            <input type="hidden" name="dnsid" value="{{dnsID}}" />
                                          </form>
                                        {% endfor %}
                                    </table>
                                </div>
                            </div>
                        </div>
                   </div>
                   <!-- TAB CONTENT END -->
                   <!-- TAB CONTENT -->
                   <div id="tab2" class="tab-single tab-cnt">
                         <div class="datacenters">
                            <div class="col-md-6 col-md-offset-3">
                                <h3 align="center">Add DNS Record</h3>
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label>Domain</label>
                                        <select name="add_zone" class="form-control">
                                            {% for d in forward_dns_domains %}
                                              <option value="{{d}}">{{d}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="add_type" id="add_type" class="form-control">
                                            <option value="none">Select value</option>
                                            <option value="A">A</option>
                                            <option value="CNAME">CNAME</option>
                                            <option value="SRV">SRV</option>
                                            <option value="MX">MX</option>
                                            <option value="TXT">TXT</option>
                                        </select>
                                    </div>
                                    <div id="a_block"></div>
                                    <div id="cname_block"></div>
                                    <div id="srv_block"></div>
                                    <div id="mx_block"></div>
                                    <div id="txt_block"></div>
                                    <input type="hidden" name="formid" value="{{formID}}" />
                                </form>
                            </div>
                        </div>
                   </div>
                   <!-- TAB CONTENT END -->
                   <!-- TAB CONTENT -->
                   <div id="tab3" class="tab-single tab-cnt">
                         <div class="datacenters">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="alert alert-info">
                                  You must point your domain to the below nameservers in order to manage your domain's DNS from this control panel.<br /><br />
                                  {% for x,n in nameservers %}
                                    Nameserver {{x+1}}: {{n}}<br />
                                  {% endfor %}
                                </div>
                                <h3 align="center">Add Domain <small>{{domaincount}} of {{domainlimit}} in use</small></h3>
                                {% if domaincount < domainlimit %}
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label>IP Address</label>
                                        <input type="text" name="ipaddress" class="form-control" />
                                        <p class="help-block">creates default A record</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Domain</label>
                                        <input type="text" name="domain" class="form-control" />
                                    </div>
                                    <input type="submit" value="Submit" class="btn btn-success btn-block" />
                                    <input type="hidden" name="formid" value="{{formID2}}" />
                                    <br />
                                    <br />
                                </form>
                                {% else %}
                                  <div class="alert alert-danger">Maximum domain limit reached.</div>
                                {% endif %}
                            </div>
                        </div>
                   </div>
                   <!-- TAB CONTENT END -->
                   <!-- TAB CONTENT -->
                   <div id="tab4" class="tab-single tab-cnt">
                         <div class="datacenters">
                            <div class="col-md-6 col-md-offset-3">
                                <h3 align="center">Remove Domain</h3>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Domain</th>
                                            <th>Remove</th>
                                        </tr>
                                        {% for d in forward_dns_domains %}
                                          <form role="form" method="POST" action="">
                                            <tr>
                                              <td>{{d}}</td>
                                              <td><input type="submit" value="Remove" class="btn btn-danger btn-sm" /></td>
                                            </tr>
                                            <input type="hidden" name="formid" value="{{formID3}}" />
                                            <input type="hidden" name="domain" value="{{d}}" />
                                          </form>
                                        {% endfor %}
                                    </table>
                                </div>
                            </div>
                        </div>
                   </div>
                   <!-- TAB CONTENT END -->
                  </div>
                 </div>
                </section>
                <!-- /Feature with tabs -->
                {% else %}
                  <p>Forward DNS is not enabled.</p>
                {% endif %}
            </div>
    	</div>
    </div>
