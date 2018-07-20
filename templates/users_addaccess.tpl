{include file="header.tpl" title="Add Access"}
<div id="content-panel">
	<form action="{$smarty.server.SCRIPT_NAME}?action=submit&amp;subaction=add-access" method="post" enctype="multipart/form-data" name="add-access-form">
		<input name="id" type="hidden" value="{$user_id}" />
		{if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<th colspan="2" scope="col">Add Program Access</th>
			</tr>
			<tr>
				<td width="50%">Username:</td>
				<td width="50%">{$username}</td>
			</tr>
			<tr>
				<td>Program:<br /><span class="description">Select the program to grant access to.</span></td>
				<td><select name="program_id" id="program_id">{html_options options=$program_list selected=$selected_program}</select></td>
			</tr>
			<tr>
				<td>Max Session Count:<br /><span class="description">Allows you to specify the max number of sessions this user can run of this application. (0 is infinite)</span></td>
				<td><input name="max_session_count" type="text" id="max_session_count" value="{$max_session_count}" /></td>
			</tr>
			<tr>
				<td>Expiration Time:<br /><span class="description">Allows you to specify a time range for this permission to expire.</span></td>
				<td>{html_options name=expiration_time options=$expiration_list}</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" id="submit" value="Submit" /></td>
			</tr>
		</table>
	</form>
</div>
{include file="footer.tpl"}