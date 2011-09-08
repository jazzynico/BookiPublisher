<?php

function usermanager_admin() {
    $tableUsers = new Axon(DB_TABLE_USERS);

	$dirs = array();
	$dh = @opendir('plugins/');
	while (($dirname = readdir($dh)) !== false){
		if (($dirname !=='.') && ($dirname!=='..') && ($dirname!=='login')) {
		    $dirs[] = $dirname;
		};
	}
	closedir($dh);

	$allBooks = array();
	$url = OBJAVI_SERVER_URL."/?server=".BOOKI_SERVER_TARGET."&book=null&mode=booklist";
	$content = file_get_contents($url);
    $content = str_replace('</option>', '|', str_replace('<option value="', '', $content));
    $content = explode('|', trim(str_replace('">', '^', $content)));
    foreach ($content as $book) {
        if ($book = trim($book)) {
            $book = explode('^', $book);
            $allBooks[$book[0]] = array_value($book, 1) ? array_value($book, 1) : $book[0];
        }
    }

	if (array_value($_GET, 'action') == 'removeuser' && array_value($_POST, 'username')) {
		$username = array_value($_POST, 'username');
		$tableUsers->load("username = '$username'");
		if (!$tableUsers->dry()) {
		    $tableUsers->erase();
		}
	}
	if (array_value($_GET, 'action') == 'adduser' && trim(array_value($_POST, 'username'))){
        $tableUsers->username = trim(array_value($_POST, 'username'));
        $tableUsers->password = sha1(trim(array_value($_POST, 'password')) . BOOKI_SALT);
        $tableUsers->email = trim(array_value($_POST, 'email'));
        $tableUsers->save();
	}
    $html = '<script type="text/javascript">
$(function(){
	$(".multiselect").multiselect();
});
</script>';
	$html.= '<h1>userInfo Plugin</h1>';
	$html.= '<table><tr><td><b>Add User</b></td><td></td></tr><tr><td>';
  	$html.=	'<form action="admin.php?plugin=usermanager&action=adduser" method="post">';
	$html.= 'username : </td><td><input type="text" name="username"</td></tr><tr><td>';
	$html.= 'password : </td><td><input type="text" name="password"</td></tr><tr><td>';
	$html.= 'email : </td><td><input type="text" name="email"</td></tr><tr>';
	$html.= '<tr><td></td><td><input type="submit" value="add user"></td></tr>';
	$html.= '</form></table>';
	$html.= '<br><br>';

  	$html.=	'<form action="admin.php?plugin=usermanager&action=removeuser" method="post">';
	$html.= '<table><tr><td colspan=2><b>Remove User</b></td></tr>';
	$html.= '<tr><td>';
	$html.= 'username : </td><td><input type="text" name="username"></td></tr>';
	$html.= '<tr><td></td><td><input type="submit" value="remove user"></td></tr>';
	$html.= '</form></table>';

	if (array_value($_GET, 'action') == 'change' && $_POST) {
		$users = $tableUsers->find();
		foreach ($users as $user) {
		    $plugins = array();
		    $checked = array_value($_POST, array('plugins', $user['username']));
		    foreach ((array)$checked as $dir) {
		        $plugins[$dir] = 'on';
			}
		    $books = array();
		    $checked = array_value($_POST, array('books', $user['username']));
		    foreach ((array)$checked as $book) {
		        $book = explode('|', $book);
		        $books[$book[0]] = $book[1];
			}
			$tableUsers->load("username = '{$user['username']}'");
			$tableUsers->plugins = $plugins ? serialize($plugins) : null;
			$tableUsers->books = $books ? serialize($books) : null;
			$tableUsers->email = array_value($_POST, array('email', $user['username']));
			$tableUsers->save();
		}
	}

	$html.= '<br><br>';
  	$html.=	'<form action="admin.php?plugin=usermanager&action=change" method="post">';
	$html.= '<table><tr><td colspan="3">';
	$html.= '<b>Users edit</b></td></tr><tr>';
	$html.= '<td></td><td>email</td><td>plugins access</td><td>books access</td></tr><tr>';
	$users = $tableUsers->find();
	foreach ($users as $user) {
		$html.='<tr><td>'.$user['username'].'</td>';
        $html.='<td><input type="text" name="email['.$user['username'].']" value="'.$user['email'].'"></td>';

        $html.='<td><select class="multiselect" title="Plugins access" multiple="multiple" name="plugins['.$user['username'].'][]" size="5">';
	    $plugins = @unserialize($user['plugins']);
        foreach ($dirs as $dir) {
			if (array_value((array)$plugins, $dir) == 'on') {
				$selected=' selected="selected"';
			} else {
				$selected=null;
			}
			$html.="<option$selected>$dir</option>";
		}
	    $html.= '</select></td>';

	    $html.='<td><select class="multiselect" title="Books access" multiple="multiple" name="books['.$user['username'].'][]" size="5">';
	    $books = @unserialize($user['books']);
        foreach ($allBooks as $key => $value) {
			if (array_value((array)$books, $key)) {
				$selected=' selected="selected"';
			} else {
				$selected=null;
			}
			$html.="<option value=\"$key|$value\" $selected>$value</option>";
		}
	    $html.= '</select></td></tr>';
	}
	$html.= '<tr><td></td><td colspan="3"><input type="submit" value="change"></td><tr></table>';
	return $html;
}


function usermanager_dispatcher($name) {
  if($name === "admin") return usermanager_admin();
}


function usermanager_afterdisplay() {
}

function usermanager_initialize() {
  add_hook("admin_nav", "usermanager_adminnav");
}

function usermanager_install() {

}

function usermanager_adminnav() {
	return	createAdminNav("usermanager","userInfo");
}

function usermanager_uninstall() {

}


function usermanager_plugin() {
  return Array("info" => Array("author" => "Adam Hyde",
			       "license" => "AGPL",
			       "description" => "Manage access to plugins.",
			       "version" => "1.0")
	       );
}
