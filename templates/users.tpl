{include file="header.tpl" title=Index}
<div id="dialog" title="Confirmation">
	<p>Are you sure you want to perform this action?</p>
</div>
<div id="content-panel">
	<h1>Users</h1>
	<p><a href="users.php?action=add"><img src="img/application_form_add.png" width="16" height="16" /> Create new user.</a></p>
	<table width="100%" cellpadding="3" cellspacing="0">
	<tr>
		<th width="100">Name</th>
		<th width="300">Authorized programs</th>
		<th>Options</th>
	</tr>
	{foreach from=$user_list key=user_id item=user}
	<tr>
	<td valign="top">{$user.username}</td>
	<td valign="top">
		<ul>
		{foreach from=$user.access_list key=access_id item=access_item}
		<li>{$access_item.name}</li>
		{foreachelse}
		<li>None</li>
		{/foreach}
		</ul>
	</td>
	<td valign="top">
		<a href="users.php?action=edit&id={$user.id}"><img src="img/group_edit.png" width="16" height="16" /> Modify User</a><br />
		<a href="users.php?action=password&id={$user.id}"><img src="img/group_key.png" width="16" height="16" /> Change Password</a><br />
		<a class="action-link" href="users.php?action=delete&id={$user.id}"><img src="img/group_delete.png" width="16" height="16" /> Delete User</a>
	</td>
	</tr>
	{foreachelse}
	<tr>
	<td colspan="3">No users found</td>
	</tr>
	{/foreach}
	</table>
</div>
{include file="footer.tpl"}