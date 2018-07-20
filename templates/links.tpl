{include file="header.tpl" title=Index}
<div id="content-panel">
<h1>Sent links log</h1>
<fieldset>
<legend>User Search:</legend>
<form id="user-search" name="user-search" method="post" action="links.php">
  <label for="username">Find links sent by username:<br />
    Username:</label>
  <input type="text" name="username" id="username" />
  <input type="submit" name="submit" id="submit" value="Submit" />
</form>
</fieldset>
{foreach from=$links_list key=link_id item=link}
<p class="{cycle values="odd-row,even-row"}">
<a href="{$link.url}">{$link.url}</a> was sent by <strong>{$link.username}</strong><br>
Using <strong>{$link.program_name}</strong> at <strong>{$link.server_time|date_format:"%D %r"}</strong></p>
{foreachelse}
<p>No activity to report</p>
{/foreach}
Page: {for $i=$page_min to $page_max}<a href="links.php?page={$i}">{$i}</a> | {/for}<a href="links.php?page={$page_max}">>></a>
</div>
{include file="footer.tpl"}