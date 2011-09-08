<?php

function empty_index() {
  //normal rendering etc outputs etc here
  //dont use echo etc, return strings
  return $html;
}

function empty_admin() {
  //admin outputs etc here
  //dont use echo etc, return strings
  return $html;
}

function empty_dispatcher($name) {
  if($name == "admin") return empty_admin();
  if($name == "index") return empty_index();
}

function empty_afterdisplay() {
}

function empty_initialize() {
  //attach functions to hooks
  //see index.php and admin.php for some hooks
  //also lib/templates.inc.php
  add_hook("admin_nav", "empty_adminnav");
  add_hook("admin_after_display", "empty_afterdisplay");
}

function empty_install() {

}

function empty_uninstall() {

}

function empty_adminnav() {
  //add	button to admin index
  return createAdminNav("empty","button text");
}

function empty_plugin() {
  return Array("info" => Array("author" => "you",
			       "license" => "AGPL",
			       "description" => "template for plugins",
			       "requirements" => "some tech info",
			       "version" => "x.x")
	       );
}

?>
