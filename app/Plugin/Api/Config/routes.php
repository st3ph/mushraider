<?php

/**
 * REST API
 */
    Router::mapResources(array('roster', 'events', 'characters'));
    Router::parseExtensions();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
