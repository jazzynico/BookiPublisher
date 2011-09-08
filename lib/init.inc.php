<?php
include_once(dirname(__FILE__).'/functions.inc.php');
include_once(dirname(__FILE__).'/plugin.inc.php');
include_once(dirname(__FILE__).'/widget.inc.php');
include_once(dirname(__FILE__).'/classes/F3.php');

// initialize F3 ORM library
F3::set('AUTOLOAD', INSTALLED_DIR . '/lib/classes');
F3::set('DB', array(
	'dsn' => DB_DSN,
    'user' => DB_USER,
    'password' => DB_PASSWORD,
    )
);

// load and initialize plugins
load_plugins();
init_plugins();

load_widgets();
init_widgets();

?>
