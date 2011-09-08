<?php

function redirect_index() {
  //normal rendering etc outputs etc here
  //dont use echo etc, return strings
  return $html;
}

function redirect_admin() {
  //admin outputs etc here
  //dont use echo etc, return strings

if (array_value($_REQUEST, 'action') == "save") {
    $orig= $_POST['originalurl'];
    $new= $_POST['newurl'];
    $new_redirect=$orig.",".$new."\n";
	if ($orig!=""){
  		file_put_contents("data/redirect.info",$new_redirect, FILE_APPEND);
	}
}
	$html ="<h1>Redirect</h1>";
	$html.="<p>Redirects URLS within booki publisher</p>";
	$html.="<h2>Add Redirect</h2>";

        $html .= '<form action="admin.php?plugin=redirect&action=save" method="POST">';
        $html .= '<table>';
        $html .=  '<tr><td>'._('original URL (full)').'</td><td><input type="text" name="originalurl" value="" size="35"/></td></tr>';
        $html .=  '<tr><td>'._('new URL (full)').' </td><td><input type="text" name="newurl" value="" size="35"/></td></tr>';
        $html .= '</table>';
        $html .= '<br><input type="submit" value="'._('save').'"></form>';


	$html.="<h2>Current redirects</h2>";
   	$redirect_file = "data/redirect.info";
        $redirect_info = file_get_contents($redirect_file);
	$redirect_info=str_replace("\n","<BR>",$redirect_info);
	$html.=$redirect_info;

  return $html;
}

function redirect_dispatcher($name) {
  if($name == "admin") return redirect_admin();
  if($name == "index") return redirect_index();
}

function redirect_afterdisplay() {
}

function redirect_initialize() {
  //attach functions to hooks
  //see index.php and admin.php for some hooks
  //also lib/templates.inc.php
  add_hook("admin_nav", "redirect_adminnav");
  add_hook("admin_after_display", "redirect_afterdisplay");
}

function redirect_install() {

}

function redirect_uninstall() {

}

function redirect_adminnav() {
  //add	button to admin index
  return createAdminNav("redirect","redirect");
}

function redirect_plugin() {
  return Array("info" => Array("author" => "adam",
			       "license" => "AGPL",
			       "description" => "enables redirect of urls",
			       "requirements" => "some tech info",
			       "version" => "x.x")
	       );
}

?>
