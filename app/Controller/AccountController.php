<?php
class AccountController extends AppController {    
    var $helpers = array();
    var $uses = array('Game', 'Character', 'Classe', 'Race', 'RaidsRole', 'EventsCharacter', 'Availability');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->breadcrumb[] = array('title' => __('My MushRaider account'), 'url' => '/account');

        $this->bridge = json_decode($this->Setting->getOption('bridge'));
        $this->set('bridge', $this->bridge);
    }

    public function index() {
       $this->setAction('personal');
       return;
    }

    public function characters($action = null) {
        $this->pageTitle = __('My MushRaider characters').' - '.$this->pageTitle;

        if($action) {
            $this->setAction('characters_'.$action);
            return;
        }
        
        $this->breadcrumb[] = array('title' => __('Characters'), 'url' => '');

    	if(!empty($this->request->data['Character'])) {
    		$toSave = array();
    		$toSave['title'] = $this->request->data['Character']['title'];
    		$toSave['slug'] = $this->Tools->slugMe($toSave['title']);
    		$toSave['game_id'] = $this->request->data['Character']['game_id'];
    		$toSave['classe_id'] = $this->request->data['Character']['classe_id'];
            $toSave['race_id'] = $this->request->data['Character']['race_id'];
    		$toSave['default_role_id'] = $this->request->data['Character']['default_role_id'];
    		$toSave['level'] = $this->request->data['Character']['level'];
    		$toSave['user_id'] = $this->user['User']['id'];

            // If it's the first character for this game, it's a main !
            $params = array();
            $params['recursive'] = -1;
            $params['conditions']['Character.user_id'] = $this->user['User']['id'];        
            $params['conditions']['Character.game_id'] = $this->request->data['Character']['game_id'];
            $params['conditions']['Character.main'] = 1;
            $params['conditions']['Character.status'] = array(0, 1);
            if(!$character = $this->Character->find('first', $params)) {
                $toSave['main'] = 1;
            }

    		if($this->Character->save($toSave)) {
    			$this->Session->setFlash(__('%s has been added to your character list', $toSave['title']), 'flash_success');
    			$this->redirect('/account/characters');
    		}

    		$this->set('showForm', $showForm);
    		$this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
    	}

    	// Games list for the form
    	$gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);

        // Get all the characters
        $params = array();
        $params['recursive'] = 1;
        $params['order'] = 'Character.status DESC, Game.title ASC, Character.title ASC';
        $params['contain']['Classe'] = array();
        $params['contain']['User'] = array();
        $params['contain']['Race'] = array();
        $params['contain']['Game'] = array();
        $params['contain']['RaidsRole'] = array();
        $params['conditions']['Character.user_id'] = $this->user['User']['id'];        
        $params['conditions']['Character.status'] = array(0, 1);
        $characters = $this->Character->find('all', $params);        
        $this->set('characters', $characters);
    }

    public function characters_edit() {
        $this->pageTitle = __('Edit a character').' - '.$this->pageTitle;

    	$c = explode('-', $this->request->params['named']['c']);
    	$characterId = $c[0];

    	// Get the character
        $params = array();
        $params['recursive'] = 1;
        $params['conditions']['Character.id'] = $characterId;
        $params['conditions']['Character.user_id'] = $this->user['User']['id'];
        if(!$character = $this->Character->find('first', $params)) {
        	$this->Session->setFlash(__('MushRaider  can\'t find this character oO'), 'flash_error');
        	return $this->redirect('/account/characters');
        }

        if(!empty($this->request->data['Character'])) {
        	// if char id in url and post id isn't the same, something is wrong so we redirect
        	if($this->request->data['Character']['id'] != $characterId || empty($this->request->data['Character']['id'])) {
        		return $this->redirect('/account/characters');
        	}

    		$toSave = array();
    		$toSave['id'] = $this->request->data['Character']['id'];
    		$toSave['title'] = $this->request->data['Character']['title'];
    		$toSave['slug'] = $this->Tools->slugMe($toSave['title']);
    		$toSave['game_id'] = $this->request->data['Character']['game_id'];
    		$toSave['classe_id'] = $this->request->data['Character']['classe_id'];
            $toSave['race_id'] = $this->request->data['Character']['race_id'];
    		$toSave['default_role_id'] = $this->request->data['Character']['default_role_id'];
    		$toSave['level'] = $this->request->data['Character']['level'];
    		$toSave['user_id'] = $this->user['User']['id'];
    		if($this->Character->save($toSave)) {
    			$this->Session->setFlash(__('%s has been edited successfully', $toSave['title']), 'flash_success');
    			return $this->redirect('/account/characters');
    		}

    		$this->set('showForm', $showForm);
    		$this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
    	}

        $this->request->data['Character'] = !empty($this->request->data['Character'])?array_merge($character['Character'], $this->request->data['Character']):$character['Character'];

        // Games list for the form
    	$gamesList = $this->Game->find('list', array('order' => 'title ASC'));
        $this->set('gamesList', $gamesList);

        $classesList = $this->Classe->find('list', array('conditions' => array('game_id' => $this->request->data['Character']['game_id']), 'order' => 'title ASC'));
        $this->set('classesList', $classesList);

        $racesList = $this->Race->find('list', array('conditions' => array('game_id' => $this->request->data['Character']['game_id']), 'order' => 'title ASC'));
        $this->set('racesList', $racesList);

        $rolesList = $this->RaidsRole->find('list', array('order' => 'title ASC'));
        $this->set('rolesList', $rolesList);

        $this->breadcrumb[] = array('title' => __('Characters'), 'url' => '/account/characters');
        $this->breadcrumb[] = array('title' => $character['Character']['title'], 'url' => '');

    	return $this->render('characters_edit');
    }

    public function characters_delete() {
        $c = explode('-', $this->request->params['named']['c']);
        $characterId = $c[0];

        // Get the character
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $characterId;
        $params['conditions']['user_id'] = $this->user['User']['id'];
        if(!$character = $this->Character->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider  can\'t find this character oO'), 'flash_error');
            return $this->redirect('/account/characters');
        }

        // Delete character        
        if(!$this->Character->delete($characterId)) {
            $this->Session->setFlash(__('MushRaider can\'t delete this character oO'), 'flash_success');
        }else {
            $deleteCond = array('EventsCharacter.character_id' => $characterId);
            $this->EventsCharacter->deleteAll($deleteCond);
            $this->Session->setFlash(__('The character %s has been deleted', $character['Character']['title']), 'flash_success');
        }

        return $this->redirect('/account/characters');
    }

    public function characters_disable() {
        $c = explode('-', $this->request->params['named']['c']);
        $characterId = $c[0];

        // Get the character
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $characterId;
        $params['conditions']['user_id'] = $this->user['User']['id'];
        if(!$character = $this->Character->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider  can\'t find this character oO'), 'flash_error');
            return $this->redirect('/account/characters');
        }

        $toSave = array();
        $toSave['id'] = $characterId;
        $toSave['status'] = 0;
        if($this->Character->save($toSave)) {
            $this->Session->setFlash(__('The character %s has been disabled', $character['Character']['title']), 'flash_success');
        }else {
            $this->Session->setFlash(__('MushRaider can\'t disable this character oO'), 'flash_success');
        }

        return $this->redirect('/account/characters');
    }

    public function characters_enable() {
        $c = explode('-', $this->request->params['named']['c']);
        $characterId = $c[0];

        // Get the character
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $characterId;
        $params['conditions']['user_id'] = $this->user['User']['id'];
        $params['conditions']['status'] = '0';
        if(!$character = $this->Character->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider  can\'t find this character oO'), 'flash_error');
            return $this->redirect('/account/characters');
        }

        $toSave = array();
        $toSave['id'] = $characterId;
        $toSave['status'] = 1;
        if($this->Character->save($toSave)) {
            $this->Session->setFlash(__('The character %s has been enabled', $character['Character']['title']), 'flash_success');
        }else {
            $this->Session->setFlash(__('MushRaider can\'t enable this character oO'), 'flash_success');
        }

        return $this->redirect('/account/characters');
    }

    public function personal() {
        $this->pageTitle = __('My MushRaider personal informations').' - '.$this->pageTitle;
        $this->breadcrumb[] = array('title' => __('Personal informations'), 'url' => '');

        if(!empty($this->request->data['User'])) {
            $toSave = array();
            $toSave['id'] = $this->user['User']['id'];
            $toSave['username'] = trim($this->request->data['User']['username']);
            if(empty($this->bridge) || !$this->bridge->enabled) {
                $toSave['email'] = trim($this->request->data['User']['email']);
            }
            if($this->User->save($toSave)) {
                $this->Session->setFlash(__('Your settings were saved successfully'), 'flash_success');
                $this->redirect('/account/personal');
            }else {
                $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
            }
        }

        $this->request->data['User']['username'] = isset($this->request->data['User']['username'])?$this->request->data['User']['username']:$this->user['User']['username'];
        $this->request->data['User']['email'] = isset($this->request->data['User']['email'])?$this->request->data['User']['email']:$this->user['User']['email'];
    }

    public function password() {
        if(!empty($this->bridge) && $this->bridge->enabled) {
            $this->Session->setFlash(__('Bridge system is enabled so you can\'t change your password here.'), 'flash_warning');
            $this->redirect('/account');
        }

        $this->pageTitle = __('My MushRaider password').' - '.$this->pageTitle;
        $this->breadcrumb[] = array('title' => __('Password'), 'url' => '');

        if(!empty($this->request->data['User'])) {
            if(empty($this->request->data['User']['currentpassword']) || empty($this->request->data['User']['newpassword']) || empty($this->request->data['User']['newpassword2'])) {
                $this->Session->setFlash(__('All the fields are mandatory'), 'flash_error');
            }elseif($this->request->data['User']['newpassword'] != $this->request->data['User']['newpassword2']) {
                $this->Session->setFlash(__('The new password isn\'t the same than his confirmation, is it that difficult ?'), 'flash_error');
            }else {
                $params = array();
                $params['fields'] = array('id');
                $params['recursive'] = -1;
                $params['conditions']['id'] = $this->user['User']['id'];
                $params['conditions']['password'] = md5($this->request->data['User']['currentpassword']);
                if(!$this->User->find('first', $params)) {
                   $this->Session->setFlash(__('Wrong current password, try again'), 'flash_error'); 
                }else {
                    $toSave = array();
                    $toSave['id'] = $this->user['User']['id'];
                    $toSave['password'] = md5($this->request->data['User']['newpassword']);
                    if($this->User->save($toSave)) {
                        $this->Session->setFlash(__('Your password has been updated'), 'flash_success');
                        $this->redirect('/account');
                    }

                    $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
                }
            }
        }

        $this->request->data['User'] = array();        
    }

    public function notifications() {
        $this->pageTitle = __('My MushRaider notifications').' - '.$this->pageTitle;
        $this->breadcrumb[] = array('title' => __('Notifications'), 'url' => '');

        if(!empty($this->request->data['User'])) {
            $toSave = array();
            $toSave['id'] = $this->user['User']['id'];
            $toSave['notifications_cancel'] = $this->request->data['User']['notifications_cancel']?1:0;
            $toSave['notifications_new'] = $this->request->data['User']['notifications_new']?1:0;
            $toSave['notifications_validate'] = $this->request->data['User']['notifications_validate']?1:0;
            if($this->User->save($toSave)) {
                $this->Session->setFlash(__('Your notification settings were saved successfully'), 'flash_success');
                $this->redirect('/account/notifications');
            }else {
                $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
            }
        }

        $this->request->data['User']['notifications_cancel'] = $this->user['User']['notifications_cancel'];
        $this->request->data['User']['notifications_new'] = $this->user['User']['notifications_new'];
        $this->request->data['User']['notifications_validate'] = $this->user['User']['notifications_validate'];
    }    

    public function availabilities($action = null, $absenceId = null) {
        $this->pageTitle = __('Absences management').' - '.$this->pageTitle;

        if($action && $absenceId) {
            $this->setAction('availabilities_'.$action, $absenceId);
            return;
        }
        
        $this->breadcrumb[] = array('title' => __('Absences'), 'url' => '');

        if(!empty($this->request->data['Availability'])) {
            $toSave = array();
            if(!empty($this->request->data['Availability']['id'])) {
                $toSave['id'] = $this->request->data['Availability']['id'];
            }
            $toSave['user_id'] = $this->user['User']['id'];
            $toSave['start'] = $this->Tools->reverseDate($this->request->data['Availability']['start']);
            $toSave['end'] = $this->Tools->reverseDate($this->request->data['Availability']['end']);
            $toSave['comment'] = trim(strip_tags($this->request->data['Availability']['comment']));
            if($this->Availability->save($toSave)) {
                $this->Session->setFlash(__('Your absence has been added successfully'), 'flash_success');
                $this->redirect('/account/availabilities');
            }else {
                $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
            }
        }

        $params = array();
        $params['order'] = array('start ASC');
        $params['conditions']['user_id'] = $this->user['User']['id'];
        $availabilities = $this->Availability->find('all', $params);

        $this->set('availabilities', $availabilities);
    }

    public function availabilities_delete($absenceId) {
        // Get the absence
        $params = array();
        $params['recursive'] = -1;
        $params['conditions']['id'] = $absenceId;
        $params['conditions']['user_id'] = $this->user['User']['id'];
        if(!$absence = $this->Availability->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider  can\'t find this absence oO'), 'flash_error');
            return $this->redirect('/account/availabilities');
        }

        // Delete absence        
        if(!$this->Availability->delete($absenceId)) {
            $this->Session->setFlash(__('MushRaider can\'t delete this absence oO'), 'flash_success');
        }else {
            $this->Session->setFlash(__('The absence has been deleted'), 'flash_success');
        }

        return $this->redirect('/account/availabilities');
    }

    public function calendar() {
        $this->pageTitle = __('Calendar management').' - '.$this->pageTitle;
        
        $this->breadcrumb[] = array('title' => __('Calendar'), 'url' => '');

        $games = $this->Game->find('list', array('order' => 'title asc'));
        $this->set('games', $games);
    }
}