<?php
// init.php

/*
 * This file will initiate the loading of the configurations for the application
 */
require_once CONFIG_DIR . 'config.php';
require_once CORE_DIR . 'autoload.php';
require_once CORE_DIR . 'init.php';

mb_internal_encoding ( 'UTF-8' );

spl_autoload_register(array('Autoload', 'autoload'));
?>