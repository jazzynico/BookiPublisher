<?php

include_once(dirname(__FILE__).'/config.inc.php');
include_once(dirname(__FILE__).'/lib/init.inc.php');
include_once(dirname(__FILE__).'/lib/templates.inc.php');

// default page
$PAGE_NAME = "admin";
$PLUGIN_NAME = "login";

if(isset($_GET["page"])) $PAGE_NAME = $_GET["page"];
if(isset($_POST["page"])) $PAGE_NAME = $_POST["page"];

if(isset($_GET["plugin"])) $PLUGIN_NAME = $_GET["plugin"];
if(isset($_POST["plugin"])) $PLUGIN_NAME = $_POST["plugin"];

if (array_value($_SESSION, 'loggedIn') != 'true') {
	$PLUGIN_NAME = 'login';
} else {
	if (!checkRights($PLUGIN_NAME)) $PLUGIN_NAME = 'login';
}

// load page and show it
if(function_exists($PLUGIN_NAME."_dispatcher")) {
  // should not be using $output at all
  // should get some kind of Response object, so i can normaly
  // change http headers, do redirects, return JSON and etc...
  		fire_hook("admin_before_display", Array());
  		$output =  call_user_func($PLUGIN_NAME."_dispatcher", $PAGE_NAME);

  		$haha =fire_hook("admin_nav", Array());
		//echo $haha;

		$output=addTemplate('editor',$output);

		if ($_SESSION['loggedIn'] == 'true') $output = preg_replace('[<admin-index/>]',$haha,$output);
		#addTemplate('editor',$output);
		echo $output;
  		fire_hook("admin_after_display", Array());
}
