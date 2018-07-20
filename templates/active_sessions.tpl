{include file="header.tpl" title="Active Sessions"}
<div id="dialog" title="Confirmation">
  <p>Are you sure you want to perform this action?</p>
</div>
<div id="content-panel">
  <h1>Active Sessions</h1>
  <p>This page displays which programs users are running and how long they've been running for. You may also terminate sessions using this panel.</p>
  <table width="100%" cellpadding="2" cellspacing="0" class="center">
    <tr>
      <th width="150">Username</th>
      <th>Program</th>
      <th>IP Address</th>
      <th>Start/Last Ping</th>
      <th>Age</th>
      <th>Expires</th>
      <th>Options</th>
    </tr>
    {foreach from=$session_list key=session_id item=session}
    <tr>
      <td>{$session.username}</td>
      <td>{$session.program_name}</td>
      <td>{$session.ip}</td>
      <td>{$session.creation_time|date_format:"%D %r"}<br />{$session.last_ping_time|date_format:"%D %r"}</td>
      <td>{$session.session_age}</td>
      <td>{$session.expiration_time}</td>
      <td><a class="action-link" href="sessions.php?action=terminate&id={$session.session_id}"><img src="img/group_delete.png" width="16" height="16" /> Terminate</a></td>
    </tr>
    {foreachelse}
    <tr>
      <td colspan="7">No active sessions found</td>
    </tr>
    {/foreach}
  </table>
</div>
{include file="footer.tpl"}