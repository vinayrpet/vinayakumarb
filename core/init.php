<?php
// init.php
/**
 * This file is to specify all classes for the Autoload
 */
$classArray = array (
    'Application' => CORE_DIR . 'application.php',
    'AppSession' => CORE_DIR . 'session.php',
    'ThemeService' => VIEW_DIR . 'theme.php',
    'Theme' => VIEW_DIR . 'theme.php',
    'View' => VIEW_DIR . 'view.php',
    'MySmarty' => VIEW_DIR . 'view.php',
    'Router' => CORE_DIR . 'router.php',
    'Route' => CORE_DIR . 'router.php',
    'PluginManager' => CORE_DIR . 'plugin.php' 
);

Autoload::getInstance ()->addClasses ( $classArray );
?>