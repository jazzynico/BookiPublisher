<?php

function pages_index() {
  return '<html><body>';
}


function pages_dispatcher($name) {
  if($name === "index") return pages_index();
}


function pages_afterdisplay() {
}

function pages_initialize() {
  add_hook("after_display", "pages_afterdisplay");
}

function pages_install() {

}

function pages_uninstall() {

}


function pages_plugin() {
  return Array("info" => Array("author" => "Aleksandar Erkalovic",
			       "version" => "1.0")
	       );
}

?>
