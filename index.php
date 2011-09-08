<?php
include_once(dirname(__FILE__).'/config.inc.php');
include_once(dirname(__FILE__).'/lib/init.inc.php');
include_once(dirname(__FILE__).'/lib/templates.inc.php');
#error_reporting(1);

// default page
$PAGE_NAME = "index";
$PLUGIN_NAME = "read";

if(isset($_GET["page"])) $PAGE_NAME = $_GET["page"];
if(isset($_POST["page"])) $PAGE_NAME = $_POST["page"];

if(isset($_GET["plugin"])) $PLUGIN_NAME = $_GET["plugin"];
if(isset($_POST["plugin"])) $PLUGIN_NAME = $_POST["plugin"];


// load page and show it
if(function_exists($PLUGIN_NAME."_dispatcher")) {
  // should not be using $output at all
  // should get some kind of Response object, so i can normaly
  // change http headers, do redirects, return JSON and etc...
  		echo fire_hook("before_display", Array());
  		$output =  call_user_func($PLUGIN_NAME."_dispatcher", $PAGE_NAME);
  		echo $output;
  		echo fire_hook("after_display", Array());
}

?>
