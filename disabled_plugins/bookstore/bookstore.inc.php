<?php

function bookstore_index() {
  $html.= '<h1>Book Store Plugin</h1>';
  #$html = addTemplate('editor',$html);
  return $html;

}


function bookstore_dispatcher($name) {
  return bookstore_index();
}


function bookstore_afterdisplay() {
}

function bookstore_initialize() {
  add_hook("admin_nav", "bookstore_adminnav");
}

function bookstore_install() {

}

function bookstore_uninstall() {

}

function bookstore_adminnav() {
$nav= "<li><a href='admin.php?plugin=bookstore'>bookstore</a></li>";
return $nav;
}

function bookstore_plugin() {
  return Array("info" => Array("author" => "Aleksandar Erkalovic",
			       "version" => "1.0")
	       );
}

?>
