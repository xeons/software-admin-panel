<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Software Administration Panel - {$title}</title>
<link rel="stylesheet" type="text/css" href="css/default.css">
<link rel="shortcut icon" href="favicon.ico" />
<link type="text/css" href="css/ui-darkness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script language="javascript" src="js/main.js"></script>
</head>
<body>
<div id="container">
<div id="title"><h1>Software Administration Panel</h1></div>
{if $authenticated}
<div id="navigation-panel">
  <ul>
    <li class="navigation-title">Navigation</li>
    <li><a href="index.php"><img src="img/page_white_star.png" width="16" height="16" alt="Home" /> Home</a></li>
    <li><a href="settings.php"><img src="img/cog.png" width="16" height="16" alt="Settings" /> Settings</a></li>
    <li><a href="groups.php"><img src="img/package.png" width="16" height="16" alt="Groups" /> Groups</a></li>
    <li><a href="programs.php"><img src="img/application_form.png" width="16" height="16" alt="Programs" /> Programs</a></li>
    <li><a href="users.php"><img src="img/group.png" width="16" height="16" alt="Users" /> Users</a></li>
    <li><a href="bans.php"><img src="img/shield.png" width="16" height="16" alt="Bans" /> Bans</a></li>
    <li><a href="sessions.php"><img src="img/application_go.png" width="16" height="16" alt="Sessions" /> Sessions</a></li>
    <li><a href="exception_log.php"><img src="img/application_error.png" width="16" height="16" alt="Exception Log" /> Exception Log</a></li>
    <li><a href="activity_log.php"><img src="img/book_open.png" width="16" height="16" alt="Activity Log" /> Activity Log</a></li>
    <li><a href="link_log.php"><img src="img/world_link.png" width="16" height="16" alt="Link Log" /> Link Log</a></li>
    <li><a href="login.php?action=logout"><img src="img/application_form_delete.png" width="16" height="16" alt="Logout" /> Logout</a></li>
    <li class="navigation-footer">&nbsp;</li>
  </ul>
</div>
{/if}
