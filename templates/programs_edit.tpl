{include file="header.tpl" title=Index}
<div id="content-panel">
  <form action="{$smarty.server.SCRIPT_NAME}?action=submit&amp;subaction=edit" method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="id" id="id" value="{$program.id}">
    {if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th colspan="2" scope="col">Add New Program/Edit Program</th>
      </tr>
      <tr>
        <td>Name:<br>
          <span class="description">The name of the program.</span></td>
        <td><input name="name" type="text" id="name" value="{$program.name}" size="50" maxlength="100"></td>
      </tr>
      <tr>
        <td>Description:<br>
          <span class="description">Short description of the program <br>
          (Max: 255 chars).</span></td>
        <td><input name="description" type="text" id="description" value="{$program.description}" size="50" maxlength="255"></td>
      </tr>
      <tr>
        <td>Major Version:<br>
          <span class="description">The first number of the version number.</span></td>
        <td><input name="major_version" type="text" id="major_version" value="{$program.major_version}" size="4"></td>
      </tr>
      <tr>
        <td>Minor Version:<br>
          <span class="description">The second number of the version number.</span></td>
        <td><label for="minor_version"></label>
          <input name="minor_version" type="text" id="minor_version" value="{$program.minor_version}" size="4"></td>
      </tr>
      <tr>
        <td>Revision Version:<br>
          <span class="description">The third number of the version number.</span></td>
        <td><label for="revision_version"></label>
          <input name="revision_version" type="text" id="revision_version" value="{$program.revision_version}" size="4"></td>
      </tr>
      <tr>
        <td>Active<br>
          <span class="description">Is this application active? If this isn't checked no users will have access.</span></td>
        <td><input name="active" type="checkbox" id="active" value="1"{if $program.active} checked{/if}>
          <label for="active"></label></td>
      </tr>
      <tr>
        <td>Update Executable:<br>
          <span class="description">Only select a file if you wish to roll out a new update.</span></td>
        <td><label for="exe"></label>
          <input name="exe" type="file" id="exe" size="50"></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="submit" id="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
</div>
{include file="footer.tpl"}