<?php

$_widgets = Array();


function load_widgets() {
  global $_widgets;
  
  if (is_dir(INSTALLED_DIR."/widgets/")) {
    if ($dh = opendir(INSTALLED_DIR."/widgets/")) {
      
      while (($file = readdir($dh)) !== false) {
	if($file != "." && $file != ".." && is_dir(INSTALLED_DIR."/widgets/".$file)) {
	  include_once(INSTALLED_DIR."/widgets/".$file."/".$file.".inc.php");
	  $_widgets[$file] = call_user_func($file."_widget");
	}
      }
      
      closedir($dh);
    }
  } else {
    // error
    echo "this is not directory";
  }
  

}

function init_widgets() {
  global $_widgets;

  foreach($_widgets as $name => $widget) {
    if(function_exists($name."_initialize")) {
      call_user_func($name."_initialize");
    }
  }
}

function render_widgets($book, $chapter, $content) {
  global $_widgets;
  foreach($_widgets as $name => $widget) {
    if(function_exists($name."_render")) {
      $widget = call_user_func($name."_render", $book, $chapter);
      $content = preg_replace("[<widget-$name/>]", $widget, $content);
    }
  }
  return $content;
}


?>
