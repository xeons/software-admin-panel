{include file="header.tpl" title=Index}
<div id="content-panel">
  <h1>Stats</h1>
  <h2>Login Stats</h2>
  <ul>
    <li><strong>Login count (today)</strong>: {$login_count_today}</li>
    <li><strong>Last user logged in</strong>: {$last_user} @ {$last_user_time|date_format:"%D %r"}</li>
    <li><strong>Active Users</strong>: {$active_users}</li>
    <li><strong>Active Sessions</strong>: {$active_sessions}</li>
    <li><strong>Failed login attempts today</strong>: {$failed_login_attempts}</li>
    <li><strong>Total failed login attempts</strong>: {$total_failed_login_attempts}</li>
  </ul>
  <h2>General Stats</h2>
  <ul>
    <li><strong>Programs</strong>: {$program_count}</li>
    <li><strong>Users</strong>: {$user_count}</li>
  </ul>
</div>
{include file="footer.tpl"}