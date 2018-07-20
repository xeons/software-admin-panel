{include file="header.tpl" title="Add User"}
<div id="content-panel">
  <form action="{$smarty.server.SCRIPT_NAME}?action=submit&amp;subaction=add" method="post" enctype="multipart/form-data" name="form1">
    {if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th colspan="2" scope="col">Add New User</th>
      </tr>
      <tr>
        <td width="40%">Username:<br>
          <span class="description">Must be between 3-20 characters.</span></td>
        <td><input name="username" type="text" id="username"></td>
      </tr>
      <tr>
        <td>Password:<br>
          <span class="description">Must be between 6-20 characters and should be something difficult.</span></td>
        <td><input name="password" type="text" id="password"></td>
      </tr>
      <tr>
        <td>Package:<br>
          <span class="description">A package associated with this user.</span></td>
        <td>{html_radios name=group_id options=$group_list selected=$selected_group separator='<br />'}</td>
      </tr>
      <tr>
        <td>Email Address (optional):<br>
          <span class="description">A contact email address.</span></td>
        <td><input name="email_address" type="text" id="email_address"></td>
      </tr>
      <tr>
        <td>Active<br>
          <span class="description">Is this user active? If this isn't checked they won't have access to anything.</span></td>
        <td><input name="active" type="checkbox" id="active" value="1" checked></td>
      </tr>
      <tr>
        <td>Admin<br>
          <span class="description">Is this user an admin?</span></td>
        <td><input name="admin" type="checkbox" id="admin" value="1"></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="submit" id="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
</div>
{include file="footer.tpl"}