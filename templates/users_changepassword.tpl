{include file="header.tpl" title="Change Password"}
<div id="content-panel">
  <form action="{$smarty.server.SCRIPT_NAME}?action=submit&amp;subaction=change-password" method="post" enctype="multipart/form-data" name="form1">
    {if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th colspan="2" scope="col">Change Password</th>
      </tr>
      <tr>
        <td>Username:</td>
        <td>{$username} <input name="id" type="hidden" value="{$user_id}" /></td>
      </tr>
      <tr>
        <td>New Password:</td>
        <td><input type="password" name="password" id="password"></td>
      </tr>
      <tr>
        <td>Confirm Password:</td>
        <td><input type="password" name="password2" id="password2"></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="submit" id="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
</div>
{include file="footer.tpl"}