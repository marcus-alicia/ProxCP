  {% if constants %}
    <div class="alert alert-info alert-info-vngreen" style="border-radius:0px;margin-bottom:0px;">
  {% else %}
    <div class="alert alert-info alert-info-vngreen" style="border-radius:0px;">
  {% endif %}
	<div class="container">
    	<div class="pull-left" style="margin-top:8px;">{{ L.navbar.welcome_tag1 }} {{appname}}! {{ L.navbar.welcome_tag2 }} <strong>{{username}}</strong></div>
    	<div class="pull-right">
    		<ol class="breadcrumb vnbc">
    			<li><a href="profile"><i class="fa fa-user fa-fw"></i> Edit Profile</a></li>
                {% if aclsetting == 'true' %}
                  <li><a href="acl"><i class="fa fa-ban fa-fw"></i> {{ L.navbar.acl }}</a></li>
                {% endif %}
                <li><a href="logout"><i class="fa fa-sign-out fa-fw"></i> {{ L.navbar.logout }}</a></li>
    		</ol>
    	</div>
	</div>
</div>
