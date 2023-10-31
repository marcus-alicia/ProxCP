<div class="row">
  <div class="col-sm-5 text-right">
    <strong>Server Status</strong>
  </div>
  <div class="col-sm-7 text-left">
    {if $status eq 'running'}
      <span style="color:green;font-weight:bold;">{$status}</span>
    {elseif $status eq 'stopped'}
      <span style="color:red;font-weight:bold;">{$status}</span>
    {else}
      {$status}
    {/if}
  </div>
</div>
<div class="row">
  <div class="col-sm-5 text-right">
    <strong>Node</strong>
  </div>
  <div class="col-sm-7 text-left">
    {$node}
  </div>
</div>
<div class="row">
  <div class="col-sm-5 text-right">
    <strong>IP Address</strong>
  </div>
  <div class="col-sm-7 text-left">
    {$ip}
  </div>
</div>
<div class="row">
  <div class="col-sm-5 text-right">
    <strong>Current OS</strong>
  </div>
  <div class="col-sm-7 text-left">
    {$os}
  </div>
</div>
