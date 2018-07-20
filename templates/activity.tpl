{include file="header.tpl" title="Activity"}
<div id="content-panel">
	<h1>Activity log</h1>
	<form action="{$smarty.server.SCRIPT_NAME}" method="get" enctype="multipart/form-data" name="activity-filter-form">
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<th colspan="2" scope="col">Activity Log Filters</th>
			</tr>
			<tr>
				<td>User:</td>
				<td>{html_options name=filter_user_id options=$user_id_list selected=$selected_user_id}</td>
			</tr>
			<tr>
				<td>Action:</td>
				<td>{html_options name=filter_action values=$action_list output=$action_list selected=$selected_action}</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" id="submit" value="Submit"></td>
			</tr>
		</table>
	</form>
	<br />
	<table width="100%" cellpadding="2" cellspacing="0" class="center">
	<tr>
		<th>IP</th>
		<th>Username</th>
		<th>Program</th>
		<th>Action</th>
		<th>Time</th>
	</tr>
	{foreach from=$activity_log key=activity_id item=activity}
	<tr>
		<td>{$activity.ipaddr}</td>
		<td>{$activity.username|escape:'htmlall'}</td>
		<td>{$activity.program_name|escape:'htmlall'}</td>
		<td>{$activity.action}</td>
		<td>{$activity.activity_time|date_format:"%D %r"}</td>
	</tr>
	{foreachelse}
	<tr>
		<td colspan="4">No activity logged</td>
	</tr>
	{/foreach}
	</table>

	<p>Page: 
	{for $i=$page_min to $page_max}
	<a href="{$smarty.server.SCRIPT_NAME}?page={$i}&amp;filter_user_id={$selected_user_id}&amp;filter_action={$selected_action}">{$i+1}</a>
	{if $i < $page_max}| {/if}
	{/for}
	</p>
</div>
{include file="footer.tpl"}