{include file="header.tpl" title="Modify User"}
<div id="dialog" title="Confirmation">
  <p>Are you sure you want to perform this action?</p>
</div>
<div id="content-panel">
	<h1>Modify User</h1>
	<form action="{$smarty.server.SCRIPT_NAME}?action=submit&amp;subaction=edit" method="post" enctype="multipart/form-data" name="form1">
		<input type="hidden" name="id" id="id" value="{$user.id}">
		{if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<th>Field</th>
				<th>Value</th>
			</tr>
			<tr>
				<td width="40%">Username:<br>
				<span class="description">Must be between 3-20 characters.</span></td>
				<td><input name="username" type="text" id="username" value="{$user.username}"></td>
			</tr>
			<tr>
				<td>Package:<br>
				<span class="description">A package associated with this user.</span></td>
				<td>{html_radios name=group_id options=$group_list selected=$user.group_id separator='<br />'}</td>
			</tr>
			<tr>
				<td>Email Address (optional):<br>
				<span class="description">A contact email address.</span></td>
				<td><input name="email_address" type="text" id="email_address" value="{$user.email_address}"></td>
			</tr>
			<tr>
				<td>Active<br>
				<span class="description">Is this user active? If this isn't checked they won't have access to anything.</span></td>
				<td><input name="active" type="checkbox" id="active" value="1"{if $user.active} checked{/if}></td>
			</tr>
			<tr>
				<td>Admin<br>
				<span class="description">Is this user an admin?</span></td>
				<td><input name="admin" type="checkbox" id="admin" value="1"{if $user.admin} checked{/if}></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" id="submit" value="Modify User"></td>
			</tr>
		</table>
	</form>
	<br />
	<h1>Access Rights</h1>
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<th>Program</th>
			<th>Expires</th>
			<th>Actions</th>
		</tr>
		{foreach from=$user.access_list key=access_id item=access_item}
		<tr>
			<td>{$access_item.name}</td>
			<td>{if $access_item.expiration_time > 0}{$access_item.expiration_time|date_format:"%D %r"}{else}Never{/if}</td>
			<td><a class="action-link" href="users.php?action=removeaccess&amp;pid={$access_item.permissions_id}"><img src="img/cross.png" width="16" height="16" alt="Remove Access" /> Remove access</a></td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="3">No access permissions found.</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="3"><a href="users.php?action=addaccess&amp;id={$user.id}"><img src="img/key_add.png" width="16" height="16" /> Add access.</a></td>
		</tr>
	</table>
</div>
{include file="footer.tpl"}