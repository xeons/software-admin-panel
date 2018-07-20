{include file="header.tpl" title=Login}
<div id="login">
  <h2>Admin Login</h2>
  {if isset($error_message)}<span class="error">Error: {$error_message}</span>{/if}
  <form id="login-form" name="login-form" method="post" action="login.php?action=login">
    <p>
      <label><strong>Username</strong>: <br />
        <input name="username" type="text" id="username" value="{if isset($username)}{$username|escape}{/if}" />
      </label>
    </p>
    <p>
      <label><strong>Password</strong>: <br />
        <input name="password" type="password" id="password" value="{if isset($password)}{$password|escape}{/if}" />
      </label>
    </p>
    {if isset($recaptcha_html)}
    <p> <strong>Security code</strong>:<br />
    {$recaptcha_html}
    </p>
    {/if}
    <p>
      <label>
        <input type="submit" name="submit" id="submit" value="Submit" />
      </label>
    </p>
  </form>
</div>
{include file="footer.tpl"}