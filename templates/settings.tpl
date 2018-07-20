{include file="header.tpl" title="Settings"}
<div id="content-panel">
	<form action="{$smarty.server.SCRIPT_NAME}?action=submit" method="post" enctype="multipart/form-data" name="form1">
		{if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
		<h1>Settings</h1>
		<table width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<th width="50%">Setting</th>
				<th width="50%">Value</th>
			</tr>
			{foreach from=$settings_list key=setting_id item=setting_item}
			<tr>
				<td><strong>{$setting_item.display_name|escape:'htmlall'}</strong><br />
				<span class="description">{$setting_item.display_desc|escape:'htmlall'}</span></td>
				<td>
					{if $setting_item.data_type == 'timezone'}
						{html_options name=$setting_item.name values=$timezone_identifiers output=$timezone_identifiers selected=$setting_item.value}
					{elseif $setting_item.data_type == 'int'}
						<input type="text" name="{$setting_item.name}" value="{$setting_item.value}" size="6" />
					{elseif $setting_item.data_type == 'bool'}
						{html_radios name=$setting_item.name options=$bool_options selected=$setting_item.value separator='<br />'}
					{/if}
				</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="3">Error! No settings found!</td>
			</tr>
			{/foreach}
			<tr>
				<td colspan="2"><input type="submit" name="submit" id="submit" value="Save Settings"></td>
			</tr>
		</table>
	</form>
</div>
{include file="footer.tpl"}