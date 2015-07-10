<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

    App::uses('Setting', 'Model');
    $SettingModel = new Setting();
    if($customHomePage = $SettingModel->getOption('homePage')) {
        App::uses('Page', 'Model');
        $PageModel = new Page();
        if($page = $PageModel->find('first', array('conditions' => array('id' => $customHomePage)))) {
            Router::connect('/', array('controller' => 'pages', 'action' => 'view', $page['Page']['id'], $page['Page']['slug']));
        }else {
            Router::connect('/', array('controller' => 'events', 'action' => 'index'));    
        }
    }else {
        Router::connect('/', array('controller' => 'events', 'action' => 'index'));
    }

    Router::connect('/l/*', array('controller' => 'home', 'action' => 'index'));
    Router::connect('/pages/preview/*', array('controller' => 'pages', 'action' => 'preview'));
    Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'view'));

// Admin routes
    Router::connect('/admin', array('plugin' => 'admin', 'controller' => 'dashboard', 'action' => 'index'));
    Router::connect('/install/step/*', array('plugin' => 'install', 'controller' => 'step', 'action' => 'index'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
    CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
    require CAKE . 'Config' . DS . 'routes.php';

// Extensions
    Router::parseExtensions('xml', 'ics');
