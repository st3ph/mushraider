<?php
class AjaxController extends AppController {
    public $components = array('Emailing');
    var $uses = array('Game', 'Dungeon', 'Classe', 'Race', 'EventsCharacter', 'EventsRole', 'Character', 'Event', 'RaidsRole', 'EventsTemplate', 'EventsTemplatesRole');

    function beforeFilter() {
        parent::beforeFilter();

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = false;
    }

    function getListByGame() {
        if(!empty($this->request->query['game'])) {
            $gameId = $this->request->query['game'];

            $classesList = $this->Classe->find('list', array('conditions' => array('game_id' => $gameId), 'order' => 'title ASC'));
            $this->set('classesList', $classesList);

            $racesList = $this->Race->find('list', array('conditions' => array('game_id' => $gameId), 'order' => 'title ASC'));
            $this->set('racesList', $racesList);

            $rolesList = $this->RaidsRole->find('list', array('order' => 'title ASC'));
            $this->set('rolesList', $rolesList);

            $this->render('/Elements/char_form_elements');
        }

        return;
    }

    function getDungeonsByGame() {
        if(!empty($this->request->query['game'])) {
            $gameId = $this->request->query['game'];

            $dungeonsList = $this->Dungeon->find('all', array('fields' => array('id', 'title'), 'recursive' => -1, 'conditions' => array('game_id' => $gameId), 'order' => 'title ASC'));
            return json_encode($dungeonsList);
        }
    }

    function eventSignin() {
        $jsonMessage = array();
        if(!empty($this->request->query['u']) && !empty($this->request->query['e']) && isset($this->request->query['signin']) && !empty($this->request->query['character'])) {
            // Choosed character must be in the event level range
            $params = array();
            $params['fields'] = array('character_level');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $this->request->query['e'];
            if(!$event = $this->Event->find('first', $params)) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('MushRaider can\'t find this event');
                return json_encode($jsonMessage);
            }

            $params = array();
            $params['fields'] = array('Character.level', 'Character.title', 'Classe.*');
            $params['recursive'] = 1;
            $params['contain']['Classe'] = array();
            $params['conditions']['Character.id'] = $this->request->query['character'];
            $params['conditions']['Character.user_id'] = $this->user['User']['id'];
            $params['conditions']['Character.level >='] = $event['Event']['character_level'];
            if(!$character = $this->Character->find('first', $params)) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Your character mush be above the level %s', $event['Event']['character_level']);
                return json_encode($jsonMessage);
            }

            $toSave = array();
            $toSave['event_id'] = $this->request->query['e'];
            $toSave['user_id'] = $this->request->query['u'];
            $toSave['character_id'] = !empty($this->request->query['character'])?$this->request->query['character']:null;
            $toSave['raids_role_id'] = !empty($this->request->query['role'])?$this->request->query['role']:null;
            $toSave['comment'] = trim(strip_tags($this->request->query['c']));
            $toSave['status'] = $this->request->query['signin'];
            if($this->EventsCharacter->__add($toSave)) {
                $jsonMessage['type'] = $toSave['status']?'info':'warning';
                $jsonMessage['msg'] = 'ok';

                $rosterHtml = '<li data-id="'.$toSave['character_id'].'" data-user="'.$toSave['user_id'].'">';
                    $rosterHtml .= '<span class="character" style="color:'.$character['Classe']['color'].'">';
                        if(!empty($character['Classe']['icon'])) {
                            $rosterHtml .= '<img src="'.$character['Classe']['icon'].'" class="tt" title="'.$character['Classe']['title'].'" width="16" />';
                        }else {
                            $rosterHtml .= $character['Classe']['title'];
                        }
                        $rosterHtml .= ' '.$character['Character']['title'].' ('.$character['Character']['level'].')';
                    $rosterHtml .= '</span>';
                    if(!empty($toSave['comment'])) {
                        $rosterHtml .= '<span class="tt" title="'.$toSave['comment'].'"><span class="icon-comments-alt"></span></span>';
                    }
                $rosterHtml .= '</li>';
                $jsonMessage['html'] = $rosterHtml;
            }else {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Error while adding your character');
            }
        }

        return json_encode($jsonMessage);
    }

    function roster() {
        if(isset($this->request->query['v']) && isset($this->request->query['refused']) && !empty($this->request->query['r']) && !empty($this->request->query['e'])) {
            $eventId = $this->request->query['e'];
            $roleId = str_replace('role_', '', $this->request->query['r']);
            $validatedList = explode(',', $this->request->query['v']);
            $refusedList = explode(',', $this->request->query['refused']);

            $params = array();
            $params['fields'] = array('id', 'character_id');
            $params['recursive'] = 1;
            $params['contain']['User']['fields'] = array('email', 'notifications_validate');
            $params['conditions']['EventsCharacter.event_id'] = $eventId;
            $params['conditions']['EventsCharacter.raids_role_id'] = $roleId;
            $params['conditions']['EventsCharacter.status >'] = 0;
            if($eventCharacters = $this->EventsCharacter->find('all', $params)) {
                if($notificationsStatus = $this->Setting->getOption('notifications')) {
                    // Get event for email notifications
                    $params = array();
                    $params['recursive'] = -1;
                    $params['conditions']['id'] = $eventId;
                    $event = $this->Event->find('first', $params);
                }
          

                foreach($eventCharacters as $eventCharacter) {
                    $toSave = array();
                    $toSave['id'] = $eventCharacter['EventsCharacter']['id'];
                    if(in_array($eventCharacter['EventsCharacter']['character_id'], $refusedList)) {
                        $toSave['status'] = 3;    
                    }elseif(in_array($eventCharacter['EventsCharacter']['character_id'], $validatedList)) {
                        $toSave['status'] = 2;
                    }else {
                        $toSave['status'] = 1;
                    }
                    if(!$this->EventsCharacter->save($toSave)) {
                        return 'fail';
                    }


                    // If notifications are enable, send email to validated and refused users
                    if($eventCharacter['User']['notifications_validate'] && $notificationsStatus) {
                        if($toSave['status'] == 2) {
                            $this->Emailing->eventValidate($eventCharacter['User']['email'], $event['Event']);
                        }elseif($toSave['status'] == 3) {
                            $this->Emailing->eventRefuse($eventCharacter['User']['email'], $event['Event']);
                        }
                    }
                }

                return 'ok';
            }
        }

        return 'fail';
    }

    function updateRosterChar() {
        if(isset($this->request->query['c']) && !empty($this->request->query['r']) && !empty($this->request->query['e'])) {
            $eventId = $this->request->query['e'];
            $roleId = str_replace('role_', '', $this->request->query['r']);
            $characterId = $this->request->query['c'];

            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['event_id'] = $eventId;
            $params['conditions']['character_id'] = $characterId;
            if($eventCharacter = $this->EventsCharacter->find('first', $params)) {
                $toSave = array();
                $toSave['id'] = $eventCharacter['EventsCharacter']['id'];
                $toSave['raids_role_id'] = $roleId;
                if(!$this->EventsCharacter->save($toSave)) {
                    return 'fail';
                }

                return 'ok';
            }
        }

        return 'fail';
    }

    function getDefaultRole() {
        $jsonMessage = array();
        $jsonMessage['role'] = '';
        if(!empty($this->request->query['character'])) {
            $params = array();
            $params['fields'] = array('default_role_id');
            $params['recursive'] = -1;
            $params['conditions']['Character.id'] = $this->request->query['character'];
            if($character = $this->Character->find('first', $params)) {
                $jsonMessage['role'] = !empty($character['Character']['default_role_id'])?$character['Character']['default_role_id']:'';
            }
        }

        return json_encode($jsonMessage);
    }

    function copyEvent() {
        if(isset($this->request->query['e']) && !empty($this->request->query['name'])) {
            if($this->Event->copy($this->request->query['e'], $this->request->query['name'])) {
                return 'ok';
            }
        }

        return 'fail';
    }

    function loadTemplate() {
        $jsonMessage = array();
        $jsonMessage['type'] = 'error';
        $jsonMessage['msg'] = __('Error while loading the template');
        if(!empty($this->request->query['t'])) {
            // Load the template
            $params = array();
            $params['recursive'] = 1;
            $params['contain']['EventsTemplatesRole'] = array();
            $params['conditions']['id'] = $this->request->query['t'];
            if($eventTemplate = $this->EventsTemplate->find('first', $params)) {
                $jsonMessage['type'] = 'ok';
                $jsonMessage['msg'] = $eventTemplate;
            }
        }

        return json_encode($jsonMessage);
    }

    function filterEvents() {
        if(isset($this->request->query['game'])) {
            $gameId = $this->request->query['game'];
            $this->Cookie->write('filterEvents', $gameId, true, '+2 weeks');
        }

        return;
    }
}