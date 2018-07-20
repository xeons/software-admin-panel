{include file="header.tpl" title="Modify Group"}
<div id="content-panel">
	<h1>Modify Group</h1>
	<form action="{$smarty.server.SCRIPT_NAME}?action=submit&amp;subaction=modify-group" method="post" enctype="multipart/form-data" name="modify-group-form">
		<input type="hidden" name="id" id="id" value="{$group.id}">
		{if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<th width="50%">Field</th>
				<th width="50%">Value</th>
			</tr>
			<tr>
				<td>Group Name:<br>
				<span class="description">Must be between 1-100 characters.</span></td>
				<td><input name="name" type="text" id="name" value="{$group.name}"></td>
			</tr>
			<tr>
				<td>Description:<br>
				<span class="description">Include a detailed description of this package.</span></td>
				<td><textarea name="description" rows="10" cols="50">{$group.description|escape:'htmlall'}</textarea></td>
			</tr>
			<tr>
				<td>Program Limit:<br>
				<span class="description">Maximum number of assigned programs.</span></td>
				<td><input name="program_limit" type="text" id="program_limit" value="{$group.program_limit}"></td>
			</tr>
			<tr>
				<td>Update Limit:<br>
				<span class="description">Maximum number of updates.</span></td>
				<td><input name="update_limit" type="text" id="update_limit" value="{$group.update_limit}"></td>
			</tr>
			<tr>
				<td>Support Ticket Limit:<br>
				<span class="description">Maximum number of support tickets.</span></td>
				<td><input name="support_ticket_limit" type="text" id="support_ticket_limit" value="{$group.support_ticket_limit}"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" id="submit" value="Submit Modifications"></td>
			</tr>
		</table>
	</form>
</div>
{include file="footer.tpl"}