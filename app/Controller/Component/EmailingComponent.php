<?php
App::uses('ComponentCollection', 'Controller');
 App::uses('CakeEmail', 'Network/Email');

class EmailingComponent extends Component {

    var $email;

    //public function startup(Controller $controller) {
    public function startup(Controller $controller) {
        $emailSettings = Configure::read('Config.email');

        $emailConfig = null;
        if(!empty($emailSettings)) {
            if($emailSettings->transport == 'Smtp') {
                $emailConfig = array();
                $emailConfig['transport'] = $emailSettings->transport;
                $emailConfig['host'] = $emailSettings->host;
                $emailConfig['port'] = $emailSettings->port;
                $emailConfig['username'] = $emailSettings->username;
                $emailConfig['password'] = $emailSettings->password;
            }
            $this->email = new CakeEmail($emailConfig);
            $this->email->emailFormat('html');
            $this->email->from(array($emailSettings->from => $emailSettings->name));
            if($emailSettings->encoding == 'utf8') {
                $this->email->charset($emailSettings->encoding);
            }
        }else { // No email settings, generate minimal
            $this->email = new CakeEmail();
            $this->email->emailFormat('html');
            $host = substr_count($_SERVER['HTTP_HOST'], '.') > 1?substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.') + 1):$_SERVER['HTTP_HOST'];                        
            $host = strpos($host, ':') !== false?substr($host, 0, strpos($host, ':')):$host; // Remove port if present on unusual configurations
            $this->email->from(array('mushraider@'.$host => 'MushRaider'));
        }
    }

    private function send() {
        try {
            $this->email->send();
        }catch(Exception $e) {

        }
    }

    function signup($dest) {
        $this->email->to($dest);
        $this->email->subject(__('Welcome to MushRaider'));
        $this->email->template('signup', 'default');

        $this->email->viewVars(array(
            'dest' => $dest
        ));
        $this->send();
    }

    function signupNotification($dest, $username, $userId) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] New signup !'));
        $this->email->template('signup_notification', 'default');

        $this->email->viewVars(array(
            'dest' => $dest,
            'username' => $username,
            'userId' => $userId
        ));
        $this->send();
    }

    function commentEvent($dest, $comment) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] New event comment'));
        $this->email->template('comment_event', 'default');

        $this->email->viewVars(array(
            'dest' => $dest,
            'comment' => $comment,
        ));
        $this->send();
    }

    function userEnabled($dest, $username) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] You account has been activated'));
        $this->email->template('user_enabled', 'default');

        $this->email->viewVars(array(
            'dest' => $dest,
            'username' => $username
        ));
        $this->send();
    }

    function recovery($dest, $hash) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] Recover your password'));
        $this->email->template('recovery', 'default');

        $this->email->viewVars(array(
            'dest' => $dest,
            'token' => $hash
        ));
        $this->send();
    }

    function eventCancel($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] Event cancelled :('));
        $this->email->template('event_cancel', 'default');

        $this->email->viewVars(array(
            'event' => $event
        ));
        $this->send();
    }

    function eventNew($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] New Event has been added'));
        $this->email->template('event_new', 'default');

        $this->email->viewVars(array(            
            'event' => $event
        ));
        $this->send();
    }

    function eventEdit($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] An Event has been updated'));
        $this->email->template('event_edit', 'default');

        $this->email->viewVars(array(            
            'event' => $event
        ));
        $this->send();
    }

    function eventValidate($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] You have been validate to an event =)'));
        $this->email->template('event_validate', 'default');

        $this->email->viewVars(array(            
            'event' => $event
        ));
        $this->send();
    }

    function eventRefuse($dest, $event) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] You have been refused to an event :('));
        $this->email->template('event_refuse', 'default');

        $this->email->viewVars(array(            
            'event' => $event
        ));
        $this->send();
    }
}