{% include 'header.tpl' %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                {% if rdnssetting == 'true' %}
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
                            <span>List rDNS Records</span>
                        </a></li>
                    <!-- TAB END -->
                    <!-- TAB -->
                    <li class="btnLink smallBtnLink green_tab">
                        <a class="tab-action" data-tab-cnt="tab2">
                            <span>Add IPv4 rDNS Record</span>
                        </a></li>
                    <!-- TAB END -->
                    <!-- TAB -->
                    <li class="btnLink smallBtnLink green_tab">
                        <a class="tab-action" data-tab-cnt="tab3">
                            <span>Add IPv6 rDNS Record</span>
                        </a></li>
                    <!-- TAB END -->
                   </div>
                   <br>
                   <div class="clr"></div>
                   <!-- TAB CONTENT -->
                   <div id="tab1" class="tab-single tab-cnt active">
                        <div class="datacenters">
                            <div class="col-md-6 col-md-offset-3">
                                <h3 align="center">Existing rDNS Records</h3>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Target</th>
                                            <th>Remove</th>
                                        </tr>
                                        {% for ed in existingdata %}
                                          <form action="" method="POST" role="form">
                                          <tr>
                                              <td>{{ed.type}}</td>
                                              <td>{{ed.ipaddress}}</td>
                                              <td>{{ed.hostname}}</td>
                                              <td><input type="submit" value="Remove" class="btn btn-danger btn-sm" /></td>
                                          </tr>
                                          <input type="hidden" name="formid" value="{{formID}}" />
                                          <input type="hidden" name="ipaddress" value="{{ed.ipaddress}}" />
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
                                <h3 align="center">Add IPv4 rDNS Record</h3>
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label>IP Address</label>
                                        <select class="form-control" name="ipaddress">
                                          {% for ip in kvmips %}
                                            <option value="{{ip}}">{{ip}}</option>
                                          {% endfor %}
                                          {% for ip in lxcips %}
                                            <option value="{{ip}}">{{ip}}</option>
                                          {% endfor %}
                                          {% for ip in secondaryips %}
                                            <option value="{{ip}}">{{ip}}</option>
                                          {% endfor %}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Hostname / Target</label>
                                        <input type="text" name="hostname" class="form-control" />
                                    </div>
                                    <input type="submit" value="Submit" class="btn btn-success btn-block" />
                                    <input type="hidden" name="formid" value="{{formID2}}" />
                                </form>
                            </div>
                        </div>
                   </div>
                   <!-- TAB CONTENT END -->
                   <!-- TAB CONTENT -->
                   <div id="tab3" class="tab-single tab-cnt">
                         <div class="datacenters">
                            <div class="col-md-6 col-md-offset-3">
                                <h3 align="center">Add IPv6 rDNS Record</h3>
                                <form action="" method="POST" role="form">
                                    <div class="form-group">
                                        <label>IP Address</label>
                                        <select class="form-control" name="ipaddress">
                                            {% for ip in v6ips %}
                                              <option value="{{ip}}">{{ip}}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Hostname / Target</label>
                                        <input type="text" name="hostname" class="form-control" />
                                    </div>
                                    <input type="submit" value="Submit" class="btn btn-success btn-block" />
                                    <input type="hidden" name="formid" value="{{formID3}}" />
                                </form>
                            </div>
                        </div>
                   </div>
                   <!-- TAB CONTENT END -->
                  </div>
                 </div>
                </section>
                <!-- /Feature with tabs -->
                {% else %}
                  <p>Reverse DNS is not enabled.</p>
                {% endif %}
            </div>
    	</div>
    </div>
