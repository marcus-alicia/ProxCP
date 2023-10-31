{% include 'header.tpl' %}
    {% if gid == true and vid == true %}
    <div class="alert alert-info alert-info-vngreen" style="border-radius:0px;margin-top:0px;">
        <div class="container">
            <form role="form" class="form-inline">
                <div class="form-group">
                    <label>Server Quick Select: </label>
                    <select class="form-control" id="fwquick">
                        <option value="{{base}}/firewall">Select...</option>
                        {% for lxc in vmtable.lxc %}
                          {% if lxc.noLogin == false %}
                            <option value="{{base}}/firewall?id={{lxc.hbid}}&virt=lxc">{{lxc.name}} - {{lxc.ip}}</option>
                          {% else %}
                            <option value="{{base}}/firewall?id={{lxc.hbid}}&virt=lxc">server down - {{lxc.ip}}</option>
                          {% endif %}
                        {% endfor %}
                        {% for kvm in vmtable.kvm %}
                          {% if kvm.noLogin == false %}
                            <option value="{{base}}/firewall?id={{kvm.hbid}}&virt=kvm">{{kvm.name}} - {{kvm.ip}}</option>
                          {% else %}
                            <option value="{{base}}/firewall?id={{kvm.hbid}}&virt=kvm">server down - {{kvm.ip}}</option>
                          {% endif %}
                        {% endfor %}
                    </select>
                </div>
                <a href="firewall" class="btn btn-info btn-sm" id="fwquickgo">Go</a>
            </form>
        </div>
    </div>
    {% endif %}
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                <h1 align="center">{{ L.firewall.manage_firewall }}{% if not fwhost is empty %} - {{fwhost}}{% endif %}</h1>
