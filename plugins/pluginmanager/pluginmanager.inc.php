<?php

function pluginmanager_admin() {
	$html = '<h1>'._('Plugin Manager').'</h1>';
	$action = array_value($_GET, 'action');
	if ($action == 'update'){
		$dh = @opendir('plugins/');
		while (($dirname = readdir($dh)) !== false){
			if (($dirname !=='.') && ($dirname!=='..')) $move=$_POST[$dirname];
			if ($move == 'disabled'){
				rename('plugins/'.$dirname, 'disabled_plugins/'.$dirname);
			}
		}
		closedir($dh);
		$dh = @opendir('disabled_plugins/');
		while (($dirname = readdir($dh)) !== false){
			if (($dirname !=='.') && ($dirname!=='..')) $move=$_POST[$dirname];
			if ($move == 'active'){
				rename('disabled_plugins/'.$dirname, 'plugins/'.$dirname);
			}
		}
		closedir($dh);

	}

	$html .= '<table><tr><td></td><td>'._('Activate').'</td><td>'._('Disable').'</td>';
	$html .= '<form action ="admin.php?plugin=pluginmanager&action=update" method="post">';
	$dh = @opendir('plugins/');
	while (($dirname = readdir($dh)) !== false){
		if (($dirname !=='.') && ($dirname!=='..')) $html.='<tr><td>'.$dirname.'</td><td><input type=radio name='.$dirname.' checked value="active"></td><td><input type=radio name='.$dirname.' value="disabled"><td></tr>';
	}
	closedir($dh);
	$dh = @opendir('disabled_plugins/');
	while (($dirname = readdir($dh)) !== false){
		if (($dirname !=='.') && ($dirname!=='..')) $html.='<tr><td>'.$dirname.'</td><td><input type=radio name='.$dirname.' value="active"></td><td><input type=radio name='.$dirname.' value="disabled" checked><td></tr>';
	}
	closedir($dh);
	$html .= '<tr><td></td><td></td><td><input type="submit" value="'._('update').'"></td></tr>';
	$html .= '</table>';
	$html .= '</form>';
	$dh = @opendir('plugins/');
	while (($dirname = readdir($dh)) !== false){
		if (($dirname !=='.') && ($dirname!=='..')) {
			$html.="<h2>".$dirname."</h2>";
			$infoarray= call_user_func($dirname."_plugin");
				//print_r($infoarray);
				foreach($infoarray["info"] as $key=>$value) $html.="<b>".$key." : </b>".$value."<br>";
		}
	}
	closedir($dh);
	$dh = @opendir('disabled_plugins/');
	while (($dirname = readdir($dh)) !== false){
		if (($dirname !=='.') && ($dirname!=='..')) {
			$html.="<h2>".$dirname." "._("(disabled)")."</h2>";
			include_once("disabled_plugins/".$dirname."/".$dirname.".inc.php");
			$infoarray= call_user_func($dirname."_plugin");
				//print_r($infoarray);
				foreach($infoarray["info"] as $key=>$value) $html.="<b>".$key." : </b>".$value."<br>";
		}
	}
	closedir($dh);

  	return $html;
}


function pluginmanager_dispatcher($name) {
  if($name == "admin") return pluginmanager_admin();
}


function pluginmanager_afterdisplay() {
}

function pluginmanager_initialize() {
  add_hook("admin_nav", "pluginmanager_adminnav");
}

function pluginmanager_install() {

}

function pluginmanager_uninstall() {

}

function pluginmanager_adminnav() {
	return	createAdminNav("pluginmanager","plugins");
}

function pluginmanager_plugin() {
  return Array("info" => Array("author" => "Adam Hyde",
			       "license" => "AGPL",
			       "description" => "Activate/deactivate plugins.",
			       "version" => "1.0")
	       );
}

?>
