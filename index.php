<?php
// index.php
/*
 * This is the starting file for the project.
 */
define ( 'DS', DIRECTORY_SEPARATOR );
// Installation root directory
define ( 'INSTALL_ROOT_DIR', dirname ( __FILE__ ) . DS );
// required directories
define ( 'CORE_DIR', INSTALL_ROOT_DIR . 'core' . DS );
define ( 'CONFIG_DIR', INSTALL_ROOT_DIR . 'config' . DS );
// Library directories
define ( 'LIB_DIR', INSTALL_ROOT_DIR . 'lib' . DS );

require_once CONFIG_DIR . 'init.php';

AppSession::getInstance () -- > start ();

$app = Application::getInstance ();
$app->init ();
// TODO event management
// TODO handle routing
$app->display ();
// TODO handle final displa
?>

