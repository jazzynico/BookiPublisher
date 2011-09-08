<?php

$_plugins = Array();
$_hooks = Array();

function add_hook($hook_type, $func_name) {
  global $_hooks;

  $_hooks[$hook_type][] = $func_name;
}

function fire_hook($hook_type, $args) {
  global $_hooks;
  $action = null;

  if(array_key_exists($hook_type, $_hooks)) {
    foreach($_hooks[$hook_type] as $func_name) {
      if(function_exists($func_name)) {
    $action.=call_user_func_array($func_name, $args);
      }
    }
  }
return $action;
}

function load_plugins() {
  global $_plugins;

  if (is_dir(INSTALLED_DIR."/plugins/")) {
    if ($dh = opendir(INSTALLED_DIR."/plugins/")) {

      while (($file = readdir($dh)) !== false) {
    if($file != "." && $file != ".." && is_dir(INSTALLED_DIR."/plugins/".$file)) {
      include_once(INSTALLED_DIR."/plugins/".$file."/".$file.".inc.php");
      $_plugins[$file] = call_user_func($file."_plugin");
    }
      }

      closedir($dh);
    }
  } else {
    // error
    echo "this is not directory";
  }


}

function init_plugins() {
  global $_plugins;

  foreach($_plugins as $name => $plugin) {
    if(function_exists($name."_initialize")) {
      call_user_func($name."_initialize");
    }
  }
}

function checkRights ($pluginname) {
    $tableUsers = new Axon(DB_TABLE_USERS);
    $tableUsers->load("username='" . array_value($_SESSION, "username") . "'");
    if (!$tableUsers->dry()) {
            $plugins = unserialize($tableUsers->plugins);
            return (array_value($plugins, $pluginname) == 'on');
    }
    return false;
}

function createAdminNav ($plugin,$buttonText,$action="") {
    $actionURL = null;
    if ($action!="") {
        $actionURL="&action=".$action;
        $buttonText=$action;
    }
    if (checkRights($plugin)) {
        $nav= "<li><a href='admin.php?plugin=$plugin".$actionURL."'>$buttonText</a></li>";
        return $nav;
    }
    return;
}
