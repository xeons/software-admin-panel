{include file="header.tpl" title=Index}
<div id="dialog" title="Confirmation">
  <p>Are you sure you want to perform this action?</p>
</div>
<div id="content-panel">
  <h1>Programs</h1>
  <p><a href="programs.php?action=add"><img src="img/application_form_add.png" width="16" height="16" /> Add new program.</a></p>
  <table width="100%" cellpadding="2" cellspacing="0">
    <tr>
      <th>Name/Description</th>
      <th>Version/Last Updated</th>
      <th width="120">Options</th>
    </tr>
    {foreach from=$program_list key=program_id item=program}
    <tr>
      <td><strong>{$program.name|escape:'htmlall'} (ID: {$program.id})</strong><br />
      <span class="description">{$program.description|escape:'htmlall'}</span></td>
      <td>{$program.major_version}.{$program.minor_version}.{$program.revision_version}<br />
      {if $program.last_updated gt 0}{$program.last_updated|date_format:"%D %r"}{else}Never{/if}</td>
      <td><a href="programs.php?action=edit&amp;id={$program.id}"><img src="img/application_form_edit.png" width="16" height="16" /> Edit</a> <a class="action-link" href="programs.php?action=delete&amp;id={$program.id}"><img src="img/application_form_delete.png" width="16" height="16" /> Delete</a></td>
    </tr>
    {foreachelse}
    <tr>
      <td colspan="3">No programs found...</td>
    </tr>
    {/foreach}
  </table>
</div>
{include file="footer.tpl"}