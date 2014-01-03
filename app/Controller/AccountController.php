<?php
class AccountController extends AppController {    
    var $helpers = array();
    var $uses = array('Game', 'Character', 'Classe', 'Race', 'RaidsRole', 'EventsCharacter');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->breadcrumb[] = array('title' => __('My MushRaider account'), 'url' => '/account');
    }

    public function index() {
       $this->setAction('characters');
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
        $params['order'] = 'Game.title ASC, Character.title';
        $params['contain']['Classe'] = array();
        $params['contain']['User'] = array();
        $params['contain']['Race'] = array();
        $params['contain']['Game'] = array();
        $params['contain']['RaidsRole'] = array();
        $params['conditions']['Character.user_id'] = $this->user['User']['id'];        
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


    public function password() {
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

    public function settings() {
        $this->pageTitle = __('My MushRaider settings').' - '.$this->pageTitle;
        $this->breadcrumb[] = array('title' => __('Settings'), 'url' => '');

        if(!empty($this->request->data['User'])) {
            $toSave = array();
            $toSave['id'] = $this->user['User']['id'];
            $toSave['notifications_cancel'] = $this->request->data['User']['notifications_cancel']?1:0;
            $toSave['notifications_new'] = $this->request->data['User']['notifications_new']?1:0;
            $toSave['notifications_validate'] = $this->request->data['User']['notifications_validate']?1:0;
            if($this->User->save($toSave)) {
                $this->Session->setFlash(__('Your settings were save successfully'), 'flash_success');
                $this->redirect('/account/settings');
            }else {
                $this->Session->setFlash(__('Something wrong happen, please fix the errors below'), 'flash_error');
            }
        }

        $this->request->data['User']['notifications_cancel'] = $this->user['User']['notifications_cancel'];
        $this->request->data['User']['notifications_new'] = $this->user['User']['notifications_new'];
        $this->request->data['User']['notifications_validate'] = $this->user['User']['notifications_validate'];
    }    
}