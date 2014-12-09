<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    function __add($data = array(), $cond = array()) {
        if(empty($data)) {
            return false;
        }

        $params = array();
        $params['recursive'] = -1;
        $params['fields'] = array('id');
        if(!empty($data['slug'])) {
            $params['conditions']['slug'] = $data['slug'];
        }
        $params['conditions'] = array_merge($params['conditions'], $cond);

        if($result = $this->find('first', $params)) {
            $toSave = array();
            $toSave['id'] = $result[$this->alias]['id'];
            $toSave = array_merge($toSave, $data);
            if(!empty($data['import_modified'])) {
                $toSave['import_modified'] = $data['import_modified'];
            }
            $this->save($toSave);

            return $result[$this->alias]['id'];
        }
        $this->create();
        if($this->save($data)) {
            return $this->getLastInsertId();
        }

        return false;
    }
}
