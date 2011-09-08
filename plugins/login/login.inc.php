<?php
session_start(); // Start a new session

 function authenticate($user,$pass){
    if (!$user || !$pass) {
        return false;
    }
    $tableUsers = new Axon(DB_TABLE_USERS);
	$tableUsers->load("username='$user'");
	if (!$tableUsers->dry() && $tableUsers->password == sha1(trim($pass) . BOOKI_SALT)) {
		return true;
	} else {
		return false;
	}
 }

function login_admin() {

  if ((array_value($_GET, 'action')=='logout')) {
      $_SESSION['loggedIn'] = "false";
      Log::insert('logged out');
  }
  if ((array_value($_GET, 'action')=='login') && ($_SESSION['loggedIn'] !=='true')   ){
	// Get the data passed from the form
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Do some basic sanitizing
	$username = stripslashes($username);
	$password = stripslashes($password);
  	if (authenticate($username,$password)){
       		$_SESSION['loggedIn'] = "true";
       		$_SESSION['username'] = $username;
       		Log::insert('logged in');
	} else {
	    Log::insert('wrong password attempt', $username);
	}
  }
  if (array_value($_SESSION, 'loggedIn') !=='true'){
         	$_SESSION['loggedIn'] = "false";
  		$html = '<h1>'._('welcome').'</h1>';
  		$html.= _('Please log in').' <br>
		<form action="admin.php?plugin=login&action=login" method="post">
         	<table>
                  <tr>
                           <td>'._('Username').': </td>
                           </td><td><input type="text" name="username"></td>
                  </tr>
                  <tr>
                           <td>'._('Password').': </td>
                           <td><input type="password" name="password"></td></tr><tr><td></td>
			   <td><input type="submit" value="login"></td>
                  </tr>
         	</table>
	</form>';
	}
  if (array_value($_SESSION, 'loggedIn') == 'true'){
  		$html = '<h1>'._('welcome').'</h1>';
  		$html.= _('You are now logged in');
  }



  return $html;
}

function login_dispatcher($name) {
  if($name == "admin") return login_admin();
}

function login_adminnav() {
	if (array_value($_SESSION, 'loggedIn') == 'true') {
	    return "<li><a href='admin.php?plugin=login&action=logout'>"._("log out")."</a></li>";
	} else {
	    return null;
	}
}

function login_initialize() {
  add_hook("admin_nav", "login_adminnav");
}

function login_install() {

}

function login_uninstall() {

}

function login_plugin() {
  return Array("info" => Array("author" => "Adam Hyde",
			       "version" => "1.0")
	       );
}
