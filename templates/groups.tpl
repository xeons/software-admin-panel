{include file="header.tpl" title=Index}
<div id="dialog" title="Confirmation">
    <p>Are you sure you want to perform this action?</p>
</div>
<div id="content-panel">
    <h1>Groups</h1>
    <p><a href="groups.php?action=add-group"><img src="img/package_add.png" width="16" height="16" /> Add new group.</a></p>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Options</th>
        </tr>
        {foreach from=$group_list key=group_id item=group}
        <tr>
            <td valign="top">{$group.name}</td>
            <td valign="top">{$group.description|escape:'htmlall'|nl2br}</td>
            <td valign="top">
                <a href="groups.php?action=edit&id={$group.id}"><img src="img/group_edit.png" width="16" height="16" /> Modify</a><br />
                <a class="action-link" href="groups.php?action=delete&id={$group.id}"><img src="img/group_delete.png" width="16" height="16" /> Delete</a>
            </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="3">No packages found.</td>
        </tr>
        {/foreach}
    </table>
</div>
{include file="footer.tpl"}