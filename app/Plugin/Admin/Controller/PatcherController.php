<?php
class PatcherController extends AdminAppController {
    public $components = array('Patcher');
    public $uses = array('Character', 'EventsCharacter', 'Event');

    var $adminOnly = true;
    var $availablePatchs = array('beta-2', 'beta-3', 'v-1.1', 'v-1.3', 'v-1.3.5', 'v-1.4', 'v-1.4.1', 'v-1.5', 'v-1.5.2', 'v-1.6', 'v-1.6.2');
    var $dbPrefix = 'mr_';

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
    }

    public function apply($patch = null) {
        if(!in_array($patch, $this->availablePatchs)) {
            $this->Session->setFlash(__('MushRaider can\'t find this patch'), 'flash_error');
            return $this->redirect('/admin');
        }

    	if(!empty($this->request->data['Patcher'])) {
            $db = ConnectionManager::getDataSource('default');
            $db->cacheSources = false;
            
    		$error = false;
	    	$databaseConfig = Configure::read('Database');
            $this->dbPrefix = $databaseConfig['prefix'];
	        if(!$mysqlLink = mysqli_connect($databaseConfig['host'], $databaseConfig['login'], $databaseConfig['password'], $databaseConfig['database'], $databaseConfig['port'])) {
	            $error = true;
	        }
	    	
	    	$sqlReport = $this->Patcher->run_sql_file($mysqlLink, '../Config/Schema/sql/mushraider_patch_'.$patch.'.sql', $this->dbPrefix);
	    	$mysqlLink = null;

	        if($sqlReport['success'] != $sqlReport['total']) {
	            $error = true;
	        }

	        if($error) {
	        	$this->Session->setFlash(__('MushRaider can\'t apply the SQL patch, please try again or apply it by yourself using the following file : /app/Config/Schema/sql/mushraider_patch_%s.sql', $patch), 'flash_error');
                return $this->redirect('/admin/patcher/apply/'.$patch);
	        }else {
	        	// If there is code to execute...
                Cache::clear(false, '_cake_core_');
                Cache::clear(false, '_cake_model_');
                Cache::clear(false);
                
                $methodName = str_replace('-', '', $patch);
	        	$methodName = str_replace('.', '', $methodName);
	        	if(method_exists($this, $methodName)) {
	        		$this->$methodName();	        		
	        	}

                // Delete cache for obvious reasons :p
                Cache::clear(false, '_cake_core_');
                Cache::clear(false, '_cake_model_');
                Cache::clear(false);

	        	$this->Session->setFlash(__('MushRaider successfully apply the patch !'), 'flash_success');
	        }

	        return $this->redirect('/admin');
	    }

	    $this->set('patch', $patch);
    }

    public function beta3() {
    	// Update new 'created' field of each character
    	// It will match the date of the first event the character signin
    	// This ensure the stats to be accurate

    	// Generate new schema
    	$this->Character->schemaBeta3();
    	Cache::clear(false);

    	$params = array();
    	$params['recursive'] = -1;
    	$params['fields'] = array('id', 'game_id');
    	if($characters = $this->Character->find('all', $params)) {
    		foreach($characters as $character) {
    			$toSaveCharacter['id'] = $character['Character']['id'];

				// Get first event
				$params = array();
				$params['recursive'] = -1;
				$params['fields'] = array('id', 'created');
				$params['order'] = 'created ASC';
				$params['conditions']['character_id'] = $character['Character']['id'];
				if($eventCharacter = $this->EventsCharacter->find('first', $params)) {
					$toSaveCharacter['created'] = $eventCharacter['EventsCharacter']['created'];
				}else {
					$toSaveCharacter['created'] = date('Y-m-d H:i:s');
				}
												
				$this->Character->save($toSaveCharacter);
    		}
    	}
    }

    public function v14() {
        // Regenerate cache
        Cache::clear(false);

        /*
        * API
        */
        // Copy bridge secret key to API private key
        $bridge = json_decode($this->Setting->getOption('bridge'));
        if(!empty($bridge) && $bridge->enabled && !empty($bridge->secret)) {
            $api = array();
            $api['enabled'] = 0;
            $api['privateKey'] = $bridge->secret;
            $this->Setting->setOption('api', json_encode($api));

            // Disable bridge to make use users update their bridge plugin
            $bridgeSettings = array();
            $bridgeSettings['enabled'] = 0;
            $bridgeSettings['url'] = $bridge->url;
            $this->Setting->setOption('bridge', json_encode($bridgeSettings));

            $this->Session->setFlash(__('Bridge has been disabled ! Be sure to use an updated version of your bridge plugin for MushRaider 1.4. If you don\'t you\'re gonna have a bad time !'), 'flash_important', array(), 'important');
        }

        /*
        * Import
        */
        // Add absolute path to games's logo field to prepare import functionallity
        App::uses('Game', 'Model');
        $GameModel = new Game();
        $params = array();
        $params['recursive'] = -1;
        $params['fields'] = array('id', 'logo');
        if($games = $GameModel->find('all', $params)) {
            foreach($games as $game) {
                if(!empty($game['Game']['logo']) && strpos($game['Game']['logo'], '/files/') === false) {
                    $toUpdate = array();
                    $toUpdate['id'] = $game['Game']['id'];
                    $toUpdate['logo'] = '/files/logos/'.$game['Game']['logo'];
                    $GameModel->create();
                    $GameModel->save($toUpdate);
                }
            }
        }

        /*
        * Roles permissions
        */
        // Add roles permissions
        $rolesPermissions = array(
            array('title' => __('Full permissions'), 'alias' => 'full_permissions', 'description' => __('Like Chuck Norris, he can do anything. This overwrite every permissions')),
            array('title' => __('Limited admin access'), 'alias' => 'limited_admin', 'description' => __('Like Robin, he can do some things but not all (like driving the batmobile or change user role)')),
            array('title' => __('Can manage events'), 'alias' => 'manage_events', 'description' => __('Can create, edit and delete events. Can also manage the roster for each events')),
            array('title' => __('Can create templates'), 'alias' => 'create_templates', 'description' => __('Can create events templates')),
            array('title' => __('Can create reports'), 'alias' => 'create_reports', 'description' => __('Can create events reports'))
        );
        App::uses('RolePermission', 'Model');
        $RolePermissionModel = new RolePermission();
        foreach($rolesPermissions as $rolesPermission) {
            $RolePermissionModel->create();
            $RolePermissionModel->save($rolesPermission);
        }

        // Add new roles permissions to existing roles
        App::uses('Role', 'Model');
        $RoleModel = new Role();
        App::uses('RolePermissionRole', 'Model');
        $RolePermissionRoleModel = new RolePermissionRole();
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('admin'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('full_permissions')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('limited_admin')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('manage_events')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('create_templates')));
        $RolePermissionRoleModel->__add(array('role_id' => $RoleModel->getIdByAlias('officer'), 'role_permission_id' => $RolePermissionModel->getIdByAlias('create_reports')));
    }

    public function v15() {
        // Regenerate cache
        Cache::clear(false);

        // Notifications
        $notifications = array(
                            'enabled' => 1,
                            'signup' => 0,
                            'contact' => ''
                        );
        $this->Setting->setOption('notifications', json_encode($notifications));

        // Calendar settings
        $calendar = array(
                        'weekStartDay' => 0,
                        'title' => 'event',
                        'timeToDisplay' => 'time_invitation',
                        'gameIcon' => 1,
                        'dungeonIcon' => 1
                    );

        $this->Setting->setOption('calendar', json_encode($calendar));

        // Set main characters        
        $sql = "SELECT t.user_id, t.game_id, t.character_id, MAX(t.used) AS nb_used
                FROM (
                    SELECT ec.user_id, e.game_id, ec.character_id, COUNT(ec.id) AS used
                    FROM ".$this->dbPrefix."events_characters ec 
                    JOIN ".$this->dbPrefix."users u ON ec.user_id=u.id
                    JOIN ".$this->dbPrefix."events e ON e.id=ec.event_id
                    GROUP BY ec.character_id
                    ORDER BY used DESC, u.id ASC, e.game_id ASC, ec.character_id
                ) t
                GROUP BY t.user_id, t.game_id";
        if($eventsCharacters = $this->EventsCharacter->query($sql)) {
            foreach($eventsCharacters as $eventsCharacter) {
                $toUpdate = array();
                $toUpdate['id'] = $eventsCharacter['t']['character_id'];
                $toUpdate['main'] = 1;
                $this->Character->save($toUpdate);
            }
        }

        $params = array();
        $params['recursive'] = -1;
        $params['group'] = array('user_id', 'game_id');
        $params['fields'] = array('id', 'user_id', 'game_id');
        $params['order'] = array('main DESC', 'level DESC');
        if($characters = $this->Character->find('all', $params)) {
            foreach($characters as $character) {
                $params = array();
                $params['recursive'] = -1;
                $params['fields'] = array('Character.id');
                $params['conditions']['user_id'] = $character['Character']['user_id'];
                $params['conditions']['game_id'] = $character['Character']['game_id'];
                $params['conditions']['main'] = 1;
                if(!$this->Character->find('first', $params)) {
                    $toUpdate = array();
                    $toUpdate['id'] = $character['Character']['id'];
                    $toUpdate['main'] = 1;
                    $this->Character->save($toUpdate);
                }
            }
        }

        // New role own events
        $toSaveRole = array('title' => __('Can manage own events only'), 'alias' => 'manage_own_events', 'description' => __('Can create, edit and delete own events only. Can also manage the roster for his events'));
        App::uses('RolePermission', 'Model');
        $RolePermissionModel = new RolePermission();
        $RolePermissionModel->create();
        $RolePermissionModel->save($toSaveRole);

        // Mushstats
        $this->Setting->setOption('Mushstats', time());
    }

    public function v152() {
        // Regenerate cache
        Cache::clear(false);

        // Timezone
        $this->Setting->setOption('timezone', 'Europe/Paris');

        // Update events registration end date
        App::uses('Event', 'Model');
        $EventModel = new Event();
        $params = array();
        $params['recursive'] = -1;
        $params['fields'] = array('id', 'time_start', 'time_inscription');
        $params['conditions']['time_inscription'] = null;
        if($events = $EventModel->find('all', $params)) {
            foreach($events as $event) {
                $EventModel->query("UPDATE ".$EventModel->tablePrefix."events SET time_inscription='".$event['Event']['time_start']."' WHERE id = ".$event['Event']['id']);
            }
        }
    }

    public function v16() {
        // Regenerate cache
        Cache::clear(false);

        // Generate calendar's keys
        App::uses('User', 'Model');
        $UserModel = new User();
        $params = array();
        $params['recursive'] = -1;
        $params['fields'] = array('id');
        $params['conditions']['status'] = array(0, 1);
        if($users = $UserModel->find('all', $params)) {
            foreach($users as $user) {
                $toUpdate = array();
                $toUpdate['id'] = $user['User']['id'];
                $toUpdate['calendar_key'] = uniqid();
                $UserModel->save($toUpdate);
            }
        }
    }

    public function v162() {
        // Regenerate cache
        Cache::clear(false);

        // Update Notifications
        $notifications = json_decode($this->Setting->getOption('notifications'), true);
        $notifications['comments'] = 0;
        $this->Setting->setOption('notifications', json_encode($notifications));
    }
}